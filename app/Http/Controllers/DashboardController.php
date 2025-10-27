<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\Boleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $r)
    {
        $day   = $r->get('day', now()->toDateString());
        $start = now()->parse($day)->startOfDay();
        $end   = now()->parse($day)->endOfDay();

        // Actas
        $totalActas = Acta::whereBetween('created_at', [$start,$end])->count();
        $actasEvid  = Acta::whereBetween('created_at', [$start,$end])->has('evidencias')->count();
        $pctEvid    = $totalActas ? round($actasEvid * 100 / $totalActas, 1) : 0;
        $actasHoy   = Acta::whereDate('created_at', $day)->count();

        // Boletas / recaudación
        $recaudacion  = (float) Boleta::whereBetween('created_at', [$start,$end])->sum('monto');
        $notifTot     = Boleta::whereBetween('created_at', [$start,$end])->whereNotNull('notified_at')->count();
        $notifFast    = Boleta::whereBetween('created_at', [$start,$end])
                          ->whereNotNull('notified_at')
                          ->whereRaw('TIMESTAMPDIFF(MINUTE, created_at, notified_at) < 5')->count();
        $pctNotifFast = $notifTot ? round($notifFast*100/$notifTot,1) : 0;

        // Tiempo promedio (min) Acta -> Boleta
        $avgMin = DB::table('boletas')
            ->join('actas','actas.id','=','boletas.acta_id')
            ->whereBetween('boletas.created_at',[$start,$end])
            ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, actas.created_at, boletas.created_at)'));
        $avgMin = $avgMin ? round($avgMin, 0) : 0;

        /* ===== Reincidencia =====
           % de administrados con 2+ actas en el día.
           IMPORTANTE: hacemos select agregado para no romper con ONLY_FULL_GROUP_BY.
        */
        $totalAdmins = Acta::whereBetween('created_at',[$start,$end])
            ->whereNotNull('administrado_id')
            ->distinct('administrado_id')
            ->count('administrado_id');

        $reincAdmins = Acta::whereBetween('created_at',[$start,$end])
            ->whereNotNull('administrado_id')
            ->select('administrado_id', DB::raw('COUNT(*) as c'))
            ->groupBy('administrado_id')
            ->having('c', '>', 1)
            ->get()            // obtenemos filas agrupadas
            ->count();         // y contamos cuántos administrados tienen >1

        $pctReinc = $totalAdmins ? round($reincAdmins * 100 / $totalAdmins, 1) : 0;

        return view('dashboard.index', compact(
            'day','avgMin','pctEvid','pctNotifFast','pctReinc','recaudacion','actasHoy'
        ));
    }
}
