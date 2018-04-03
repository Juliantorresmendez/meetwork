
El dueño del lugar {{$reserv->site->name}} : id {{$reserv->site->id}}  ha declinado la reserva de 
{{$reserv->user->name}} id: {{$reserv->user->id}}
<hr/>

@if($reserv->description)
Descripción
<br/>
<strong>{{$reserv->description}}</strong>
<hr/>

@endif
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

Datos de la persona:<br>
{{$reserv->user}}

<hr/>
