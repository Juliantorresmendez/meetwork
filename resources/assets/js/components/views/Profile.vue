<template>
    <div class="site-container">

        <div class="row">
            <div class="col-md-4 ">
                <div class="row container-info">
                    <div class="col-md-12 container-info-site user-individual">
                        <div class="row logo-info-site">
                            <div class="col-md-3 container-site-logo">
                                <img  class="logo-site" v-bind:src="defaultImage" />

                            </div> 
                            <div class="col-md-9">
                                <h3>{{user.name}}</h3> 
                                <p>{{user.country}}</p>
                                <p>{{user.profesion}}</p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="container-bio-text">{{user.bio}}</p>
                            </div> 
                        </div>
                    <div class="row container-edit-buttons">
                                    <div class="col-md-7 container-vue-image-cropie">
                                <VueImageUploadCroppie :defaultImage.sync="defaultImage" :height="200" :width="200" :circle="true" :trans="trans"></VueImageUploadCroppie>
                                    </div> 
                                    <div class="col-md-5">
                                        <button class="btn-purple btn-small edit-button" v-on:click="editProfile()">Editar</button>
                                    </div>
                                    
                    </div> 

                    </div> 

                </div>
            </div>
            <div class="col-md-8 container-description-site profile-container-general">
                <h2>Espacios Visitados</h2>

                <div class="row ">
                    <div class="col-12 col-md-6" v-for="item in list">
                        <router-link class="place "  :key="$route.fullPath"  :to="{name: 'view_site', params: { id: item.site.id,name:string_to_slug(item.site.name) }}"   :id="'sp_' + item.site.id" >
                                     <div class="slick-initialized slick-slider">
                                <div class="slick-list">
                                   <div class="slick-track" >
                                        <div class="slick-slide slick-cloned photo_place" data-index="-1"
                                             v-bind:style="{ 'background-image': 'url(' + formatesetresImages(item.site.id,item.site.images[0].url)  + ')' }"
                                             ></div>

                                    </div>
                                </div>   

                            </div>
                            <div class="header">
                                <div class="place-info">
                                    <h3>{{item.site.name}}</h3>
                                    <h5>Buenos aires - {{item.site.address}}</h5>
                                    <p><span style="color:orange" v-if="item.status_id==1">En Proceso</span> <span  style="color:green" v-if="item.status_id==2">Aprobado</span> <span  style="color:gray" v-if="item.status_id==3">Visitado</span> <span  style="color:red" v-if="item.status_id==3">Declinado</span></p>
                                    
                                </div>
                            </div>
                            <div class="services">
                                <div class="service_label " v-for="service in item.site.services">
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAMCAYAAAHKRddyAAAAAXNSR0IArs4c6QAAAZNJREFUKBWFUj1LA0EQnbkEm4CVpBELsTJKolZ2+gOCINp6SUCImlwSFVRIo6KIVpIELJQQU1iJ2NnHWuTuVNBg50dlZSrJ7Ti3YcNFAm6xb+fN25m3NwfAK5Y2311srbhh76uzRCQiTO08BRpfzbNAAJclqxvWh56xdt1AM4x6LwDVgj3BQ1ctFd4NY9mHOXKcS0S0ELUDIgeBIE8AIxr6ZtpX9LRJgHCFhEhAs9VSROY0Vc4l3CSL+lTSzUlVImdOCAdOuMQYEN370ZcsF0dN1A2zwf0C3H8PNayRA1MEIs/xt8bbZqssfQohtlCDNxkjbMuyMcMqsdMFNsGlYYXJi0oxvKQ8/Y/yWR6ZLLuYs4d/muKI+agn1/XIHq8R/RuVQqiO0g9RipUv/PJctRS+6XqLyUTWjgpBxzyXIUA8lZ2VOJ6zp4UjyvxpBhWnkDu+8owSlWLkVnEd6E5az5hZffW535tIrNkDMcNe//sndHTuuGA8hgQ0k2yvel4I33lz6tyevyIUCnDG2f48x5OK+4u/Usub7bKVLm8AAAAASUVORK5CYII=" alt="icon">
                                    <p>{{service.name}}</p>
                                </div>

                            </div>
                        </router-link>
                    </div>


                </div>
                <div class="row" v-if="list.length==0">
                    <div class="col-md-12">
                        <h3>Aún no visitaste ningún espacio</h3>
                        <button v-on:click="goToHome()" class="btn-purple button-work-here">Visita un espacio</button>
                    </div>

                </div>

            </div>
        </div>




        <sweet-modal ref="modalEdit" class="container-modal-review">
            <div class="row" >   
                <div class="col-md-12">
                <div class=" login-container profile-edit-container">
                    <h1 class="title">Editar Cuenta</h1> 
                    <form method="post"  @submit="editProfilePost">
                        <div class="form-group">
                            <input required="required" type="text" v-model="user.name" id="name"  placeholder="Ingresa tu Nombre" class="form-control"> <!---->
                        </div> 
                        <div class="form-group">
                            <input  type="text" v-model="user.lastname"  id="lastname"  placeholder="Ingresa tu Apellido" class="form-control"> <!---->
                        </div> 
                        
                        <div class="form-group">
                            <input type="number" v-model="user.cel" id="cel"  placeholder="Ingresa tu Whatsapp" class="form-control"> <!---->
                        </div> 
                        <div class="form-group">
                            <input  required="required" type="email" v-model="user.email" id="email"  placeholder="Ingresa tu Correo Electrónico" class="form-control"> <!---->
                        </div>
                        <div class="form-group">
                            <input type="text" v-model="user.country" id="country"  placeholder="Ingresa tu Nacionalidad" class="form-control"> <!---->
                        </div>
                        
                        <div class="form-group">
                            <input type="text" v-model="user.profesion" id="profesion"  placeholder="Ingresa tu Profesion" class="form-control"> <!---->
                        </div>
                          
                        <div class="form-group">

                            <textarea v-model="user.bio" placeholder="Escribe un poco sobre tí"></textarea>
                        </div>
                        <div class="form-group container-button-login">
                            <button type="submit" class="btn-white">Actualizar Perfil</button> 
                                

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
import VueImageUploadCroppi from 'vue-image-upload-croppie'

   

    export default {
        data: function () {
            return {
                list: [

                ],
                user: {},

                defaultImage: '/no-image-available.png',
                'trans': { 
                    'cropImage': 'Cambiar imágen', 
                    'chooseImage':'Seleccionar Imágen', 
                    'confirmCutting': 'Confirmar'
                }
            }

        },    
        
 

        watch: { 
 
            defaultImage: function (val) {
                 if (val) {
                     console.log("aqui",val);
                     this.editAvatar(val);
                }

            }
        },
        mixins: [helpers],

        created: function () {

        this.getUser();
        }, 
        methods: {  
              isBase64(str) {
                try {
                    return btoa(atob(str)) == str;
                } catch (err) {
                    return false;
                }
            },
            updateObject(){   
                console.log("updateObject");
            },
            editProfile() {
                this.$refs.modalEdit.open();  
            },
            imageuploaded(res) {      
                if (res.errcode == 0) {
                    this.src = 'http://img1.vued.vanthink.cn/vued751d13a9cb5376b89cb6719e86f591f3.png';
                }
            },
            goToHome() {
                this.$router.push({name: 'all_site'})

            },
            editAvatar(avatar){
                var _this=this;
                if (avatar.indexOf("data") >= 0){

                    axios.post('editAvatar', {avatar:avatar}) 
                    .then(response => _this.successUploadAvatar(response.data))
                    .catch(error => console.log(error)); 
                }
              
            },
            successUploadAvatar(data){
                swal("Actualizado","Imagen subida con éxito", "success");

                
                console.log(data);
            },
            editProfilePost(e){ 
              
              var _this=this;
              axios.post('editProfile', this.user) 
                    .then(response => _this.successsUpdateProfile(response.data))
                    .catch(error => _this.errorUpdateProfile(error)); 
                        e.preventDefault();

            },
            errorUpdateProfile(error){
                swal("Error",error, "error");
            },
            successsUpdateProfile(data){
                
                if(!!data.success){
                    swal("Actualizado",data.data, "success");
                }else{
                    swal("Error",data.data, "error");

                }
                                this.$refs.modalEdit.close();  

            },
            getUser() {
                if (this.$route.params.id) {
                    axios.get('/app/profile/' + this.$route.params.id)
                            .then(response => this.formatProfile(response.data))
                            .catch(error => console.log(error.response.data));
                } else {
                    axios.get('/app/profile/')
                            .then(response => this.formatProfile(response.data))
                            .catch(error => console.log(error.response.data));
                }

            },
            formatProfile(data) {
                this.list = data.reservations;
                this.user = data;
                
                this.defaultImage=data.avatar;

                console.log(this.defaultImage);
            }
        }
        ,
        components: {
            SweetModal,VueImageUploadCroppi
        }
    }
</script>
