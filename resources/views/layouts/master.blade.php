<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel-Vue</title>
<link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.0/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.0/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.0.0/mapbox-gl-draw.js'></script>
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.0.0/mapbox-gl-draw.css' type='text/css'/>
<script src="https://unpkg.com/vue-select@latest"></script>
  <meta name="csrf-token" content="{{csrf_token()}}">

</head>
<body>

<div>

    <div class="layout" id="app">

       <div class="navigation">
           <div class="container-fluid">
               <div class="logo">
               <router-link  :to="{name: 'all_site'}">

                                   <img alt="logo" src="{{asset("/img/logo.svg")}}">

               </router-link>
                  
               </div>
               <div class="links">
                   <ul>
                     <li> <router-link  :to="{name: 'all_site'}">Busca tu espacio</router-link></li>
                     <li v-if="!$root.$data.user"><router-link  :to="{name: 'login'}">Entrar</router-link></li>
                     <li v-if="$root.$data.user"> <router-link  :to="{name: 'singleProfile'}">Perfil</router-link></li> 
                     <li v-if="$root.$data.user"> <a href="/logout">Salir</a></li>
                   </ul>
               </div>
           </div>
       </div>
        <div class="container-fluid">

            <router-view></router-view>
        </div>
    </div>

  </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
