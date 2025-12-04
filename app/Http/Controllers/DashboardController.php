<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Main dashboard for municipal KPIs.
     */
    public function index(Request $request)
    {
        // Date filters (default: last 30 days)
        $today = now()->toDateString();
        $from  = $request->input('from', now()->subDays(30)->toDateString());
        $to    = $request->input('to', $today);

        /* --------------------------------------------------------
         * ACTAS
         * ------------------------------------------------------ */

        // Total actas (all history)
        $totalActas = DB::table('actas')->count();

        // Actas in selected period
        $actasPeriodo = DB::table('actas')
            ->whereBetween('fecha', [$from, $to])
            ->count();

        // Actas created today (by fecha)
        $actasHoy = DB::table('actas')
            ->whereDate('fecha', $today)
            ->count();

        // Actas by status (borrador / emitida / notificada / anulada)
        $actasPorEstado = DB::table('actas')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado'); // ['emitida' => X, 'borrador' => Y, ...]

        /* --------------------------------------------------------
         * BOLETAS / REVENUE
         * ------------------------------------------------------ */

        // Only consider "valid" fines (no anuladas) for KPIs
        $boletasVigentes = DB::table('boletas')
            ->whereIn('estado', ['emitida', 'notificada']);

        // Total number of valid tickets (all history)
        $totalBoletas = (clone $boletasVigentes)->count();

        // Total amount of fines (emitida + notificada, no anulada)
        $recaudacionTotal = (clone $boletasVigentes)->sum('monto');

        // Tickets and amount in selected period (using acta.fecha)
        $boletasPeriodoQuery = DB::table('boletas')
            ->join('actas', 'boletas.acta_id', '=', 'actas.id')
            ->whereIn('boletas.estado', ['emitida', 'notificada'])
            ->whereBetween('actas.fecha', [$from, $to]);

        $boletasPeriodo = (clone $boletasPeriodoQuery)->count();
        $recaudacionPeriodo = (clone $boletasPeriodoQuery)->sum('boletas.monto');

        // Tickets by status (emitida / notificada / anulada)
        $boletasPorEstado = DB::table('boletas')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        /* --------------------------------------------------------
         * TOP DAYS & LOCATIONS
         * ------------------------------------------------------ */

        // Days with more actas (top 7, all history)
        $diasTop = DB::table('actas')
            ->select('fecha', DB::raw('COUNT(*) as total'))
            ->groupBy('fecha')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        // Hot spots: locations with more actas in the selected period
        $lugaresTop = DB::table('actas')
            ->select('lugar', DB::raw('COUNT(*) as total'))
            ->whereNotNull('lugar')
            ->where('lugar', '!=', '')
            ->whereBetween('fecha', [$from, $to])
            ->groupBy('lugar')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        /* --------------------------------------------------------
         * MONTHLY AMOUNTS (last 6 months)
         * ------------------------------------------------------ */

        $desdeMes = now()->subMonths(5)->startOfMonth()->toDateString(); // 6 months including current

        $porMes = DB::table('boletas')
            ->join('actas', 'boletas.acta_id', '=', 'actas.id')
            ->whereIn('boletas.estado', ['emitida', 'notificada'])
            ->where('actas.fecha', '>=', $desdeMes)
            ->selectRaw("
                DATE_FORMAT(actas.fecha, '%Y-%m') as periodo,
                COUNT(DISTINCT actas.id)        as actas,
                COUNT(boletas.id)               as boletas,
                SUM(boletas.monto)              as monto
            ")
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();

        /* --------------------------------------------------------
         * TOP INFRACCIONES (by tipificaciones)
         * ------------------------------------------------------ */

        $topInfracciones = DB::table('tipificaciones')
            ->join('infracciones', 'tipificaciones.infraccion_id', '=', 'infracciones.id')
            ->join('actas', 'tipificaciones.acta_id', '=', 'actas.id')
            ->whereBetween('actas.fecha', [$from, $to])
            ->select(
                'infracciones.codigo',
                'infracciones.descripcion',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(tipificaciones.multa) as monto')
            )
            ->groupBy('infracciones.codigo', 'infracciones.descripcion')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        /* --------------------------------------------------------
         * EXPEDIENTES
         * ------------------------------------------------------ */

        $expedientesPorEstado = DB::table('expedientes')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado'); // ['abierto' => X, 'concluido' => Y, ...]

        /* --------------------------------------------------------
         * VIEW
         * ------------------------------------------------------ */

        return view('dashboard', [
            'today'                => $today,
            'from'                 => $from,
            'to'                   => $to,

            'totalActas'           => $totalActas,
            'actasPeriodo'         => $actasPeriodo,
            'actasHoy'             => $actasHoy,
            'actasPorEstado'       => $actasPorEstado,

            'totalBoletas'         => $totalBoletas,
            'boletasPeriodo'       => $boletasPeriodo,
            'recaudacionTotal'     => $recaudacionTotal,
            'recaudacionPeriodo'   => $recaudacionPeriodo,
            'boletasPorEstado'     => $boletasPorEstado,

            'diasTop'              => $diasTop,
            'lugaresTop'           => $lugaresTop,
            'porMes'               => $porMes,

            'topInfracciones'      => $topInfracciones,
            'expedientesPorEstado' => $expedientesPorEstado,
        ]);
    }
}
