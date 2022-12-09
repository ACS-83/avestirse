@extends('layouts.app')
@section('content')
{{-- Carrusel --}}
    <div class="mt-5">
        <div class="container">
            <div class="row col-xs col-sm-8 col-md-6 mx-auto">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000">
                    <div class="carousel-inner">
                        {{-- Mostrar imágenes (si el producto contiene) --}}
                            @if (isset($productImages) < 1)
                                <img src="{{ asset('preview.jpg') }}" class="bd-placeholder-img card-img-top cov" width="100%" height="225">
                                @else
                                @foreach ($productImages as $productImage)
                                <div class="carousel-item @if($loop->first) active @endif">
                                    <img src="/products_images/{{ $productImage->image }}" class="d-block w-100 cov" alt="..." height="336px">
                                </div>
                                @endforeach
                            </div>
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
                {{-- Formulario --}}
                <form action="{{ route('products.update', $product) }}" method="post">
                    @method('put')
                    @csrf
                    <div class="form-group row mt-5">
                        <label for="name" class="col-3 col-form-label">Nombre producto</label>
                        <div class="col-9">
                            <input id="name" name="name" type="text"
                                 {{-- class="form-control"> --}}
                                 class="form-control mb-4" value="{{@old('name', $product->name)}}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-3 col-form-label">Descripción</label>
                        <div class="col-9">
                            <textarea id="description" name="description" cols="40" rows="5"  class="form-control mb-4">{{@old('description', $product->description)}}</textarea>
                            @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price" class="col-3 col-form-label">Precio</label>
                        <div class="col-9">
                            <input id="price" name="price" type="number" step=".01" min="0" value="{{@old('price', $product->price)}}" pattern="^\d*(\.\d{0,2})?$"
                                class="form-control mb-4">
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                    </div>
                    <div class="form-group row">
                        <label for="stock" class="col-3 col-form-label">Stock</label>
                        <div class="col-9">
                            <input id="stock" name="stock" type="number"
                                class="form-control mb-4" value="{{@old('stock', $product->stock)}}">
                                @error('stock')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    {{-- Botón de envío --}}
                    <div class="form-group row">
                        <div class="offset-3 col-9">
                            <button name="submit" type="submit" class="btn btn-primary">Enviar</button>
                            <script>
                                function back() {
                                    window.history.back()
                                }
                            </script>
                            {{-- Botón de regreso a página anterior --}}
                            <input type="button" value="Volver" onclick="back()">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop