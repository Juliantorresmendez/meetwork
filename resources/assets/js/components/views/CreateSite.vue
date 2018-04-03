<template>
    <div class="site-container">

        <div class="container container-form">
            
            <form-wizard finishButtonText="Guardar"  color="#8186ff"  title="Creación de espacio" nextButtonText="Siguiente" subtitle="Para registrar tu espacio completa el formulario de registro">
                <tab-content title="Datos Básicos" :before-change="beforeTabSwitch">
                  <form>
                      <div class="row">
                          <div class="col-md-6">
                              
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nombre</label>
                                <input v-model="site.name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ingresa el nombre">
                            </div>
                              
                          </div>
                          
                          <div class="col-md-6">
                              
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Teléfono</label>
                                    <input  v-model="site.phone" type="text" class="form-control"  placeholder="Ingresa el teléfono">
                                </div>
                              
                          </div>
                          <div class="col-md-6">
                          
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Correo</label>
                                    <input  v-model="site.email" type="email" class="form-control"  placeholder="Ingresa el Correo Electrónico">
                                </div>
                              
                          </div>
                          <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Ciudad</label>
                                    <input  v-model="site.city" type="text" class="form-control"  placeholder="Ingresa la ciudad">
                                </div>  
                          </div>
                          <div class="col-md-12">
                              
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Dirección</label>
                                    <input @focus="focusAdress()"  v-model="site.address" type="text" class="form-control"  placeholder="Ingresa la Dirección">
                                </div>
                              
                          </div>

                         <div class="col-md-6">
                         
                            <div class="form-group">
                                <label for="exampleInputPassword1">Servicios</label>
                                <v-select multiple label="name" :options="optionsService" v-model="site.selectedServices"></v-select>

                            </div>
                             
                             
                         </div>
                         <div class="col-md-6">
                              
                            <div class="form-group">
                                <label for="exampleInputPassword1">Espacios</label>
                                <v-select multiple label="name" :options="optionsSpace" v-model="site.selectedSpace"></v-select>

                            </div>

                             
                             
                          </div>
                          <div class="col-md-12">
                              
                              <div class="form-group">
                                <label for="exampleInputPassword1">Descripción</label>

                                <textarea v-model="site.description" placeholder="Descripción"></textarea>
                            </div>
                              
                          </div>
                          
                      </div>

                
 
                

                
                
            </form>
                    
                    
                    
                </tab-content>
                   
                 
            </form-wizard>
            
            
        </div>
        <sweet-modal ref="modal" class="container-modal-review">
			<div class="row">
                            <div class="col-md-7">
                                 <div class="form-group">
                                    <input v-model="validAdress" type="text" class="form-control"  placeholder="Ingresa tu Dirección">
                                </div>
                            </div>
                             <div class="col-md-5">
                                 <button class=" btn-purple btn-medium" v-on:click="getSites()"> Validar Dirección</button>
                            </div>
                            <div class="col-md-12 temp-places-popup">
                                <button  class="btn-white" v-for="item in tempSites" v-on:click="setSiteMap(item)">{{item.display_name}}</button>
                                <div v-if="!!mapGeneral" >
                                    <img class="map-static" :src="mapGeneral"/>
                                    <button v-on:click="selectAdress()"  class="btn-white" >Seleccionar Dirección</button>
                                </div>
                            </div>
                        </div>
		</sweet-modal>
       
    </div>
</template>     
 
<script>
    import helpers from './helpers';
  import Dropzone from 'vue2-dropzone'
import { SweetModal, SweetModalTab } from 'sweet-modal-vue'



    export default {
        data: function () {
            return {
                tempSites:[],
                selectedSite:null,
                selectedSiteLatLon:null,
                site:{
                  id:null,
                  name:null,  
                  phone:null,  
                  email:null,  
                  city:null,  
                  selectedServices:[],  
                  selectedSpace:[],  
                  description:null,  
                  address:null
                }, 
                mapGeneral:"",
                validAdress:"",
                optionsSpace:[],
                optionsService:[],
                selectedServices:[],
                csrf:document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                dic:{
                    dictDefaultMessage: "<br>Seleccionar las fotos del espacio"
                }
                ,uploadFiles:false
                       

                
            } 
            
            

        },   

        watch: {

        },  
        mixins: [helpers],

        created: function () {
            this.getFilters();
        },
        methods: {
            
            getFilters: function (page, $state) {
                        axios.get('/getFilters')
                                .then(response => this.formatFilters(response.data, $state))
                                .catch(error => console.log(error));
                    },
                    formatFilters(data) {
                        this.optionsSpace = data.spaces;
                        this.optionsService = data.services;
                    },
            
            selectAdress(){
                
                this.$refs.modal.close();
                var siteNameExplode=this.selectedSite.display_name.split(",");
                var nameAddress="";
                console.log("aqui address");
                if(siteNameExplode.length<5){
                    nameAddress=this.selectedSite.display_name;
                }else{
                    var firstFiveName=siteNameExplode.slice(0, 5);
                    nameAddress= firstFiveName.join(",");
                }
                this.site.address = nameAddress;
                
                this.site.address=this.selectedSite.display_name;
                this.site.lat=this.selectedSite.lat;
                this.site.lon=this.selectedSite.lon;
                console.log(this.site);

            },
            setSiteMap(data){
                this.setStaticImage(data.lon,data.lat);
                this.selectedSite=data;
            },
            focusAdress(){
                this.$refs.modal.open();
  
            },
            setStaticImage(lat,lon){
              var urlStatic="https://api.mapbox.com/styles/v1/mapbox/streets-v10/static/url-https%3A%2F%2Fmapbox.com%2Fimg%2Frocket.png("+lat+","+lon+")/"+lat+","+lon+",15/600x300?access_token=pk.eyJ1IjoibG9yZG1hY3VzIiwiYSI6ImNqZGdub3B4eDBtb3QyeG45Y3YwMDZnd3YifQ.0c8uzpkL-Ib5UBj8cVBUQA";
              this.mapGeneral=urlStatic;
            },
            
            publishSite(){
                axios.post('/publishSite', this.site) 
                    .then(response => this.configSite(response.data))
                    .catch(error => console.log(error));  
            },
            configSite(data){
                this.site.id=data.id;  
               
                this.$router.push({name: 'edit_site_new', params: {id: this.site.id,new:1}})
               
                return false;
                console.log("config site");
            },
            getSites(){
                
                var search=this.validAdress+", Buenos Aires, Argentina";
              axios.get('https://nominatim.openstreetmap.org/search/'+encodeURI(search)+'?format=json&addressdetails=1&limit=1&polygon_svg=1')
                    .then(response => this.formatGeoCoding(response.data) )
                    .catch(error => console.log(error.response.data));  
            },
            formatGeoCoding(data) {
                
               for(var i=0;i<data.length;i++){
                   
                   this.tempSites.push(data[i]);
               }
               
               if(data.length==1){
                    this.selectedSite=data[0];
                    this.setStaticImage(data[0].lon,data[0].lat);

               }
              },
            beforeTabSwitch(){
                this.publishSite();
                return true;
            },
            'showSuccess': function (file) {
              console.log('A file was successfully uploaded')
            }
        }
        ,
        components: {
        Dropzone ,SweetModal,
		SweetModalTab

        }
    }
</script>
