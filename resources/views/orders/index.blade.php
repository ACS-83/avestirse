@extends('layouts.app')
@section('content')
{{-- Formulario de compra --}}
    <div class="container">
        <main>
            <div class="py-5 text-center">
                <h2>Formulario de compra</h2>
            </div>
            {{-- Muestra mensajes de validaciones --}}
            @foreach ($errors->all() as $error)
                <p class="text-danger"><strong>{{ $error }}</strong></p>
            @endforeach
                <div class="row g-5">
                    <div class="col-md-5 col-lg-4 order-md-last">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-primary">Productos</span>
                            {{-- Muestra productos en función de si hay o no contenidos en el SESSION/PRODUCTSCART --}}
                            @if (empty(session('productsCart')))
                                <span class="badge bg-primary rounded-pill">0</span>
                            @else
                                <span class="badge bg-primary rounded-pill">{{ count(session('productsCart')) }}</span>
                            @endif
                        </h4>
                        <ul class="list-group mb-3">
                            {{-- Mensaje de aviso para añadir productos que puede ser clicado --}}
                            @if (empty(session('productsCart')))
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <a href="{{ route('products.index') }}" class="text-decoration-none" title="Click para añadir"><h6 class="my-0 text-danger"><strong>¡Añade productos!</strong></h6></a>
                            @else
                            {{-- Inicialización de variable acumuladora --}}
                            @php $acum = 0 @endphp
                            {{-- Muestra productos en función de si hay o no contenidos en el carrito --}}
                                @foreach ($products as $product)
                                        @if (array_key_exists($product->id, Session::get('productsCart')))
                                        <li class="list-group-item d-flex justify-content-between lh-sm" id="productAdded">
                                            <div>
                                                {{-- Datos de producto --}}
                                                <a href="{{ route('products.show', $product->id) }}"
                                                    class="text-decoration-none">
                                                    @if (Session::get('productsCart')[$product->id]['quantity'] > 1)
                                                        <h6 class="my-0">{{ Str::limit($product->name, 20) }} <b> X </b>
                                                            {{ Session::get('productsCart')[$product->id]['quantity'] }}
                                                        </h6>
                                                    @else
                                                        <h6 class="my-0">{{ Str::limit($product->name, 20) }}</h6>
                                                    @endif
                                                    
                                                    <small
                                                        class="text-muted">{{ Str::limit($product->description, 20) }}</small>
                                                        @php
                                                            if(Session::get('productsCart')[$product->id]['quantity'] > 1) {
                                                                $productPrice = number_format($product->price * Session::get('productsCart')[$product->id]['quantity'], 2, '.', ',');
                                                            } else {
                                                                $productPrice = Session::get('productsCart')[$product->id]['quantity'];
                                                            }
                                                        @endphp
                                                </a>
                                            </div>
                                            {{-- Cantidad --}}
                                            <div class="d-flex justify-content-end">
                                                @php $productQuantity = Session::get('productsCart')[$product->id]['quantity']; @endphp
                                                @if (Session::get('productsCart')[$product->id]['quantity'] > 1)
                                                    <span
                                                        {{ $actualQuantity = Session::get('productsCart')[$product->id]['quantity'] }}
                                                        class="text-muted">{{ number_format($product->price * $actualQuantity, 2, '.', ',') }}
                                                        €</span><a
                                                        href="{{ route('products.removefromcart', $product->id) }}"><button
                                                            class="btn btn-sm btn-danger ms-1"><i
                                                                class="fa-solid fa-trash"></i></button></a>
                                                    @php
                                                        $acum += $product->price * Session::get('productsCart')[$product->id]['quantity'];
                                                    @endphp
                                                    <input type="text" name="dataProduct[]" value="{{ $product->id }}, {{ $product->name }}, {{ $productQuantity }}, {{ $productPrice }}" form="formOrder" hidden>
                                                @else
                                                    <span class="text-muted">{{ $product->price }} €</span><a
                                                        href="{{ route('products.removefromcart', $product->id) }}"><button
                                                            class="btn btn-sm btn-danger ms-1"><i
                                                                class="fa-solid fa-trash"></i></button></a>
                                                    <input type="text" name="dataProduct[]" value="{{ $product->id }}, {{ $product->name }}, {{ $productQuantity }}, {{ $product->price }}" form="formOrder" hidden>
                                                    {{-- Con cada iteración se va acumulando la cantidad en función de cómo haya llegado la lectura de código hasta aquí --}}
                                                    @php
                                                        $acum += $product->price;
                                                    @endphp
                                                @endif
                                            </div>
                                    </li>
                                @endif
                            @endforeach
                            {{-- Input auxiliar para recogida de datos desde el formulario --}}
                            <input type="text" name="orderDataTotalPrice" value="{{ number_format($acum, 2, '.', ',') }}" form="formOrder" hidden>
                            @endif
                            {{-- Muestra el precio total en función de si hay o no productos en carrito --}}
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total (EUR)</span>
                                @if (empty(session('productsCart')))
                                    <strong>0.00 €</strong>
                                @else
                                    <strong>{{ number_format($acum, 2, '.', ',') }} €</strong>
                                @endif
                            </li>
                        </ul>
                    </div>
                    {{-- Recogida de datos de compra --}}
                    <div class="col-md-7 col-lg-8">
                        <h4 class="mb-3">Datos de envío</h4>
                        <form action="{{ route('orders.store') }}" id="formOrder" method="post">
                          @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="firstName" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="firstName" name="name" placeholder=""
                                    value="" required>
                            </div>

                            <div class="col-sm-6">
                                <label for="lastName" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="lastName" name="surname" placeholder=""
                                    value="" required>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Dirección</label>
                                <input type="text" class="form-control" name="address" id="address"
                                    placeholder="Dirección" required>
                            </div>

                            <div class="col-md-5">
                                <label for="country" class="form-label">País</label>
                                <select class="form-select" id="country" name="country" required>
                                    <option selected disabled>Escoge...</option>
                                    <option value="España">España</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Andorra">Andorra</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="zip" class="form-label">Código postal</label>
                                <input type="text" class="form-control" name="zip" id="zip" placeholder=""
                                    required>
                            </div>
                        </div>
                        <hr class="my-4">
                        <h4 class="mb-3">Método de pago</h4>
                        <div class="my-3">
                            <div class="form-check">
                                <input id="credit" name="paymentMethod" type="radio" class="form-check-input" value="Crédito"
                                    required>
                                <label class="form-check-label" for="credit">Tarjeta de crédito</label>
                            </div>
                            <div class="form-check">
                                <input id="debit" name="paymentMethod" type="radio" value="Débito" class="form-check-input"
                                    required>
                                <label class="form-check-label" for="debit">Tarjeta de débito</label>
                            </div>
                        </div>

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label for="cc-name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="cc-name" name="fullCardName"
                                    placeholder="" required>
                                <small class="text-muted">Nombre completo incluído en la tarjeta</small>
                            </div>

                            <div class="col-md-6">
                                <label for="cc-number" class="form-label">Número tarjeta crédito</label>
                                <input type="text" name="cardNumber" class="form-control" id="cc-number"
                                    placeholder="" required>
                            </div>

                            <div class="col-md-3">
                                <label for="cc-expiration" class="form-label">Fecha expiración</label>
                                <input type="text" class="form-control" name="cardExpiration" id="cc-expiration"
                                    placeholder="" required>
                            </div>

                            <div class="col-md-3">
                                <label for="cc-cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" name="cvv" id="cc-cvv" placeholder=""
                                    required>
                            </div>
                        </div>
                        <hr class="my-4">
                        {{-- Habilita el botón de COMPRAR en función de si hay o no productos en carrito --}}
                        @if (session('productsCart') === null)
                            <button class="w-100 btn btn-primary btn-lg" id="send" type="submit">COMPRAR</button>
                        @else
                            <button class="w-100 btn btn-primary btn-lg" id="send" type="submit" disabled>COMPRAR</button>
                        @endif
            </form>
            {{-- Simple validación por JS de datos de formulario. Preparado
                para activar el botón de envío cuando cada campo contenga al menos
                un mínimo de datos relleno. Uso de SESSIONSTORAGE para mantener
                los datos almacenados durante la sesión de compra del usuario. --}}
            <script type="text/javascript" defer>
            // Comprobación de validación al terminar de cargar la página
                window.addEventListener('DOMContentLoaded', (event) => {
                    if(document.querySelector("#formOrder").checkValidity() == false) {
                        document.querySelector("#send").disabled = true
                    }
                    // Manejo de Sessionstorage. Recupera los datos desde el mismo en función de
                    // si hay o no algo
                    if (sessionStorage.length > 1) {
                        document.querySelector("#firstName").value = sessionStorage.getItem('firstName');
                        document.querySelector("#lastName").value = sessionStorage.getItem('lastName');
                        document.querySelector("#address").value = sessionStorage.getItem('address');
                        document.querySelector("#country").value = sessionStorage.getItem('country');
                        document.querySelector("#zip").value = sessionStorage.getItem('zip');
                        if(sessionStorage.getItem('paymentMethod') === 'credit') {
                            document.querySelectorAll('input[name="paymentMethod"]')[0].checked = true
                        }else {
                            document.querySelectorAll('input[name="paymentMethod"]')[1].checked = true
                        }
                        document.querySelector('input[name="paymentMethod"]:checked').id = sessionStorage.getItem('paymentMethod');
                        document.querySelector("#cc-name").value = sessionStorage.getItem('cc-name'); 
                        document.querySelector("#cc-number").value = sessionStorage.getItem('cc-number');
                        document.querySelector("#cc-expiration").value = sessionStorage.getItem('cc-expiration');
                        document.querySelector("#cc-cvv").value = sessionStorage.getItem('cc-cvv');
                        if(document.querySelector("#formOrder").checkValidity() && document.querySelectorAll("#productAdded").length > 0) {
                            document.querySelector("#send").disabled = false
                        } else {
                            document.querySelector("#send").disabled = true
                        }
                    }
                });
                /* Añade eventos de escucha a todo el formulario de tal forma que detecte cualquier cambio
                producido por el usuario. Si se produce dicho cambio, almacena los datos de cada
                campo en el Sessionstorage */
                document.querySelector("#formOrder").addEventListener("input", function () {
                    if(document.querySelector("#formOrder").checkValidity() && document.querySelectorAll("#productAdded").length > 0) {
                        document.querySelector("#send").disabled = false
                    } else {
                        document.querySelector("#send").disabled = true
                    }
                    sessionStorage.setItem('firstName', document.querySelector("#firstName").value);
                    sessionStorage.setItem('lastName', document.querySelector("#lastName").value);
                    sessionStorage.setItem('address', document.querySelector("#address").value);
                    if(document.querySelector("#country").value !== "Escoge...") {
                        sessionStorage.setItem('country', document.querySelector("#country").value);
                    }
                    sessionStorage.setItem('zip', document.querySelector("#zip").value);
                    sessionStorage.setItem('paymentMethod', document.querySelector('input[name="paymentMethod"]:checked').id);
                    sessionStorage.setItem('cc-name', document.querySelector("#cc-name").value);
                    sessionStorage.setItem('cc-number', document.querySelector("#cc-number").value);
                    sessionStorage.setItem('cc-expiration', document.querySelector("#cc-expiration").value);
                    sessionStorage.setItem('cc-cvv', document.querySelector("#cc-cvv").value);
                })
            </script>
    </div>
    </div>
    </main>
@stop
