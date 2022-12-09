@extends('layouts.app')
@section('content')
{{-- Titular --}}
    <div class="mt-5">
        <div class="container">
            <div class="row col-xs col-sm-8 col-md-6 mx-auto">
                {{-- Formulario y recogida de datos en cada campo. Los formularios con imágenes
                    deben tener un atributo 'enctype="multipart/form-data"' --}}
                <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-3 col-form-label">Nombre producto</label>
                        <div class="col-9">
                            <input id="name" name="name" type="text"
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
                    {{-- Imágenes a subir en array --}}
                    <div class="form-group row">
                        <label for="files" class="col-3 col-form-label">Imágenes</label>
                        <div class="col-9">
                        <input type="file" id="img" name="images[]" accept="image/*" multiple>
                            @error('images')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Botón de envío de formulario --}}
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