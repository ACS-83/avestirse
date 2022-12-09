@extends('layouts.app')
@section('content')
<main>
  {{-- Detalles de pedido --}}
  <div class="px-4 py-5 my-5 text-center">
    <h1 class="display-8 fw-bold">Detalle pedido</h1>
    {{-- Botón que vuelve a listado de pedidos --}}
  <form id="ordersList" action="{{ route('orders.list') }}" method="POST">
      @csrf
      <input class="btn btn-secondary" type="submit" value="Volver a pedidos">
  </form>  
  </div>
</main>
<div class="container-fluid">
  {{-- Preparación de listado de pedidos procedente del controlador --}}
  @php
  $products = $order['productsOrdered'];
  $decode = explode("],[", $products);
  $decode = str_replace('["', '', $decode);
  $decode = str_replace('"]', '', $decode);
  // Recorrido de pedidos para convertir a arrays
  foreach ($decode as $key => $value) {
    $arrayProducts[$key] = explode(", ", $value);
  }
  @endphp
    <div class="container">
      <!-- Contenido principal -->
      <div class="row">
        <div class="col-lg-8 mx-auto">
        {{-- SWITCH para actualizar el estado del envío de producto. Solo visible
          para el usuario ADMIN. Se muestra en pantalla en función del estado en que
          esté desde la BDD --}}
        @if(Auth::user()->role == 1)
          @if ($order->sent == 0)
              <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" form="orderSent">
              <label>Enviar</label>
              <form id="orderSent" action="{{ route('orders.sent', $order->id) }}" method="POST" class="d-none">
                  @csrf
                  <button type="submit" id="submitButton" hidden></button>
              </form>  
            </div>
          @else
            <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked disabled>
            <label class="form-check-label" for="flexSwitchCheckDefault">Enviado</label>
          </div>
          @endif
          @endif
          <!-- Detalles de pedido -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="mb-3 d-flex justify-content-between">
                <div>
                  <span class="me-3 text"><b>Fecha ped: </b>{{ $order['updated_at']->format('d-m-y') }}</span>
                  <span class="me-3"><b>N. pedido: </b>{{ $order['id'] }}</span>
                  <span class="me-3"><b>Tarjeta: </b>...{{ substr($order['cardNumber'], -4) }}</span>
                </div>
              </div>
              <table class="table table-borderless">
                <tbody>
                  {{-- Productos a mostrar en el pedido --}}
                  @foreach ($arrayProducts as $product)
                  <tr>
                    <td>
                      <div class="d-flex mb-2">
                        <div class="flex-lg-grow-1 ms-3">
                          <h6 class="small mb-0"><a href="{{ route('products.show', $product[0]) }}" class="text-reset">{{ $product[1] }}</a></h6>
                        </div>
                      </div>
                    </td>
                    <td>X {{ $product[2] }}</td>
                    <td class="text-end">{{ $product[3] }}€</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr class="fw-bold">
                    <td colspan="2">TOTAL</td>
                    <td class="text-end">{{ $order['orderTotalPrice'] }}€</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <!-- Pago -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6 col-sm-6">
                  <h3 class="h6"><strong>Método de pago</strong></h3>
                  <p>{{ $order->paymentMethod }}, (...{{ substr($order['cardNumber'], -4) }}) <br>
                  Total: 169,98€ <span class="badge bg-success rounded-pill">PAGADO</span></p>
                </div>
                <div class="col-lg-6 col-sm-6">
                  <h3 class="h6"><strong>Datos de envío</strong>
                    @if ($order->sent == 0)
                    <span class="badge bg-danger rounded-pill">PENDIENTE</span>
                    @else
                    <span class="badge bg-success rounded-pill">ENVIADO</span>
                    @endif
                  </h3>
                  <address>
                    {{ $order->name  }} {{ $order->surname }}<br>
                    {{ $order->address }}<br>
                    {{ $order->country }}, {{ $order->zip }}<br>
                  </address>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      </div>
      {{-- Activa el envío del formulario de cambio de estado de envío en caso
        de que el administrador haga clic en él --}}
      <script type="text/javascript" defer>
        window.addEventListener('DOMContentLoaded', (event) => {
          document.querySelector(".form-check-input").addEventListener("input", function () {
            if(this.checked) {
              document.querySelector('#submitButton').click()
            }
          });
        });
      </script>
@stop