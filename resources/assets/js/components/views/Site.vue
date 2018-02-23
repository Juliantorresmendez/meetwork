<template>
	<div class="site-container">
	    <div class="row">
	    	<img src="/img/fondo1_1.jpg" />
	    </div>
	    <div class="row">
	    	<div class="col-md-4 ">
	    		<div class="row container-info">
	    			<div class="col-md-12 container-info-site">
	    				<div class="row logo-info-site">
	    					<div class="col-md-3 container-site-logo">
			    				<img class="logo-site" v-for=" logo in site.logo" v-bind:src="formatImagesLogo(logo.url,'medium')" />
			    			</div>
			    			<div class="col-md-9">
			    				<h3>{{site.name}}</h3> 
			    				<div class="row">
			    					<div class="col-md-5">
			    						<star-rating v-model="rating" @rating-selected ="openRating"  :star-size="15" :show-rating="false" active-color="#8D98F1"  ></star-rating>
			    					</div>
			    					<div class="col-md-7 container-site-visit">
			    						<strong>234</strong> Visitas
			    					</div>
			    					
			    				</div>
			    				<p>{{site.address}}</p>
			    				
			    			</div>
	    				</div>
	    				<div class="row container-map-site">
		    				<div class="shadow-up">
		    					
		    				</div>
			    						<mapbox  
										access-token="pk.eyJ1IjoibG9yZG1hY3VzIiwiYSI6ImNqZGdub3B4eDBtb3QyeG45Y3YwMDZnd3YifQ.0c8uzpkL-Ib5UBj8cVBUQA"
										:map-options="{
									    	style: 'mapbox://styles/mapbox/light-v9',
									    	zoom: 13,
									    	center: [-58.4389656,  -34.6016045],
									    }"
										:geolocate-control="{
											show: false, 
											 position: 'bottom-right'
										}"
										:scale-control="{
											show: false,
										}"
										:fullscreen-control="{
											show: false
											}"
										:nav-control="{
										show: false
										}"
							    		@map-load="mapLoaded"

										></mapbox>
								<div class="shadow-down container-work-here">
    								<button v-on:click="showWorkHere()" class="btn-purple pull-right button-work-here">Hoy Trabajo Aquí</button>
		    					</div>
		    				</div>
		    				<div class="row container-services">
		    					<h4>Servicios</h4>
		    					<p v-for="service in site.services"><img src="/img/wifi.png" /> {{service.name}}</p>
		    				</div>

		    				<div class="row container-spaces">
		    					<h4>Espacios</h4>
		    					<p v-for="space in site.spaces"><img src="/img/wifi.png" /> {{space.name}}</p>
		    				</div>
	    			</div> 
	    			
	    		</div>
	    	</div>
	    	<div class="col-md-8 container-description-site">
	    		<div class="row">
	    			<div class="col-md-12 container-site-gallery">
	    				<div class="container-image-mini" v-for="(image, index) in images" @click="openGallery(index)" v-bind:style="{ 'background-image': 'url(' + formatImagesGallery(image.src,'uploads','thumbs') + ')' }">
	    					 
	    				</div>
 
	    				
	    		<lightbox :images="images"
        ref="lightbox"

	    		></lightbox>

	    			</div>
	    		</div>
	    		<h4>Acerca de Nosotros</h4>
	    		<p>
	    			{{site.description}}
	    		</p>
	    		<div class="row ">
	    			<div class="col-md-12 ">
	    				<div class="container-site-review ">
	    						<div class="row">
	    							<div class="col-md-2 container-review">
	    								<strong>{{reviewCount.count}}</strong> Reviews 
	    							</div>
	    							<div class="col-md-2">
    									<star-rating v-model="rating" @rating-selected ="openRating"  :star-size="15" :show-rating="false" active-color="#8D98F1"  ></star-rating>


	    							</div>
	    							<div class="col-md-8">
	    								<button v-on:click="show()" class="btn-purple pull-right">Escribir Opinión</button>
	    							</div>
	    						</div>
	    				</div>
	    			</div>
	    		</div>
	    		<div class="row container-comments" v-for="review in site.reviews">
	    			<div class="col-md-4">
	    				<div class="row">
	    					<div class="col-md-4 container-logo-comments">
			    				<router-link  :key="$route.fullPath"  :to="{name: 'profile', params: { id: review.user.id,name:string_to_slug(review.user.name) }}"  >
			    					<img class="logo-site" src="/img/logo.png" />
			    					</router-link>
	    					</div>
	    					<div class="col-md-8 container-user-comments">
	    						<h5><router-link  :key="$route.fullPath"  :to="{name: 'profile', params: { id: review.user.id,name:string_to_slug(review.user.name) }}"  >{{review.user.name}}</router-link></h5>
	    						<p>17 de julio 2017</p>
	    						<star-rating  :rating="review.rating"  read-only :star-size="15" :show-rating="false" active-color="#8D98F1"  ></star-rating>

	    					</div>
	    				</div>
	    			</div>
	    			<div class="col-md-8 container-review-text">
	    				<p>
	    					{{review.review}}
	    				</p>
	    			</div>
	    		</div>
	    	</div>
	    </div>


		<sweet-modal ref="modal" class="container-modal-review">
			<div class="row">
				<div class="col-md-12" v-show="commentFlag">
					<h3>Escribe un comentario</h3>
					<p>Esta es una descripcion para invitar al usuario a que haga una review</p>
				</div>
				<div class="col-md-12" v-show="ratingFlag">
					<h3>Califica de uno a 5</h3>
				</div>
				<div class="col-md-12" v-show="ratingFlag">
					<star-rating v-model="modalReview.rating" @rating-selected ="setRating"  :star-size="30" :show-rating="false" active-color="#ffffff" inactive-color="#adadad"    ></star-rating>
				</div>
				<div class="col-md-12" v-show="commentFlag">
					<textarea  v-model="modalReview.commentText" placeholder="Escribe el comentario aqui"></textarea>
				</div>
				<div class="col-md-12" v-show="commentFlag" >
					<button v-on:click="postReview()" class="btn-white comment-button">Comentar</button>
				</div>
			</div>
		</sweet-modal>

	<sweet-modal ref="modalLogin" class="container-modal-review">
			<div class="row">
				<div class="col-md-12" >
					<h1>{{LoginTitle.toUpperCase()}}</h1>
					<p>Para poder {{LoginTitle}} necesitas ingresar a MeetWork</p>
				</div>   
				<div class="col-md-12 containerInput"  >
					<button v-on:click="LoginMeetWork()"  class="btn-white comment-button">Ingresa a MeetWork</button>

				</div>
			</div>
		</sweet-modal>


		<sweet-modal ref="modalSuscribe" class="container-modal-review">
			<div class="row">
				<div class="col-md-12" >
					<h1>Reserva</h1>
					<p>Por favor confirmanos los siguientes datos</p>
				</div>  

				<div class="col-md-6 containerInput" >
					 
					<div class="row">
						<div class="col-md-3 labelModal">
							Fecha
						</div>
						<div class="col-md-9">
							<date-picker :date="date"  :limit="limit"   :option="option" ></date-picker>
						</div>  
					</div>
				</div>

				<div class="col-md-6 containerInput" >
					<div class="row">
						<div class="col-md-3 labelModal">
							Hora
						</div>
						<div class="col-md-9 dropDowwnModal">
							 <v-select  label="name" :options="optionHours" v-model="selectHour"></v-select>

						</div>  
					</div>

				</div>
				<div class="col-md-12 containerInput"  >
				<div class="row">
					<div class="col-md-6 labelModal">
						Cuantas personas asistirán
					</div>
					<div class="col-md-5">

						 <NumberInputSpinner
						    :min="0"
						    :max="10"

						    :integerOnly="true"
						    v-model="modalVars.numberPersons"
						  />

					</div>
				</div>
				</div>
	
				<div class="col-md-12 containerInput"  >
					<button v-on:click="postReserv()" class="btn-white comment-button">Reservar</button>
				</div>
			</div>
		</sweet-modal>


    </div>
</template>

<script>
import Mapbox from 'mapbox-gl-vue';
import StarRating from 'vue-star-rating'
import { SweetModal, SweetModalTab } from 'sweet-modal-vue'
import myDatepicker from 'vue-datepicker'
    import NumberInputSpinner from 'vue-number-input-spinner'
import helpers from './helpers';
import Lightbox from 'vue-image-lightbox'
require('vue-image-lightbox/dist/vue-image-lightbox.min.css')


    export default {
        data: function () {
            return {

            	images : [] ,
                site: [],
                selectHour:0,
                
		          options : {
		            closeText : 'X'
		          },
                rating:3,
                        LoginTitle:"",

                modalReview:{
                	rating:0,
                	commentText:""
                },
                commentFlag:false,
                ratingFlag:true,
                reviewCount:{
                	count:0,
                	avg:0
                },
                modalVars:{
                	numberPersons:1
                },
                selectedSpace:null,
                optionHours:[
                	"00",
                	"01",
                	"02",
                	"03",
                	"04",
                	"05",
                	"06",
                	"07",
                	"08",
                	"09",
                	"11",
                	"12",
                	"13",
                	"14",
                	"15",
                	"16",
                	"17",
                	"18",
                	"19",
                	"20",
                	"21",
                	"22",
                	"23",
                	"24",


                ],
                
      startTime: {
        time: '2018-02-27'
      },
      endtime: {
        time: ''
		      },
		date: {
		  time: '' // string
		},
      option: {

        type: 'day',
        week: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sa', 'Dom'],
        month: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        format: 'YYYY-MM-DD',
        placeholder: 'Cuando?',
        color: {
          header: '#5A6DBF',
        },
        buttons: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        },
        overlayOpacity: 0.5, // 0.5 as default
        dismissible: true // as true as default
      },


      
      limit: [
      {
        type: 'fromto',
        from: '2018-02-20',
      }]

            }
        },
                   mixins: [ helpers ],

        created: function () {

            this.getNotes();
            this.getReviewsCount();
            var d = new Date().toISOString().slice(0,10);
            this.limit=[{ type: 'fromto', from:d}]


        },

        methods: {
        	LoginMeetWork(){

              this.$router.push({name:"login"})

        	},
        	openRating(val){

        		if(this.$root.$data.user){
			    	this.modalReview.rating=val;
	        		this.commentFlag=true
	        		this.ratingFlag=false
				    this.$refs.modal.open();
          		}else{
        			this.LoginTitle="comentar";
		    		this.$refs.modalLogin.open();
          		}
        		
        	},
        	clearReview(){
        		this.modalReview.rating=0
        		this.modalReview.commentText=""
        		this.commentFlag=false
        		this.ratingFlag=true
        	},
        	setRating(rating){
        		this.modalReview.rating=rating;
        		this.ratingFlag=false;
        		this.commentFlag=true;
        	},
        	showWorkHere(){
        		if(this.$root.$data.user){
			    	this.$refs.modalSuscribe.open();
          		}else{
        			this.LoginTitle="reservar";
		    		this.$refs.modalLogin.open();
          		}

        	},
        	hideWorkHere(){
			    this.$refs.modalSuscribe.close();
        	},
        	 show () {

        	 	if(this.$root.$data.user){
			    	this.$refs.modal.open();
          		}else{
        			this.LoginTitle="comentar";
		    		this.$refs.modalLogin.open();
          		}
			    
			  },
			  hide () {
			    this.$refs.modal.close();
			  },
			  formatReviewCount(data){
			  	this.reviewCount=data
			  	this.rating=parseFloat(data.avg)
			  },
            getReviewsCount: function () {
                axios.get('/getReviewsCount/'+this.$route.params.id)
                    .then(response => this.formatReviewCount(response.data.data))
                    .catch(error => console.log(error.response.data));
            },
            getNotes: function () {
                axios.get('/siteSystem/'+this.$route.params.id)
                    .then(response => this.formatSite(response.data) )
                    .catch(error => console.log(error.response.data));
            },
            formatSite(data){
            	this.site=data;

            	var images=[];
            	for (var i = data.images.length - 1; i >= 0; i--) {
            		
            		images.push({src:data.images[i].url,caption:data.name,thumb:data.images[i].url})
            	}
            	this.images=images;
            	                    	      this.$refs.lightbox.closeLightBox();

            	console.log(this.images);
            }, 
            successReview(data){
            	this.site.reviews=data.data;
            	this.clearReview()
            	this.hide()
                swal("Enviado", "Comentario publicado con èxito", "success");
                this.getReviewsCount();
            },
            failReview(data){
            	this.clearReview()
            	this.hide()
                swal("Error", "No se ha publicado el Comentario", "error");

            },
            postReview: function () {
                let data = this.modalReview;
 
                axios.post('/review/'+this.$route.params.id, data) 
                    .then(response => this.successReview(response.data))
                    .catch(error => this.failReview(error));
            },

            postReserv:function(){
            	if(!this.date.time){
	                swal("Error", "Ingresa una fecha de reserva", "error");

            		return false;
            	}

	         	if(!this.modalVars.numberPersons){
	                swal("Error", "Ingresa un numero de personas", "error");

            		return false;
            	}


	         	if(!this.selectHour){
	                swal("Error", "Ingresa una fecha", "error");

            		return false;
            	}

            	let data={time:this.date.time,numberOfPersons:this.modalVars.numberPersons,hour:this.selectHour}
            	 
                axios.post('/reserv/'+this.$route.params.id, data) 
                    .then(response => this.successReserv(response.data))
                    .catch(error => this.failReserv(error));
            },
            clearReserv(){
            	this.date.time="";
            	this.modalVars.numberPersons=1;
            	this.selectHour=0;
            },
             openGallery(index) {
      this.$refs.lightbox.showImage(index)
    },
            successReserv(data){
			    this.$refs.modalSuscribe.close();

		    	this.clearReserv();
		    	if(!!data.error){
               	 	swal("Ya Reservado", data.message, "error");

		    	}else{
                	swal("Reservado", data.data, "success");

		    	}

            },
           failReserv(data){
           	                swal("Error", "No se ha reservado el lugar", "error");
			    	this.$refs.modalSuscribe.close();
			    	this.clearReserv();

            },
            goToParent(){
            	this.$root.$data.fromRoute="individual"
            	javascript:history.back()
            },
            mapLoaded(map){
            	map.addLayer({
			        'id': 'points',
			        
			          "source": "points",
			        'layout': {
			          'icon-image': '{icon}-15',
			          'text-field': '{title}',
			          'text-font': ['Open Sans Semibold', 'Arial Unicode MS Bold'],
			          'text-offset': [0, 0.6],
			          'text-anchor': 'top'
			        },
		        "paint": {
		            "text-size": 12,
		            "icon-color" : "#ff0000"
		        }


	      		});


		        var el = document.createElement('div');
			    el.className = 'marker';
			    el.style.backgroundImage = 'url(/img/iconlocation.png)';
			    el.style.width = 30 + 'px';
			    el.style.height = 85 + 'px';

			   
			    new mapboxgl.Marker()
			        .setLngLat([-58.4389656,  -34.6016045])
			        .addTo(map);
				map.setCenter([-58.4389656,  -34.6016045]);
            }
        }
        ,
	  components: {
	    Mapbox,StarRating,SweetModal,
		SweetModalTab,'date-picker': myDatepicker,NumberInputSpinner,Lightbox
	  }
    }
</script>
