<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#576dae" />


    <title>MeetWork</title>
<link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.0/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.0/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.0.0/mapbox-gl-draw.js'></script>
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.0.0/mapbox-gl-draw.css' type='text/css'/>
<script src="https://unpkg.com/vue-select@latest"></script>
 
 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<meta name="csrf-token" content="{{csrf_token()}}">

<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.2.0/mapbox-gl-geocoder.min.js'></script>
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.2.0/mapbox-gl-geocoder.css' type='text/css' />

</head>

<?php
 $agent =  Agent::isMobile();
?>
<body class="@if($agent) mobile  @endif">

    
    
    
<script>
    @if(Auth::check())
    var login='<?php echo json_encode(array("name"=>Auth::user()->name,"id"=>Auth::id())) ?>';
    @else
        var login="null";
    @endif
    
    window.fbAsyncInit = function() {
    FB.init({ 
      appId      : '893850434141116',
      cookie     : true,  // enable cookies to allow the server to access the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v2.8' // use graph api version 2.8
    });
  };
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>

    <div class="layout" id="app">

       <div class="navigation principal-header">
           <div class="container-fluid">
               <div class="logo-bars">
                   
               
               <div class="logo">     
               <router-link  :to="{name: 'allsite'}">
                    <img alt="logo" src="{{asset("/img/logom.png")}}">
               </router-link>
                  
               </div>
               <div class="links">
                    <ul class="ul-bars">
                        <li><button onclick="slideIn()" class="btn-bars-top"><i class="fa fa-bars" aria-hidden="true"></i></button></li>

                   </ul>
               </div>
                   </div>
               <div class="links navigator-links-general" id="navigator-links-general">
                   
                   <ul class="links-menu"> 
                       <li> <a href="/">Busca tu espacio</a></li>
                    
                   @if(!Auth::check())
                   <li v-if="!$root.$data.user"><a href="/register">Entrar</a></li>
                  @endif
                    <?php 
                    if(Auth::check()){
                        $user = Auth::user();
                        $admin=$user->isAdministrator();
                    }
                    
                    ?>
                   
                   @if(Auth::check())
                   
                        @if($admin)
                    <li v-if="$root.$data.user"> <a href="/adminMeetwork">Admin</a></li>
                       @endif
                       
                    <li v-if="$root.$data.user"> <a href="/dashboard">Mis espacios</a></li>

                     <li v-if="$root.$data.user"><a href="/profile">Perfil</a> </li> 
                     <li v-if="$root.$data.user"><a href="/create">Registrar Espacio</a></li> 
                     <li v-if="$root.$data.user"> <a href="/logout">Salir</a></li>
                     @endif
                     
                   
                   </ul>
                  
                   
               </div>
           </div>
       </div>
        <div class="container-fluid">

            <router-view></router-view>
        </div>
    </div>


    <script src="{{ mix('js/app.js') }}"></script>
    
    
    <script>
    var slideOut=true;
    function slideIn(){
        if(slideOut){
            document.getElementById("navigator-links-general").style.marginTop = "0px";
            slideOut=false;
        }else{
            document.getElementById("navigator-links-general").style.marginTop = "-400px";
                        slideOut=true;

        }
    }
    
    </script>
</body>
</html>
