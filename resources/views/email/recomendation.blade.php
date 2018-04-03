<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Hay un usuario conquistando un sitio Meetwork</h2>

        <div>
        
           El Usuario: {{$user["name"]}}
           <br/>Email: {{$user["email"]}}
           <br/>Cel: {{$user["cel"]}}
           <br>
           https://www.google.com/maps?q={{$lat}},{{$lon}}
          
           <br>
           @if(Auth::check())
           <pre>
               {{Auth::user()}}
           </pre>
           @endif
        </div>

    </body>
</html>