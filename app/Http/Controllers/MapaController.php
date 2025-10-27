<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\User;
use Illuminate\Http\Request;

class MapaController extends Controller
{
    public function index(Request $r)
    {
        // Filtros
        $inspector = $r->input('inspector'); // id de usuario
        $estado    = $r->input('estado');    // borrador | emitida | anulada
        $fecha     = $r->input('fecha');     // opcional

        $q = Acta::query()
            ->whereNotNull('lat')
            ->whereNotNull('lng');

        if ($inspector) {
            $q->where('inspector_id', $inspector);
        }
        if ($estado) {
            $q->where('estado', $estado);
        }
        if (!empty($fecha)) {
            // si envías una sola fecha la usamos como "en ese día"
            $q->whereDate('fecha', $fecha);
        }

        // Traemos datos necesarios (sin 'giro')
        $markers = $q->with([
                'inspector:id,name',
                'tipificaciones.infraccion:id,codigo,descripcion'
            ])
            ->get(['id','numero','fecha','hora','lugar','lat','lng','inspector_id','estado']);

        $inspectores = User::orderBy('name')->get(['id','name']);
        $estados     = ['borrador','emitida','anulada'];

        return view('mapa.index', compact('markers','inspectores','estados'));
    }
}
