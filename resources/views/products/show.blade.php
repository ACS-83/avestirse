@extends('layouts.app')
@section('content')
{{-- Detalles de producto --}}
    <div class="mt-5">
        <div class="album bg-light">
            <div class="container">
                <div class="row col-xs col-sm-8 col-md-6 mx-auto">
                    <div class="col">
                                {{-- Si el producto no contiene imágenes, muestra PREVIEW.JPG --}}
                                @if (isset($images) && count($images) == null)
                                    <img src="{{ asset('products_images/preview.jpg') }}" class="bd-placeholder-img card-img-top cov" width="100%" height="225">
                                @else
                                {{-- Caso contrario, realiza un recorrido e incluye el nombre de cada imagen en cada atributo SRC --}}
                                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000">
                                    <div class="carousel-inner">
                                @foreach ($images as $image)
                                <div class="carousel-item @if($loop->first) active @endif">
                                    <img src="/products_images/{{ $image->image }}" class="d-block w-100 cov" alt="..." height="336px">
                                </div>
                                @endforeach
                            </div>
                            
                            {{-- Botones de control de carrusel --}}
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Next</span>
                            </button>
                          </div>
                          @endif
                          
                          {{-- Datos de producto. Muestra stock en función de la cantidad --}}
                            <div class="card-body mt-3">
                                <h3 class="card-text">{{ $product->name }}</h3>
                                <p class="card-text">{{ $product->description }}</p>
                                <p class="card-text">Stock:
                                    @if ($product->stock > 1)
                                        {{ $product->stock }} unidades
                                    @elseif ($product->stock == 1)
                                        1 unidad
                                    @elseif($product->stock < 1)
                                        Agotado
                                    @endif
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        {{-- BOTÓN AÑADIR AL CARRITO. Comprobará que el usuario esté registrado
                                            y su rol no sea otro que el de USER --}}
                                        @if (!Auth::check() || (Auth::check() && Auth::user()->role == 0))
                                            <form action="{{ route('products.addtocart') }}" method="post">
                                                @csrf
                                                <div class="form-outline">
                                                    <input pattern="[0-9]*" min="0" max="{{ $product->stock }}"
                                                        style="width: 12ch;" class="btn-light" name="quantity"
                                                        @if (Session::get('productsCart') !== null && isset(Session::get('productsCart')[$product->id]['quantity'])) value="{{ Session::get('productsCart')[$product->id]['quantity'] }}"
                                                @else
                                                value="1" @endif
                                                        type="number" class="form-control" />
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                                    <input type="submit" value="Añadir">
                                                </div>
                                            </form>
                                        @endif
                                        
                                        {{-- Muestra botón EDITAR y BORRAR si el usuario es administrador --}}
                                        @if (Auth::check() && Auth::user()->role == 1)
                                            <a href=" {{ route('products.edit', $product) }}"
                                                class="btn btn-success">Editar</a>
                                            <a href="products/{{ $product->id }}" class="btn btn-danger"
                                                onclick="
                                            var confir = confirm('¿Seguro que quiere eliminar este producto?');
                                            event.preventDefault();
                                            if(confir){
                                            document.getElementById('delete-form').submit();
                                            }">Borrar</a>
                                            <form method="POST" id="delete-form"
                                                action="{{ route('products.destroy', [$product->id]) }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="DELETE">
                                        @endif
                                        </form>
                                        {{-- Regreso. Se activa mediante JS --}}
                                        <input type="button" value="Volver" onclick="back()">
                                    </div>
                                    <small class="text-muted">{{ $product->price }}€</small>
                                </div>
                            </div>
                        </div>
                        {{-- Función de botón de regreso a página anterior --}}
                        <script>
                            function back() {
                                window.history.back()
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    @stop
