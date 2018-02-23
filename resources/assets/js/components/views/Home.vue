<template>
  <div class="row">
	 

  	<div class=" col-12 col-md-7">

		<div class="row filterMap">
			<div class="col-md-6">
				<p>Tipos de espacios</p>
				<v-select multiple label="name" :options="optionsSpace" v-model="selectedSpace"></v-select>
			</div>

			<div class="col-md-6">
				<p>Servicios ofrecidos</p>

				<v-select multiple label="name"  :options="optionsService" v-model="selectedService"></v-select>

			</div>

			 
		</div>

  		<div class="row">
  		  	<div class="headslider col-12">
			    <div class="wrapper">
				    <h3 class="subtitle">Meetwork</h3>
				    <h2 class="title">¿Desde donde quieres trabajar hoy?</h2>
				</div>
		    </div>
  		</div>

  		<div class="row">
  			<div class="col-12 col-md-6" v-for="item in list" >
  				<router-link class="place "  :key="$route.fullPath"  :to="{name: 'view_site', params: { id: item.id,name:string_to_slug(item.name) }}"   :id="'sp_' + item.id" >
					   <div class="slick-initialized slick-slider">
					      <div class="slick-list">
					         <div class="slick-track" >
					            <div class="slick-slide slick-cloned photo_place" data-index="-1"
					            v-bind:style="{ 'background-image': 'url(' + formatImagesGallery(item.images[0].url,'uploads','thumbs') + ')' }"
					             ></div>
				
					         </div>
					      </div>
					     
					   </div>
					   <div class="header">
					      <div class="place-info">
					         <h3>{{item.name}}</h3>
					         <h5>Buenos aires - {{item.address}}  </h5>
					      </div>
					   </div>
					   <div class="services">
					      <div class="service_label " v-for="service in item.services">
					         <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAMCAYAAAHKRddyAAAAAXNSR0IArs4c6QAAAZNJREFUKBWFUj1LA0EQnbkEm4CVpBELsTJKolZ2+gOCINp6SUCImlwSFVRIo6KIVpIELJQQU1iJ2NnHWuTuVNBg50dlZSrJ7Ti3YcNFAm6xb+fN25m3NwfAK5Y2311srbhh76uzRCQiTO08BRpfzbNAAJclqxvWh56xdt1AM4x6LwDVgj3BQ1ctFd4NY9mHOXKcS0S0ELUDIgeBIE8AIxr6ZtpX9LRJgHCFhEhAs9VSROY0Vc4l3CSL+lTSzUlVImdOCAdOuMQYEN370ZcsF0dN1A2zwf0C3H8PNayRA1MEIs/xt8bbZqssfQohtlCDNxkjbMuyMcMqsdMFNsGlYYXJi0oxvKQ8/Y/yWR6ZLLuYs4d/muKI+agn1/XIHq8R/RuVQqiO0g9RipUv/PJctRS+6XqLyUTWjgpBxzyXIUA8lZ2VOJ6zp4UjyvxpBhWnkDu+8owSlWLkVnEd6E5az5hZffW535tIrNkDMcNe//sndHTuuGA8hgQ0k2yvel4I33lz6tyevyIUCnDG2f48x5OK+4u/Usub7bKVLm8AAAAASUVORK5CYII=" alt="icon">
					         <p>{{service.name}}</p>
					      </div>
					      
					   </div>
					</router-link>
			</div>
			  <infinite-loading @infinite="infiniteHandler"></infinite-loading>

  		</div>
  	</div>
  	<div class="col-12 col-md-5 nopadding container-map">
  		<mapbox  
				access-token="pk.eyJ1IjoibG9yZG1hY3VzIiwiYSI6ImNqZGdub3B4eDBtb3QyeG45Y3YwMDZnd3YifQ.0c8uzpkL-Ib5UBj8cVBUQA"
				:map-options="{
			    	style: 'mapbox://styles/mapbox/light-v9',
			    	zoom: 15,
			    	center: [-58.4389656,  -34.6016045],
			    }"
				:geolocate-control="{
					show: true, 
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
    		@map-geolocate="mapGeolocate"
    		@map-dragend="dragMap"
    		@map-data="sourcedataMap"


		    	></mapbox>
  	</div>
  	<loading
     :show="show"
     :label="label">
 </loading>
  </div>

</template>


<script>
import Mapbox from 'mapbox-gl-vue';
import InfiniteLoading from 'vue-infinite-loading';
import loading from 'vue-full-loading'
import helpers from './helpers';

export default {
  data() {
    return {
      list: [],
       paginator:1,
      tempMapPlaces:[],
      custommap:null,
      lastPlaceAdd:0,
      latUser:0,
      lonUser:0,
      noData:0,
      selectedSpace:[],
      selectedService:[],
	  optionsSpace:[],
	  optionsService:[],
	  spaceArray:[],
	  serviceArray:[],
	  clearMapFlag:false,
	   show: false,
	   paintInLocationInMap:false,
       label: 'Cargando Sitios...'
    };
  } ,

  watch: {

    selectedSpace: function (val) {
    	this.show=true;
    	var ids=[];
    	for (var i = 0; i < val.length; i++) {
    		ids.push(val[i].id);
    	}
    	this.spaceArray=ids
    	this.clearData();
    	this.clearMapFlag=true;
    	this.getSites(this.paginator,1);

    },
    selectedService: function (val) {
    	    	this.show=true;

    	var ids=[];
    	for (var i = 0; i < val.length; i++) {
    		ids.push(val[i].id);
    	}
    	this.serviceArray=ids

    	this.clearData();
    	this.clearMapFlag=true;
    	this.getSites(this.paginator,1);

    }
  },
           mixins: [ helpers ],

	created: function () {
		 setTimeout(() => {
                    this.show = true;
                }, 100)


		 this.latUser=Vue.localStorage.get('lat');
		 this.lonUser=Vue.localStorage.get('lon');
		 if(!this.latUser){
	 			this.getSites(this.paginator,1);
		 }
	    	this.getFilters();
		
	  
	}
	,
  methods: {
  	sourcedataMap(map){
  	/*	console.log("sourcedataMap");
  					this.show=true;
*/
  	},
  	dragMap(map){
  			var center=map.getCenter();
  			if(!!center.lat){
   				Vue.localStorage.set('lat', center.lat)
  			}
  			if(!!center.lon){
            	Vue.localStorage.set('lon', center.lon)
  			}
  		this.latUser=center.lat;
       	this.lonUser=center.lng;
      	this.getSites(this.paginator,1);

  		console.log("termina e cargar");
  	},
  	mapGeolocate:function (map){
  		console.log(map);
  	},
  	getFilters: function (page,$state) {
                axios.get('/getFilters')
                    .then(response =>  this.formatFilters(response.data,$state))
                    .catch(error => console.log(error));
            },
    formatFilters(data){
    	this.optionsSpace=data.spaces;
    	this.optionsService=data.services;
    	console.log(data);
    },
  	getSites: function (page,$state) {

                axios.get('/sites?page='+page+'&lat='+this.latUser+'&lon='+this.lonUser+'&services='+this.serviceArray+'&selectedSpace='+this.spaceArray)
                    .then(response =>  this.formatSites(response.data,$state))
                    .catch(error => console.log(error));
            },
	 scrollIntoView(eleID,name) {
	 	console.log("cuando hace click "+eleID)
      this.$router.push({name: 'view_site', params: { id: eleID,name:name }})

	},
    formatSites(data,$state){
    		this.clearMapFlag=false;
    		console.log(data.next_page_url);
    		if(data.next_page_url==null){
    			this.noData=1;
    		}

    		var data=data.data;
    		if(data.length==0){
    			this.noData=1;
    				
    		}else{
    			var sites=[];
				for (var i = 0; i < data.length; i++) {

					var site=data[i];
					site.type="Feature";
					site.geometry={"type":"Point","coordinates":[data[i].lon, data[i].lat]};
					site.properties={"title":data[i].name,"icon":"marker"};
					sites.push(site);

				}
				this.paginator+=1;
		       
		    	this.list = this.list.concat(sites);

				this.tempMapPlaces=	sites;	
    		}
		         	
		    	if($state!=1){
		 			$state.loaded();
		    	}
			this.show=false;


    	

    },

    searchNewPlaces(){
    	var newData=false
    	if(this.tempMapPlaces.length>0){
    		if(this.tempMapPlaces[this.tempMapPlaces.length-1].id!=this.lastPlaceAdd){
    		newData=true;
    		}
    	}
    	

    	return newData;
    	
    },
    paintPlaces(map){
    	console.log(this.tempMapPlaces);
    	var _this=this;
		for (var i = 0; i < this.tempMapPlaces.length; i++) {
			if(this.tempMapPlaces[i].logo.length>0){
				var el = document.createElement('div');


			    el.className = 'marker';
			    el.style.backgroundImage = 'url('+this.formatImagesLogo(this.tempMapPlaces[i].logo[0].url,"small")+')';
			    el.style.width = 30 + 'px';
			    el.style.height = 30 + 'px';
			    var id=this.tempMapPlaces[i].id;
			    var name=this.tempMapPlaces[i].name;
			    el.addEventListener('click', function() {
			        _this.scrollIntoView(id,name);
			    });

			    new mapboxgl.Marker(el)
			        .setLngLat([this.tempMapPlaces[i].lon, this.tempMapPlaces[i].lat])
			        .addTo(map);
				}
	    		
	    	}

	    	this.lastPlaceAdd=this.tempMapPlaces[this.tempMapPlaces.length-1].id;
	    	

    },
    clearData(){
		this.list=[];

		this.paginator=1
		this.tempMapPlaces=[]
		this.custommap=null
		this.lastPlaceAdd=0
		this.noData=0
    },
    clearMap(map){
    	this.clearData()
			this.latUser=0
		this.lonUser=0
		map.removeLayer("points");
		this.mapLoaded(map);
		
    },
    getLocationUser(map){

    	if(!this.paintInLocationInMap){
			var _this=this;
		   if( navigator.geolocation )
	        {
	           // Call getCurrentPosition with success and failure callbacks
	           navigator.geolocation.getCurrentPosition( function (position){
				_this.clearMap(map);
	           	_this.latUser=position.coords.latitude;
	           	_this.lonUser=position.coords.longitude;
	           	if(!!position.coords.latitude){
	           		Vue.localStorage.set('lat', position.coords.latitude)
	           	}
	           	if(!!position.coords.longitude){
            		Vue.localStorage.set('lon', position.coords.longitude)
	           	}

   	      		_this.getSites(_this.paginator,1);
   	      		_this.paintInLocationInMap=true;

				var el = document.createElement('div');
			    el.className = 'marker';
			    el.style.backgroundImage = 'url(/img/iconlocation.png)';
			    el.style.width = 30 + 'px';
			    el.style.height = 85 + 'px';

			   
			    new mapboxgl.Marker()
			        .setLngLat([_this.lonUser,  _this.latUser])
			        .addTo(map);
				map.setCenter([_this.lonUser,  _this.latUser]);



	           }, function (){

	           } );
	        }
	        else
	        {
	           alert("Sorry, your browser does not support geolocation services.");
	        }
    	}else{
    		
    	}
    	
    },
     removeElementsByClass(className){
    var elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
},
    addMapLayers(map){

var self = this;

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

	//this.paintPlaces(map);

	var _this=this

	var t=setInterval(function (){

		if(_this.clearMapFlag){
			map.removeLayer("points");
			_this.removeElementsByClass("marker");


		}
 
	},100);


	var t=setInterval(function (){
				_this.getLocationUser(map);
 
		if(_this.searchNewPlaces()){
			this.show=true;

			_this.paintPlaces(map);
		}

	},300);


navigator.geolocation.watchPosition(function(position) {
  //console.log(position);
})



	
	      
    },
	mapLoaded(map) {
		map.setCenter([-58.4389656,  -34.6016045]);
		map.setZoom(15);
		this.addMapLayers(map);


    },

    infiniteHandler($state) {
    	if(this.noData==0){
    					this.show=true;

      		this.getSites(this.paginator,$state);
    	}




/*setTimeout(() => {
        const temp = [];
        for (let i = this.list.length + 1; i <= this.list.length + 20; i++) {
          temp.push(i);
        }
        this.list = this.list.concat(temp);
        $state.loaded();
      }, 1000);*/


    },
  },
  components: {
    InfiniteLoading,Mapbox,loading
  },
};

</script>