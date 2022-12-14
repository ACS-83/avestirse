{{-- Inicio cabecera APP (instalado por defecto por Laravel UI)--}}
@if (isset($ordersChecking) && !Auth::check())
    {{ $ordersChecking = '' }}
@endif
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Nombre de logo (superior izquierda) --}}
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fuentes -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    
    {{-- Estilo para casos concretos --}}
    <style>
        .w-inh {
            width: inherit;
        }

        .cov {
            object-fit: cover;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/products_images/logo.png" class="navbar-brand" alt="" srcset="" height="39">
                    {{ config('app.name') }}
                    
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
        
                    <!-- Izquierda Navbar -->
                    <!-- Derecha Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <a class="nav-link mx-auto" href="{{ route('products.index') }}">{{ __('Productos') }}</a>
                    {{-- @if (Auth::check())
                        {{ dd($ordersChecking) }}
                    @endif --}}
                        <a class="nav-link mx-auto" href="{{ route('us') }}">{{ __('Nosotros') }}</a>
                        <!-- Autentificaci??n -->
                        {{-- Si el usuario est?? registrado, su rol es 0 (usuario) y est?? verificado... --}}
                        @if(Auth::check() && Auth::user()->role == 0 && Auth::user()->email_verified_at)
                        <a class="mx-auto" href="{{ route('orders.index') }}" title="Ir al carrito">
                            <li class="nav-item">
                                {{-- Muestra en pantalla 0 si el carrito viene vac??o --}}
                                @if(session('productsCart') === null)
                                <label class="nav-link" style="cursor: pointer;" for="submit-form" tabindex="0"><span class="badge bg-danger text-white" id="cart">0</span><i class="fa-solid fa-cart-shopping"></i></label>  
                                @else
                                {{-- Caso contrario, muestra la cantidad de productos en carrito --}}
                                <label class="nav-link" style="cursor: pointer;" for="submit-form" tabindex="0"><span class="badge bg-danger text-white" id="cart">{{ count(session('productsCart')) }}</span><i class="fa-solid fa-cart-shopping"></i></label>
                                @endif
                            </li>
                        </a>
                        @endif
                        
                        @guest
                        {{-- Inclusi??n de iconos de LOGIN y REGISTRO de fontawesome --}}
                            @if (Route::has('login'))
                                <li class="nav-item mx-auto">
                                    <a class="nav-link" href="{{ route('login') }}"><i class="fa-solid fa-user" >&nbsp;</i>{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item mx-auto">
                                    <a class="nav-link" href="{{ route('register') }}"><i class="fa-solid fa-square-pen">&nbsp;</i>{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                        
                            <li class="nav-item dropdown mx-auto">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle text-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            @if (isset($ordersChecking) && Auth::check() && Auth::user()->role == 1)
                                            @php $count = count($ordersChecking) @endphp
                                            @if (isset($count) && $count >= 1)
                                            <span class="text-danger">??? </span>
                                            @endif
                                            @endif
                                            {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    {{-- Muestra el bot??n de PEDIDOS si eres ADMIN o USER.
                                        Contiene comportamientos de JS para enviar
                                        el formulario al hacer clic desde un bot??n que se encuentra
                                        fuera de ??l --}}
                                    @if (Auth::check() && Auth::user()->role == 1 || Auth::check() && Auth::user()->role == 0)
                                        <a class="dropdown-item text-md-start text-center
                                        
                                        " href="{{ route('orders.list') }}"
                                            onclick="event.preventDefault();
                                            document.getElementById('list').submit();">
                                            Pedidos
                                            @if (isset($count) && $count >= 1 && Auth::user()->role == 1)
                                                <span class="badge bg-danger">{{ $count }}</span>
                                            @endif
                                            </a>
                                        <form id="list" action="{{ route('orders.list') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>  
                                    @endif
                                    {{-- Eliminaci??n del SESSION cuando se hace LOGOUT --}}
                                    <a class="dropdown-item text-md-start text-center" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();
                                        sessionStorage.clear();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        {{-- Inclusi??n de contenidos a continuaci??n del navbar --}}
        <main class="py-5">
            @yield('content')
        </main>
    </div>
</body>
</html>
