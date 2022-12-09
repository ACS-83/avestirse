@extends('layouts.app')
{{-- Inclusión de ALPINEJS --}}
<script defer src="https://unpkg.com/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@section('content')
{{-- Muestra los productos desde el principio cada vez que se carga la página --}}
<script>
if (history.scrollRestoration) {
    history.scrollRestoration = 'manual';
} else {
    window.onbeforeunload = function () {
        window.scrollTo(0, 0);
    }
}
</script>
{{-- Directiva necesaria para Livewire --}}
@livewireScripts

<div class="px-5 py-5 my-5 text-center">
    <div class="py-2 my-5 text-center">
        <h1 class="display-5 fw-bold">Listado de productos</h1>
    </div>
    {{-- Muestra botón CREAR si eres ADMIN --}}
    @if(Auth::check() && Auth::user()->role == 1)
        <a href="{{ route('products.create') }}">
        <button type="button" class="btn btn-primary">Crear</button></a>
    @endif
    {{-- Muestra mensaje en pantalla si algo ha ocurrido --}}
    @if(session('success'))
        <p class="mt-5 bg-success text-white col-lg-2 col-md-2 col-sm-2 mx-auto p-2">{{ session('success') }}</p>
    @endif
</div>
{{-- Componente de Livewire. Muestra de productos (livewire/product-list) --}}
<livewire:product-list />