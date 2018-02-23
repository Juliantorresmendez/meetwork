<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Validacion de cuenta de Meetwork</h2>

        <div>
           
            Gracias por crear una cuenta con Meetwork.
            Por favor sigue el siguiente link {{ URL::to('register/verify/' . $confirmation.'/'.$email) }} o copialo y pegalo en la barra de direcciones.
            <br/>
            También puedes copiar el siguiente codigo y pegarlo en la página de validación {{$confirmation}}
        </div>

    </body>
</html>