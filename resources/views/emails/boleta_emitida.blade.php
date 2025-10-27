<p>Estimado/a,</p>
<p>Adjuntamos la Boleta de Infracción {{ $boleta->serie }}-{{ str_pad($boleta->numero,6,'0',STR_PAD_LEFT) }}.</p>
<p>Puede verificarla aquí: <a href="{{ route('boletas.show',$boleta) }}">{{ route('boletas.show',$boleta) }}</a></p>
<p>Atentamente,<br>{{ config('app.name') }}</p>
