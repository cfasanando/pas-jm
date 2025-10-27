<?php

namespace App\Http\Controllers;

use App\Models\{Acta,Administrado,Infraccion,Sequence,Tipificacion};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActaController extends Controller
{
    public function index(Request $r)
    {
        $q = trim($r->get('q',''));

        $actas = Acta::with(['inspector:id,name', 'administrado:id,nombres,apellidos,razon_social'])
            ->withCount(['evidencias','tipificaciones'])
            ->when($q, function($query) use ($q) {
                $query->where(function($sub) use ($q){
                    $sub->where('numero', 'like', "%$q%")
                        ->orWhere('lugar', 'like', "%$q%")
                        ->orWhere('constatacion', 'like', "%$q%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('actas.index', compact('actas','q'));
    }

    public function create()
    {
        $infracciones = Infraccion::orderBy('codigo')->get();
        return view('actas.create', compact('infracciones'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'fecha'        => 'required|date',
            'hora'         => 'required',
            'lugar'        => 'required|string|max:191',
            'constatacion' => 'nullable|string',
            'tipo_doc'     => 'nullable|in:DNI,RUC,CE,PAS',
            'numero_doc'   => 'nullable|string|max:20',
            'razon_social' => 'nullable|string|max:191',
            'email'        => 'nullable|email|max:191',
            'telefono'     => 'nullable|string|max:50',
            'direccion'    => 'nullable|string|max:191',
            'lat'          => 'nullable|numeric',
            'lng'          => 'nullable|numeric',
            'infracciones'   => 'nullable|array',
            'infracciones.*' => 'exists:infracciones,id',
        ]);

        $acta = DB::transaction(function () use ($data) {
            $administrado = null;
            if (!empty($data['tipo_doc']) && !empty($data['numero_doc'])) {
                $administrado = Administrado::firstOrCreate(
                    ['tipo_doc'=>$data['tipo_doc'], 'numero_doc'=>$data['numero_doc']],
                    [
                        'razon_social'=>$data['razon_social'] ?? null,
                        'email'       =>$data['email'] ?? null,
                        'telefono'    =>$data['telefono'] ?? null,
                        'direccion'   =>$data['direccion'] ?? null,
                    ]
                );
            }

            $acta = Acta::create([
                'fecha'          => $data['fecha'],
                'hora'           => $data['hora'],
                'lugar'          => $data['lugar'],
                'constatacion'   => $data['constatacion'] ?? null,
                'inspector_id'   => Auth::id(),
                'administrado_id'=> $administrado?->id,
                'lat'            => $data['lat'] ?? null,
                'lng'            => $data['lng'] ?? null,
                'estado'         => 'borrador',
            ]);

            if (!empty($data['infracciones'])) {
                $acta->infracciones()->sync($data['infracciones']);
            }
            return $acta;
        });

        return redirect()->route('evidencias.index', $acta)->with('ok', 'Acta guardada en borrador.');
    }

    public function emit(Acta $acta)
    {
        if ($acta->estado !== 'borrador') {
            return back()->with('warn', 'El acta no está en estado borrador.');
        }
        $n = Sequence::next('actas');
        $acta->numero = 'ACT-'.str_pad($n, 6, '0', STR_PAD_LEFT);
        $acta->estado = 'emitida';
        $acta->save();

        return back()->with('ok', 'Acta emitida: '.$acta->numero);
    }

    public function cancel(Acta $acta)
    {
        $acta->estado = 'anulada';
        $acta->save();
        return back()->with('ok', 'Acta anulada.');
    }

    public function show(Acta $acta)
    {
        $acta->load(['inspector','administrado','evidencias','tipificaciones.infraccion']);
        return view('actas.show', compact('acta'));
    }

    public function addTipificacion(Request $request, Acta $acta)
    {
        if ($acta->estado === 'anulada') {
            return redirect()->route('evidencias.index', $acta)
                ->with('warn', 'No se puede editar un acta anulada.');
        }

        // OJO: en BD los campos son multa y observacion
        $data = $request->validate([
            'infraccion_id' => 'required|exists:infracciones,id',
            'multa'         => 'required|numeric|min:0',
            'observacion'   => 'nullable|string|max:1000',
        ]);

        $acta->tipificaciones()->create($data);

        return redirect()->route('evidencias.index', $acta)
            ->with('ok', 'Tipificación agregada.');
    }


    public function removeTipificacion(Tipificacion $tip)
    {
        $acta = $tip->acta;
        $tip->delete();

        return redirect()->route('evidencias.index', $acta)
            ->with('ok', 'Tipificación eliminada.');
    }

    public function updateUbicacion(Request $r, Acta $acta)
    {
        $data = $r->validate(['lat'=>'required|numeric', 'lng'=>'required|numeric']);
        $acta->update($data);
        return back()->with('ok', 'Ubicación actualizada.');
    }

}
