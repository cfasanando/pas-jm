<?php

namespace App\Http\Controllers;

use App\Models\{Boleta, Acta, Sequence};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Mail, Storage};
use Barryvdh\DomPDF\Facade\Pdf;

class BoletaController extends Controller
{
    public function index()
    {
        $boletas = Boleta::with('acta.administrado')->latest()->paginate(20);
        return view('boletas.index', compact('boletas'));
    }

    public function show(Boleta $boleta)
    {
        // Si por un bug previo quedó en 0, lo corregimos con la suma de tipificaciones
        if ((float)$boleta->monto <= 0 && $boleta->acta) {
            $nuevo = (float) $boleta->acta->tipificaciones()->sum('multa');
            if ($nuevo > 0) {
                $boleta->monto = $nuevo;
                $boleta->save();
            }
        }

        // Carga relaciones útiles para la vista
        $boleta->load('acta.administrado', 'acta.inspector', 'acta.tipificaciones.infraccion');

        return view('boletas.show', compact('boleta'));
    }

    // Generar boleta desde un Acta
    public function createFromActa(Acta $acta)
    {
        // 1) Obtener o crear serie por defecto
        $serieRow = DB::table('sequences')
            ->where('key', 'like', 'boleta_%')
            ->orderBy('key')
            ->first();

        if (!$serieRow) {
            DB::table('sequences')->insert([
                'key'        => 'boleta_A001',
                'value'      => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $serie = 'A001';
        } else {
            // key = 'boleta_A001' => 'A001'
            $serie = strtoupper(str_replace('boleta_', '', $serieRow->key));
        }

        // 2) Correlativo atómico
        $n      = Sequence::next('boleta_'.$serie);
        $numero = str_pad((string)$n, 6, '0', STR_PAD_LEFT); // padding fijo 6

        // 3) Monto total desde tipificaciones del acta
        $monto = (float) $acta->tipificaciones()->sum('multa');

        // 4) Crear boleta
        $boleta = Boleta::create([
            'serie'           => $serie,
            'numero'          => $numero,
            'acta_id'         => $acta->id,
            'administrado_id' => $acta->administrado_id,
            'monto'           => $monto,
            'estado'          => 'emitida',
            'pdf_path'        => null,
            'qr_hash'         => null,
        ]);

        return redirect()
            ->route('boletas.show', $boleta)
            ->with('ok', "Boleta {$boleta->serie}-{$boleta->numero} generada.");
    }

    public function pdf(Boleta $boleta)
    {
        // Igual lógica de saneamiento por si quedó en 0
        if ((float)$boleta->monto <= 0 && $boleta->acta) {
            $nuevo = (float) $boleta->acta->tipificaciones()->sum('multa');
            if ($nuevo > 0) {
                $boleta->monto = $nuevo;
                $boleta->save();
            }
        }

        $boleta->load('acta.administrado','acta.inspector','acta.tipificaciones.infraccion');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('boletas.pdf', [
            'boleta' => $boleta,
        ])->setPaper('a4');

        $nombre = "Boleta_{$boleta->serie}-".str_pad($boleta->numero, 6, '0', STR_PAD_LEFT).".pdf";
        return $pdf->download($nombre);
    }

    public function notify(Request $r, Boleta $boleta)
    {
        $to = $r->input('email') ?: optional($boleta->acta->administrado)->email;
        if (!$to) {
            return back()->with('warn', 'No hay correo del administrado');
        }

        // Generar PDF en memoria
        $pdfBin = Pdf::loadView('boletas.pdf', ['boleta' => $boleta])->output();

        // Guardar temporal y adjuntar
        $tmp = 'tmp/'.uniqid('boleta_').'.pdf';
        Storage::put($tmp, $pdfBin);

        // Enviar (usa un Mailable si lo tienes; aquí una versión simple)
        Mail::send([], [], function ($message) use ($to, $boleta, $tmp) {
            $message->to($to)
                ->subject("Boleta {$boleta->serie}-{$boleta->numero}")
                ->setBody("Estimado/a,\n\nAdjuntamos su boleta.\n\nSaludos.", 'text/plain')
                ->attach(Storage::path($tmp), [
                    'as'   => "Boleta_{$boleta->serie}-{$boleta->numero}.pdf",
                    'mime' => 'application/pdf',
                ]);
        });

        // Actualizar estado (sin 'notified_at' para evitar error de columna)
        $boleta->estado = 'notificada';
        $boleta->notified_at = now();
        $boleta->save();

        Storage::delete($tmp);

        return back()->with('ok', 'Boleta notificada por correo.');
    }
}
