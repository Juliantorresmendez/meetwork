<template>
    <div class="site-container">

        <div class="container container-form">
            <div class="row">
                <div class="col-md-8">
                    <h1>Reservas de {{$route.params.name}}</h1>
                </div>
                <div class="col-md-4">
                    <button v-on:click="backToEdit()" class="btn-purple btn-small pull-right">Volver a {{$route.params.name}}</button>
                </div>
            </div>
            <vue-tabs  :centered="true"  activeTabColor="#8186ff" activeTextColor="#ffffff">


                <v-tab title="Hoy">
                    <h2 v-if="reservs.current.length==0">No hay reservaciones para Hoy</h2>

                    <table   v-if="reservs.current.length>0" class="table table-hover table-reservations">
                        <thead>
                        <th>Fecha de la reservación</th>
                        <th>Número de personas</th>
                        <th>Usuario</th>
                        <th>Acciones</th>

                        </thead>
                        <tbody>
                            <tr v-for="(reserv, index)  in reservs.current ">


                                <td>{{ reserv.date_reservation+" "+reserv.time+":00" | moment("MMMM Do YYYY, h:mm a") }}</td>
                                <td>{{reserv.number_person}} </td>
                                <td> <router-link target="_blank"   :to="{name: 'profile',params:{name:string_to_slug(reserv.user_name),id:reserv.user_id}}">{{reserv.user_name}} </router-link> </td>
                        <td>
                            <p v-if="reserv.status_id==4">Declinado</p>
                            <p v-if="reserv.status_id==3">Aceptado</p>

                            <button v-on:click="declineReserv(reserv.id,index,'current')"   v-if="reserv.status_id!=4 && reserv.status_id!=3" class="btn-small btn btn-danger">Declinar</button>
                            <button v-on:click="acceptReserv(reserv.id,index,'current')" v-if="reserv.status_id!=3 && reserv.status_id!=4"  class="btn-small btn btn-success">Reservar</button>
                        </td>
                        </tr>
                        </tbody>

                    </table>
                </v-tab>

                <v-tab title="Programadas">
                    <h2 v-if="reservs.future.length==0">No hay reservaciones Futuras</h2>

                    <table  v-if="reservs.future.length>0" class="table table-hover table-reservations">

                        <thead>
                        <th>Fecha de la reservación</th>
                        <th>Número de personas</th>
                        <th>Usuario</th>
                        <th>Acciones</th>

                        </thead>
                        <tbody>
                            <tr v-for="(reserv, index) in reservs.future ">


                                <td>{{ reserv.date_reservation+" "+reserv.time+":00" | moment("MMMM Do YYYY, h:mm a") }}</td>
                                <td>{{reserv.number_person}}  </td>
                                <td> <router-link target="_blank"   :to="{name: 'profile',params:{name:string_to_slug(reserv.user_name),id:reserv.user_id}}">{{reserv.user_name}} </router-link> </td>
                        <td>
                            <button v-on:click="declineReserv(reserv.id,index,'future')"  v-if="reserv.status_id!=4 && reserv.status_id!=3" class="btn-small btn btn-danger">Declinar</button>
                            <button v-on:click="acceptReserv(reserv.id,index,'future')"   v-if="reserv.status_id!=3 && reserv.status_id!=4"   class="btn-small btn btn-success">Reservar</button>

                            <p v-if="reserv.status_id==4">Declinado</p>
                            <p v-if="reserv.status_id==3">Aceptado</p>
                        </td>
                        </tr>
                        </tbody>   

                    </table>
                </v-tab>

                <v-tab title="Realizadas">
                    <h2 v-if="reservs.past.length==0">No hay reservaciones Realizadas</h2>

                    <table  v-if="reservs.past.length>0" class="table table-hover table-reservations">
                        <thead>
                        <th>Fecha de la reservación</th>
                        <th>Número de personas</th>
                        <th>Usuario</th>

                        </thead>
                        <tbody>
                            <tr v-for="reserv in reservs.past ">


                                <td>{{ reserv.date_reservation+" "+reserv.time+":00" | moment("MMMM Do YYYY, h:mm a") }}</td>
                                <td>{{reserv.number_person}} </td>
                                <td> <router-link target="_blank"   :to="{name: 'profile',params:{name:string_to_slug(reserv.user_name),id:reserv.user_id}}">{{reserv.user_name}} </router-link> </td>

                        </tr>
                        </tbody>

                    </table>
                </v-tab>

            </vue-tabs>

        </div>

        
        <sweet-modal ref="modalDeclineReserv" class="container-modal-review">
            <div class="row" >
                <div class="col-md-12">
                    <div class=" login-container profile-edit-container">
                        <h1 class="title">¿Estas seguro de declinar esta reserva?</h1>


                        <h3 class="title">Este es es un subtitulo explicando y alertando de que pasa si declina</h3>
                        <div class="loading-container" v-if="showFormPopup">
                            <i class="fa fa-cog fa-spin"></i>  Enviando...

                        </div>

                        <form method="post" v-if="!showFormPopup" >
                           

                            <div class="form-group">

                                <textarea placeholder="Ingresa la descripción por que se declina" v-model="formDecline.descriptionDecline"></textarea>
                            </div>

                            <div class="form-group container-button-login">
                                <button type="button" v-on:click="delineSubmit()" class="btn-white">Declinar Reserva</button>


                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </sweet-modal>

    </div>
</template>     

<script>
    import helpers from './helpers';

    import { SweetModal, SweetModalTab } from 'sweet-modal-vue'


    export default {
        data: function () {
            return {

                reservs: {
                    past: [],
                    current: [],
                    future: [],
                    pending: [],
                },
                showFormPopup:false,
                formDecline:{
                    descriptionDecline:""
                },
                idReserv:null,
                indexReserv:null,
                typeReserv:null
                
                


            }



        },

        watch: {

        },
        mixins: [helpers],

        created: function () {
            this.getReservations();
        },
        methods: {
            backToEdit() {
                this.$router.push({name: 'edit_site', params: {id: this.$route.params.id}})

            },
            getReservations() {
                axios.post('/getReservations/' + this.$route.params.id)
                        .then(response => this.formatReservations(response.data))
                        .catch(error => console.log(error));
            },
            declineReserv(id, index, type) {
                this.idReserv=id;
                this.indexReserv=index;
                this.typeReserv=type;
                this.$refs.modalDeclineReserv.open();


               

            },
            acceptReserv(id, index, type) {
               this.formDecline.descriptionDecline="";
                this.indexReserv=index;
                this.typeReserv=type;
                this.idReserv=id;

               axios.post('/acceptReserv/' + id,this.formDecline)
                                    .then(response => this.processAcept(response.data))
                                     .catch(error => console.log(error));
               

            },
            delineSubmit(){
                this.showFormPopup=true;
               axios.post('/declineReserv/' + this.idReserv,this.formDecline)
                                    .then(response => this.processDecline(response.data))
                                     .catch(error => console.log(error));  
            }
        ,
            processAcept(data) {
                this.showFormPopup=false;

                var index=this.indexReserv;
                var type=this.typeReserv;
                
                console.log("index resdrva ",this.reservs.future[index].status_id);
                if (!!data.error) {
                    swal("Error", data.message, "error");

                }
                if (!!data.success) {
                    swal("Confirmado", data.message, "success");
                    if (type == "future") {
                        this.reservs.future[index].status_id = 3;                        
                    } else {
                        this.reservs.current[index].status_id = 3;
                    }
                }

            }    
        ,
            processDecline(data) {
                this.showFormPopup=false;
                this.$refs.modalDeclineReserv.close();
                this.formDecline.descriptionDecline="";

                var index=this.indexReserv;
                var type=this.typeReserv;
                if (!!data.error) {
                    swal("Error", data.message, "error");

                }
                if (!!data.success) {
                    swal("Declinado", data.message, "success");
                    if (type == "future") {
                        this.reservs.future[index].status_id = 4;
                    } else {
                        this.reservs.current[index].status_id = 4;
                    }
                }

            },

            formatReservations(data) {
                
                if (!!data.error) {
                    swal("Error", data.message, "error");
                    window.location="/";
                }else{
                                    this.reservs = data;

                }
                  

            }
        }
        ,
        components: {
SweetModal
        }
    }
</script>
