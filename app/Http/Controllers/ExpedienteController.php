<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpedienteController extends Controller
{
    public function index(Request $r)
    {
        $q = trim($r->get('q',''));

        // Lista de "expedientes" simple basada en actas emitidas
        $actas = Acta::with(['inspector:id,name','tipificaciones.infraccion:id,codigo,descripcion'])
            ->when($q, function($query) use ($q) {
                $query->where(function($s) use ($q){
                    $s->where('numero','like',"%$q%")
                      ->orWhere('lugar','like',"%$q%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('expedientes.index', compact('actas','q'));
    }
}
