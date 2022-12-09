{{-- Comprueba si hay o no productos en BDD. Caso de que no, muestra mensaje --}}
@if ($products->isEmpty())
<div class="d-flex justify-content-center">
    <h5 class="text-center fw-bold bg-warning col-2">¡No hay productos!</h5>
</div>
@endif
{{-- Comprueba si SESSION contiene un error procedente desde controladores --}}
@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block text-center">
        <strong>{{ $message }}</strong>
</div>
@endif
{{-- Estructura inicial de muestrario de productos --}}
<div class="album bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-3">
            {{-- Recorrido de variable de productos. Cada iteración contiene la estructura necesaria
            para ser mostrada en pantalla --}}
            @foreach ($products as $product)
                <div class="col-sm">
                    <div class="card h-100">
                        {{-- Si el producto contiene imagen, lo muestra --}}
                        @if(isset($images[$product->id]))
                            <img src="/products_images/{{ $images[$product->id] }}" class="card-img-top cov" width="100%" height="225">
                        {{-- Caso contrario, muestra una imagen por defecto común a todos los productos que no tengan imagen --}}
                        @else
                            <img src="/products_images/preview.jpg" class="card-img-top cov" width="100%" height="225">
                        @endif
                        <div class="card-body d-flex flex-wrap align-content-between">
                            <div class="w-auto">
                            {{-- STR::LIMIT limita la cantidad de caracteres a mostrar por pantalla.
                                Se aplica cuando no supere la cantidad establecida por segundo parámetro. --}}
                            <h3 class="card-title fw-bold">{{ Str::limit($product->name, 25) }}</h3>
                            @if(strlen($product->description) < 150)
                                <p class="card-text">{{ Str::limit($product->description) }}</p>
                            @else
                                <p class="card-text">{{ Str::limit($product->description, 150) }}</p>
                            @endif
                        </div>

                            <div class="pt-3 w-100 d-flex justify-content-between align-items-center">
                                <div class="btn-group" role="group">
                                    {{-- Botón VER --}}
                                    <a href="{{ route('products.show', $product->id) }}">
                                        <button type="button" class="btn btn-info btn-sm text-white">Ver</button></a>
                                    {{-- Botón EDITAR. Exclusivo para ADMIN --}}
                                    @if(Auth::check() && Auth::user()->role == 1)
                                        <a href="{{ route('products.edit', $product) }}">
                                            <button type="button" class="btn btn-sm btn-warning">Editar</button></a>
                                    {{-- Botón BORRAR. Exclusivo para ADMIN. Incluye mensaje de aviso por JS
                                        al usuario --}}
                                        <a href="products/{{ $product->id }}"
                                            onclick="
                                                var confir = confirm('¿Seguro que quiere eliminar este producto?');
                                                event.preventDefault();
                                                if(confir){
                                                    document.getElementById('delete-form{{ $product->id }}').submit();
                                                }">
                                            <button type="button" class="btn btn-sm btn-danger">Borrar</button>
                                        </a>
                                        {{-- Formulario encargado de eliminar el producto  --}}
                                    <form method="POST" id="delete-form{{ $product->id }}"
                                        action="{{ route('products.destroy', [$product->id]) }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                    @endif
                                    <div class="w-inh" x-data="{ count: $persist(0), total: 0 }">
                                </div>
                                </div>
                                {{-- Precio de producto --}}
                                <small class="text-muted">{{ $product->price }}€</small>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fin de recorrido de array de productos --}}
            @endforeach
            {{-- Sentencia ALPINEJS. Se activa cuando el usuario llega
                al final de la página haciendo SCROLL --}}
            <div x-data="{
                observe() {
                    let watcher = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                @this.call('load')
                            }
                        })
                    }, {
                        root: null
                    })
            
                    watcher.observe(this.$el)
                }
            
            }" x-init="observe">
            </div>
            {{-- Mediante JS detectamos si se está mostrando mensaje de aviso de pedido enviado.
                Caso afirmativo, eliminamos los datos del SESSIONSTORAGE que se usan en los campos
                del formulario de pedido --}}
            <script type="text/javascript" defer>
                if(document.querySelector(".mt-5").textContent === '¡Gracias! Pronto enviaremos tu pedido') {
                    sessionStorage.clear();
                }
            </script>
        </div>
    </div>
</div>
