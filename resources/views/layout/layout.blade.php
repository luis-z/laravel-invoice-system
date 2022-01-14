<!DOCTYPE html>
<html lang="es">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
  
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
  
      <title>Ejercicio Laravel</title>

      <!-- <link href="{{ asset('css/style.css') }}" rel="stylesheet"> -->
      <!-- <script src="{{ asset('js/vue.js') }}"></script> -->
      <!-- <script src="{{ asset('js/vue.min.js') }}"></script> -->
      <script src="{{ asset('js/axios.js') }}"></script>
      <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
      <style>
        body {
            font-family: Roboto!important;
        }
        </style>
    </head>
    <body>
        <div id="app">
            <div class="loader"></div>
            @yield('content')
        </div> 
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
    @yield('scripts')
    
    </body>
</html>