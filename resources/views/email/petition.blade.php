
Reserva en curso de el lugar {{$reserv->site->name}} : id {{$reserv->site->id}}  usuario que esta reservando 
{{$reserv->user->name}} id: {{$reserv->user->id}}
<hr/>


<br/>


Datos del espacio:<br/>
{{$reserv->site->phone}}
<br/>
{{$reserv->site->address}}

<br/>
{{$reserv->site->description}}

<br/>
<br/>

{{$reserv->site->user}}

<br/>
<br/>

https://meetwork.co/reservations/{{$reserv->site->id}}/{{urlencode($reserv->site->name)}}



<br/>
<br/>

Datos de la persona:<br>
{{$reserv->user}}

<hr/>
