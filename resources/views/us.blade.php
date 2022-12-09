@extends('layouts.app')
@section('content')
{{-- Página de NOSOTROS --}}

{{-- Cabecera --}}
<div class="my-5"></div>
<header id="header" class="d-flex align-items-center bg-warning">
    <div class="container d-flex flex-column align-items-center my-5">
      <h1>CAMISETAS COMO MEDIO DE EXPRESIÓN</h1>
      <h2>Más allá de una empresa de productos poco serios</h2>
    </div>
  </header>

  <main id="main">

    {{-- Divisor --}}
    <hr class="mb-5">
    <section id="about" class="about">
      <div class="container">
    {{-- Contenido --}}
        <div class="section-title mx-auto col-lg-8 col-md-6s">
          <h2 class="text-center">Cómo empezó todo esto</h2>
          <p>Gabriel y Javier tenían pasión por crear y querían montar su propio negocio.
            Tras investigar un poco, decidieron crear camisetas con diseños y estampados únicos. 
            Empezaron con poco, imprimiendo y vendiendo camisetas en las calles de Cádiz.
            A pesar de las largas horas de trabajo y los bajos ingresos, mantuvieron su determinación
            y siguieron sacando adelante su negocio. 
            A medida que sus diseños de camisetas ganaban más y más adeptos, abrieron
            su primera tienda en pleno centro de la ciudad de Almería. Trabajaron duro para mejorar
            la calidad de sus productos y, con el tiempo,
            se hicieron con una amplia cartera de clientes.
            
            Fue durante un paseo con su hija por el parque cuando a Gabriel, en un alarde de originalidad,
            se le ocurrió llamar a la tienda AVESTIRSE, jugando con las palabras de AVE y la acción
            de "¡A VESTIRSE!" al ver unos patitos comiendo trocitos de pan colocados en una chaqueta tirada
            en el suelo.
            
            El negocio creció exponencialmente y pronto se expandieron a otros países.
          </p>
        </div>

      </div>
    </section>
    {{-- Divisor --}}
  <section class="mt-5">
    <hr class="mb-5">
  </section>
  {{-- Acerca de --}}
    <section class="w-75 mx-auto">
      <div class="row d-flex justify-content-center">
        <div class="col-md-10 col-xl-8 text-center">
          <h3 class="mb-4">Gabriel y Javier</h3>
          <p class="mb-4 pb-2 mb-md-5 pb-md-0 text-start">
            Dos buenos amigos comparten un vínculo especial basado en la confianza,
            la comprensión y el amor incondicional. Son leales el uno al otro
            y se apoyan mutuamente en los momentos difíciles.
            Disfrutan haciendo actividades juntos, como ver películas
            salir a pasear y probar nuevos restaurantes. Pueden ser sinceros
            el uno con el otro sobre cualquier cosa y hablar de temas que nadie más entiende.
            También comparten el sentido del humor y pueden hacerse reír mutuamente.
            Este tipo de amistades pueden durar toda la vida y son increíblemente valiosas,
            como el caso de Gabriel y Javier
          </p>
        </div>
      </div>
    {{-- Testimonios --}}
      <div class="row text-center">
        <div class="col-md-6 mb-4 mb-md-0">
          <div class="d-flex justify-content-center mb-4">
            <img src="{{ 'gfx/joaquin.jpg' }}"
              class="rounded-circle shadow-1-strong" width="100" height="100" />
          </div>
          <p class="lead my-3 text-muted">
            "La vida es un 10% lo que te ocurre y un 90% cómo reaccionas ante ello."
          </p>
          <p class="font-italic font-weight-normal mb-0">Gabriel Siquier</p>
        </div>
        <div class="col-md-6 mb-0">
          <div class="d-flex justify-content-center mb-4">
            <img src="{{ 'gfx/javier.jpg' }}"
              class="rounded-circle shadow-1-strong" width="100" height="100" />
          </div>
          <p class="lead my-3 text-muted">
            "La única forma de hacer un gran trabajo es amar lo que haces."
          </p>
          <p class="font-italic font-weight-normal mb-0">Javier Espadas</p>
        </div>
      </div>
    </section>
    <section class="mt-5">
      <hr class="mb-5">
    </section>
    <section id="contact" class="contact">
      <div class="container my-5">
    {{-- Sección contacto --}}
        <div class="section-title text-center">
          <h2>Contacto</h2>
        </div>

        <div class="row justify-content-center">

          <div class="col-lg-5 col-md-6 d-flex justify-content-center border pt-3 pb-3 rounded">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Dirección:</h4>
                <p>Calle San Pedro 6, 04001 Almería (España)</p>
              </div>

              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4>Email:</h4>
                <p>info@avestirse.com</p>
              </div>

              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Teléfono:</h4>
                <p>679417141</p>
              </div>
              <div class="justify-content-center">
              <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3193.157803405509!2d-2.467065584751248!3d36.83869657994068!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd70760137b70789%3A0x2df7ef7819a5d0ce!2sC.%20San%20Pedro%2C%206%2C%2004001%20Almer%C3%ADa!5e0!3m2!1ses-419!2ses!4v1669470782496!5m2!1ses-419!2ses" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>
            </div>
          </div>
          </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@stop