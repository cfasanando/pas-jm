<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpedienteController extends Controller
{
    /**
     * Index: list of case files with filters and summary KPIs.
     */
    public function index(Request $request)
    {
        $q      = trim((string) $request->input('q'));
        $estado = $request->input('estado'); // abierto, en_tramite, concluido, archivado or null

        // --- Base query for list ---
        $query = DB::table('expedientes as e')
            ->leftJoin('actas as a', 'a.id', '=', 'e.acta_id')
            ->leftJoin('users as u', 'u.id', '=', 'a.inspector_id')
            ->leftJoin('tipificaciones as t', 't.acta_id', '=', 'a.id')
            ->select(
                'e.id',
                'e.codigo',
                'e.estado',
                'e.fecha_apertura',
                'e.fecha_cierre',
                'a.id as acta_id',
                'a.numero as acta_numero',
                'a.fecha as acta_fecha',
                'a.hora as acta_hora',
                'u.name as inspector_name',
                DB::raw('COALESCE(SUM(t.multa), 0) as multa_total')
            )
            ->groupBy(
                'e.id',
                'e.codigo',
                'e.estado',
                'e.fecha_apertura',
                'e.fecha_cierre',
                'a.id',
                'a.numero',
                'a.fecha',
                'a.hora',
                'u.name'
            );

        // Filter by status if provided
        if ($estado && in_array($estado, ['abierto', 'en_tramite', 'concluido', 'archivado'], true)) {
            $query->where('e.estado', $estado);
        }

        // Text search by code, acta number, inspector or place
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('e.codigo', 'like', "%{$q}%")
                    ->orWhere('a.numero', 'like', "%{$q}%")
                    ->orWhere('u.name', 'like', "%{$q}%")
                    ->orWhere('a.lugar', 'like', "%{$q}%");
            });
        }

        $expedientes = $query
            ->orderByDesc('e.fecha_apertura')
            ->orderByDesc('e.id')
            ->paginate(15)
            ->withQueryString();

        // --- Simple KPIs for header ---
        $kpis = [
            'total'      => DB::table('expedientes')->count(),
            'open'       => DB::table('expedientes')->where('estado', 'abierto')->count(),
            'inProgress' => DB::table('expedientes')->where('estado', 'en_tramite')->count(),
            'closed'     => DB::table('expedientes')->where('estado', 'concluido')->count(),
            'archived'   => DB::table('expedientes')->where('estado', 'archivado')->count(),
        ];

        return view('expedientes.index', [
            'expedientes' => $expedientes,
            'q'           => $q,
            'estado'      => $estado,
            'kpis'        => $kpis,
        ]);
    }

    /**
     * Show a single case file details.
     */
    public function show(int $id)
    {
        $expediente = DB::table('expedientes as e')
            ->leftJoin('actas as a', 'a.id', '=', 'e.acta_id')
            ->leftJoin('users as u', 'u.id', '=', 'a.inspector_id')
            ->leftJoin('administrados as ad', 'ad.id', '=', 'a.administrado_id')
            ->select(
                'e.*',
                'a.id as acta_id',
                'a.numero as acta_numero',
                'a.fecha as acta_fecha',
                'a.hora as acta_hora',
                'a.lugar as acta_lugar',
                'a.estado as acta_estado',
                'u.name as inspector_name',
                'ad.razon_social as admin_razon_social',
                'ad.numero_doc as admin_numero_doc',
                'ad.tipo_doc as admin_tipo_doc',
                'ad.direccion as admin_direccion'
            )
            ->where('e.id', $id)
            ->first();

        if (! $expediente) {
            abort(404);
        }

        // Tipifications + infractions
        $tipificaciones = DB::table('tipificaciones as t')
            ->join('infracciones as i', 'i.id', '=', 't.infraccion_id')
            ->select(
                't.id',
                'i.codigo',
                'i.descripcion',
                't.multa',
                't.observacion'
            )
            ->where('t.acta_id', $expediente->acta_id)
            ->orderBy('t.id')
            ->get();

        $multaTotal = (float) DB::table('tipificaciones')
            ->where('acta_id', $expediente->acta_id)
            ->sum('multa');

        // Tickets (boletas) linked to this acta
        $boletas = DB::table('boletas as b')
            ->where('b.acta_id', $expediente->acta_id)
            ->orderBy('b.created_at')
            ->get();

        return view('expedientes.show', [
            'expediente'   => $expediente,
            'tipificaciones' => $tipificaciones,
            'boletas'      => $boletas,
            'multaTotal'   => $multaTotal,
        ]);
    }
}
