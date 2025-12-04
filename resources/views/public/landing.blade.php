@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Hero --}}
    <div class="row align-items-center mb-5">
        <div class="col-md-7 mb-4 mb-md-0">
            <h1 class="h3 mb-3">Municipalidad de Jesús María · Fiscalización</h1>

            <p class="lead mb-3">
                Sistema interno para registrar actas de fiscalización, boletas y expedientes
                del Procedimiento Administrativo Sancionador (PAS) de la Municipalidad de Jesús María.
            </p>

            <p class="mb-4">
                Si eres inspector municipal, jefe de área o personal administrativo autorizado,
                ingresa con tu cuenta institucional para acceder al panel interno.
            </p>

            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-box-arrow-in-right me-1"></i>
                Ingresar al sistema
            </a>
        </div>

        <div class="col-md-5 text-center">
            {{-- Imagen principal de fiscalización urbana --}}
            <img
                src="{{ asset('img/landing/inspeccion-municipal.jpg') }}"
                alt="Personal de fiscalización municipal realizando una inspección"
                class="img-fluid rounded shadow-sm"
            >
        </div>
    </div>

    {{-- Información básica para ciudadanos --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-2">¿Qué es un Acta de Fiscalización?</h2>
                    <p class="mb-0">
                        Es el documento en el que el inspector municipal registra los hechos
                        verificados durante una visita de inspección, incluyendo posibles
                        infracciones, datos del administrado y las evidencias recogidas.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-2">¿Quién usa esta plataforma?</h2>
                    <p class="mb-0">
                        Solo el personal municipal autorizado (inspectores, jefes y
                        administradores del sistema) accede a esta herramienta interna.
                        Los ciudadanos son informados sobre sus expedientes y multas
                        a través de los canales oficiales de la municipalidad.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-2">Protección de datos</h2>
                    <p class="mb-0">
                        La información registrada en este sistema se gestiona de acuerdo
                        con las políticas municipales y la normativa vigente sobre
                        protección de datos personales y transparencia.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bloque de ayuda / contacto --}}
    <div class="card shadow-sm">
        <div class="card-body d-md-flex justify-content-between align-items-center">
            <div class="me-md-4">
                <h2 class="h5 mb-2">¿Necesitas ayuda?</h2>
                <p class="mb-0">
                    Para consultas sobre multas, expedientes o procedimientos de fiscalización,
                    comunícate con la Oficina de Atención al Ciudadano de la Municipalidad
                    de Jesús María.
                </p>
            </div>
            <div class="mt-3 mt-md-0 text-md-end">
                <div class="mb-2">
                    <i class="bi bi-telephone me-1"></i>
                    Central telefónica: (01) 000-0000
                </div>
                <a href="mailto:mesadepartes@munijesusmaria.gob.pe" class="btn btn-outline-secondary">
                    <i class="bi bi-envelope me-1"></i>
                    Contactar soporte
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
