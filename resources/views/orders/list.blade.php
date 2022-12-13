@extends('layouts.app')
@section('content')
{{-- Listado de pedidos --}}
<main>
        <div class="px-4 py-5 my-5 text-center">
          <h1 class="display-8 fw-bold">Listado pedidos</h1>
        </div>
        @if (isset($message['success']))
        <div class="text-center">
            <p class="my-3 bg-success text-white col-lg-2 col-md-2 col-sm-2 mx-auto p-2">{{ $message['success'] }}</p>
        </div>
        @endif
</main>
{{-- Muestra mensaje de aviso en pantalla cuando la variable contenga algo --}}

    {{-- Muestra pedidos --}}
    <div class="list-group col-lg-8 mx-auto mt-5">
        {{-- Si hay pedidos... --}}
        @if(isset($orders) && count($orders) != null)
        {{-- Los recorre y los representa en pantalla --}}
        @foreach ($orders as $order)
        <a href="{{ route('orders.show', $order->id) }}" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
            <div class="d-flex w-25 justify-content-center">
                @if ($order['sent'] == 0) <p><span class="badge bg-danger rounded-pill text-wrap">PENDIENTE ENVÍO</p> @else <p><span class="badge bg-success rounded-pill">ENVIADO</span></p> @endif
            </div>
            
            <div class="d-flex gap-2 w-100 justify-content-between">
                <div>
                    <h6 class="mb-0">
                        <b>Nº pedido: </b>{{ $order->id }}<br>
                        <div class="w-100 ml-3">
                        {{-- Representación de productos de pedido en pantalla --}}
                        @php
                        $productsOrdered = $order->productsOrdered;
                        foreach ($productsOrdered as $key => $value) {
                            $arrayValues = explode(', ', $value);
                        }
                        echo "<b>Productos:</b> ".$arrayValues[1]."...<br>";
                        echo "<b>Pagado:</b> ".$arrayValues[3]."€";
                        @endphp
                        </div>
                    </h6>
                    <p class="mb-0 opacity-75">Cliente: {{ $order['mailuser'] }}</p>
                </div>
                <small class="opacity-50 text-wrap">Solicitado {{ $order['updated_at']->diffForHumans() }}</small>
            </div>
        </a>
        @endforeach
        {{-- Si no hay pedidos... Muestra mensaje en pantalla --}}
        @else
        <div class="d-flex justify-content-center">
            <h5 class="text-center fw-bold bg-warning col-2">¡No hay pedidos!</h5>
        </div>
        @endif
    </div>
@stop