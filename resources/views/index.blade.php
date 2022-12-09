
@extends('layouts.app')
@section('content')
{{-- LANDING PAGE --}}
{{-- ESTILOS --}}
<style>
  /* cabecera */
  header {
  position: relative;
  background-color: black;
  height: 75vh;
  min-height: 25rem;
  width: 100%;
  overflow: hidden;
}
/* video */
header video {
  position: absolute;
  top: 50%;
  left: 50%;
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
  z-index: 0;
  -ms-transform: translateX(-50%) translateY(-50%);
  -moz-transform: translateX(-50%) translateY(-50%);
  -webkit-transform: translateX(-50%) translateY(-50%);
  transform: translateX(-50%) translateY(-50%);
}
/* contenedor de video */
header .container {
  position: relative;
  z-index: 2;
}
/* superposición de cabecera */
header .overlay {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-color: black;
  opacity: 0.5;
  z-index: 1;
}

/* Media Query para dispositivos con punteros gruesos y sin funcionalidad de desplazamiento.
Esto usará una imagen de reserva en lugar de un video para dispositivos
que comúnmente no admiten el elemento de video HTML5 */

@media (pointer: coarse) and (hover: none) {
  header video {
    display: none;
  }
}
</style>
<header>

  <!-- Intencionalmente en blanco. Crea la superposición negra transparente
    sobre el video que puede modificar en el CSS -->
  <div class="overlay"></div>

  <!-- El vídeo -->
  <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
    <source src="{{ 'gfx/seq.mp4' }}" type="video/mp4">
  </video>

  <!-- Contenido de cabecera -->
  <div class="container h-100">
    <div class="d-flex h-100 text-center align-items-center">
      <div class="w-100 text-white">
        <h1 class="display-3">¡Tenemos camisetazas!</h1>
        <a href="{{ route('products.index') }}"><button type="button" class="btn btn-warning">¿Ah sí? A ver...</button></a>
      </div>
    </div>
  </div>
</header>
{{-- Footer --}}
<footer class="footer mt-auto py-3 bg-light fixed-bottom">
  <div class="container text-center">
    <span class="text-muted">Proyecto final DAW - Alberto Cortés Sánchez - Dic. 2022</span>
  </div>
</footer>
@stop