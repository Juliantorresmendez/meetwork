<template>
    <div class="row">


        <div class=" col-12">
           
            <div class="row filterMap filters-mobile" v-bind:class="{'is-hidden' : hidden,'is-show' : !hidden  }">
                <div class="col-6">
                    <button class="btn-purple filterMobile"  v-bind:class="{'selected-filter' : space  }" v-on:click="showSpacesDropdown">Espacios</button>
                </div>
                  <div class="col-6">
                    <button  class="btn-purple filterMobile" v-bind:class="{'selected-filter' : service  }" v-on:click="showServicesDropdown">Servicios</button>
                </div>
                
                
                
                <div class="col-md-6 filder-inputs"  v-show="space && !service" >
                    <p>Tipos de espacios</p>
                    
                    <select multiple="multiple" v-model="selectedSpace">
                        <option v-for="spaceOption in optionsSpace" v-bind:value="spaceOption">{{spaceOption.name}}</option>
                    </select>

                </div>

                <div class="col-md-6 filder-inputs" v-show="service && !space">
                    <p>Servicios ofrecidos</p>


                    
                      <select multiple="multiple" v-model="selectedService">
                        <option v-for="serviceOption in optionsService" v-bind:value="serviceOption">{{serviceOption.name}}</option>
                    </select>
                    
                </div>
                


            </div>
             <div class="row filterMap filters-mobile">
            
            <div class="col-md-6 col-6">
                    <button class="btn-purple filterMobile btn-small" v-on:click="hidden = !hidden">Filtros</button>
                </div>

                <div class="col-md-6 col-6">

                    <button class="btn-purple filterMobile btn-small" v-on:click="viewListSite()">Listado</button>


                </div>
   </div>
        </div>
        <div class="col-12  nopadding container-map full-map">
            <mapbox  

                access-token="pk.eyJ1IjoibG9yZG1hY3VzIiwiYSI6ImNqZGdub3B4eDBtb3QyeG45Y3YwMDZnd3YifQ.0c8uzpkL-Ib5UBj8cVBUQA"
                :map-options="{
                style: 'mapbox://styles/mapbox/light-v9',
                zoom: 13,
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
                @map-zoomend="dragMap"

                @map-data="sourcedataMap"
                @map-click="mapClicked"


                ></mapbox> 
        </div>
        <loading
            :show="show"
            :label="label">
        </loading>




        <sweet-modal  ref="modalOnBoarding" class="container-on-boarding"  width="450">

            <div class="On-boarding-container">



                <carousel  :per-page="1"  :navigationEnabled="true" navigationNextLabel="Siguiente >" navigationPrevLabel="< Anterior"  paginationActiveColor="#576eae" paginationColor="#d5dbeb">
                    <slide>
                        <div class="container-slide">
                            <img src="/slides/step-1.png"/>
                            <p class="title">¡Bienvenido!</p>
                            <p class="description">A partir de este momento vas a tener disponible un espacio de trabajo para desarrollar tus ideas desde cualquier lugar de la ciudad.</p>


                        </div>

                    </slide>
                    <slide>
                        <div class="container-slide">

                            <img src="/slides/step-2.png"/>
                            <p class="title">Espacios para tus ideas</p>
                            <p class="description">Puedes reservar un espacio de trabajo en el café, bar o restaurante más cercano a tu ubicación. Con nosotros tienes una oficina donde quieras.</p>
                        </div>

                    </slide>

                    <slide>
                        <div class="container-slide">

                            <img src="/slides/step-3.png"/>
                            <p class="title">Haz de la ciudad tu oficina </p>
                            <p class="description">Además vas a poder crear eventos, reservar salas de reuniones y espacios de estudio en donde tu inspiración se va a renovar todos los días.</p>
                        </div>
                    </slide>


                    <slide>
                        <div class="container-slide">

                            <img src="/slides/step-4.png"/>
                            <button class="btn-green start-already-button"  @click="closeOnBoarding">¡Empecemos ya!</button>
                        </div>
                    </slide>
                </carousel>


            </div>

        </sweet-modal>





    </div>

</template>


<script>
    import Mapbox from 'mapbox-gl-vue';
    import InfiniteLoading from 'vue-infinite-loading';
    import loading from 'vue-full-loading'
            import helpers from './helpers';
    import { Carousel, Slide } from 'vue-carousel';
    import { SweetModal, SweetModalTab } from 'sweet-modal-vue'

            export default {
                data() {
                    return {
                        space:true,
                        service:false,
                        temporalSite: null,
                        list: [],
                        paginator: 1,
                        showMap: false,
                        tempMapPlaces: [],
                        custommap: null,
                        lastPlaceAdd: 0,
                        latUser: 0,
                        lonUser: 0,
                        noData: 0,
                        hidden: true,
                        selectedSpace: [],
                        selectedService: [],
                        optionsSpace: [],
                        optionsService: [],
                        spaceArray: [],
                        serviceArray: [],
                        clearMapFlag: false,
                        show: false,
                        paintInLocationInMap: false,
                        label: 'Cargando Espacios...',
                        defaultLat: -34.5892671959861,
                        defaultLon: -58.43098330510253,
                        paintUserLocationFlag: false,
                        aleradyLocatedUser: false,
                        flyselected:false
                    };
                },

                watch: {

                    selectedSpace: function (val) {
                        this.flyselected=true;
                        this.show = true;
                        this.hidden=true;
                        var ids = [];
                        for (var i = 0; i < val.length; i++) {
                            ids.push(val[i].id);
                        }
                        this.spaceArray = ids
                        this.clearData();
                        this.clearMapFlag = true;
                        this.getSites(this.paginator, 1);

                    },
                    selectedService: function (val) {
                        this.show = true;
                        this.flyselected=true;
                        this.hidden=true;

                        var ids = [];
                        for (var i = 0; i < val.length; i++) {
                            ids.push(val[i].id);
                        }
                        this.serviceArray = ids

                        this.clearData();
                        this.clearMapFlag = true;
                        this.getSites(this.paginator, 1);

                    }
                },
                mixins: [helpers],

                created: function () {
                    setTimeout(() => {
                        this.show = true;
                            
                         if(!Vue.localStorage.get('onboarding')){
                            this.$refs.modalOnBoarding.open();
                         }

                    }, 100)





                    if (navigator && navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(this.getlocationDemo, this.errorCallback, {enableHighAccuracy: true});
                    } else {
                        console.log('Geolocation is not supported');
                    }


                    this.latUser = Vue.localStorage.get('lat');
                    this.lonUser = Vue.localStorage.get('lon');

                    this.getSites(this.paginator, 1);

                    this.getFilters();


                }
                ,
                methods: {
                    showSpacesDropdown(){
                        
                        console.log("aqui espacios");
                      this.space=true;  
                       this.service=false;
                    },
                    showServicesDropdown(){
                         this.space=false; 
                                                console.log("aqui servicos");

                        this.service=true;
                    },
                    closeOnBoarding() {
                        Vue.localStorage.set('onboarding', "ok")

                        this.$refs.modalOnBoarding.close();
                    },
                    errorCallback(error) {
                        this.latUser = this.defaultLat;
                        this.lonUser = this.defaultLon;

                        if (!!position.coords.latitude) {
                            Vue.localStorage.set('lat', position.coords.latitude)
                        }
                        if (!!position.coords.longitude) {
                            Vue.localStorage.set('lon', position.coords.longitude)
                        }
                    },
                    getlocationDemo(position) {

                        this.latUser = position.coords.latitude;
                        this.lonUser = position.coords.longitude;

                        if (!!position.coords.latitude) {
                            Vue.localStorage.set('lat', position.coords.latitude)
                        }
                        if (!!position.coords.longitude) {
                            Vue.localStorage.set('lon', position.coords.longitude)
                        }

                    },
                    sourcedataMap(map) {
                        /*	console.log("sourcedataMap");
                         this.show=true;
                         */
                    },
                    viewListSite() {
                        this.$router.push({name: 'mobile'})

                    },
                    dragMap(map) {
                        var center = map.getCenter();
                        if (!!center.lat) {
                            Vue.localStorage.set('lat', center.lat)
                        }
                        if (!!center.lon) {
                            Vue.localStorage.set('lon', center.lon)
                        }
                        this.latUser = center.lat;
                        this.lonUser = center.lng;
                        this.getSites(this.paginator, 1);

                        console.log("termina e cargar");
                    },
                    mapGeolocate: function (map) {

                    },
                    getFilters: function (page, $state) {
                        axios.get('/getFilters')
                                .then(response => this.formatFilters(response.data, $state))
                                .catch(error => console.log(error));
                    },
                    formatFilters(data) {
                        this.optionsSpace = data.spaces;
                        this.optionsService = data.services;

                    },
                    getSites: function (page, $state) {
                        console.log("llamando sites");
                        axios.get('/sites?page=' + page + '&lat=' + this.latUser + '&lon=' + this.lonUser + '&services=' + this.serviceArray + '&selectedSpace=' + this.spaceArray)
                                .then(response => this.formatSites(response.data, $state))
                                .catch(error => console.log(error));
                    },
                    scrollIntoView(eleID, name) {
                        console.log("cuando hace click " + eleID)
                        this.$router.push({name: 'view_site', params: {id: eleID, name: name}})

                    },
                    formatSites(data, $state) {
                        this.clearMapFlag = false;
                        console.log(data.next_page_url);
                        if (data.next_page_url == null) {
                            this.noData = 1;
                        }

                        var data = data.data;
                        if (data.length == 0) {
                            this.noData = 1;

                        } else {
                            var sites = [];
                            for (var i = 0; i < data.length; i++) {

                                var site = data[i];
                                site.type = "Feature";
                                site.geometry = {"type": "Point", "coordinates": [data[i].lon, data[i].lat]};
                                site.properties = {"title": data[i].name, "icon": "marker"};
                                sites.push(site);

                            }
                            this.paginator += 1;

                            this.list = this.list.concat(sites);

                            this.tempMapPlaces = sites;
                        }

                        if ($state != 1) {
                            $state.loaded();
                        }
                        this.show = false;




                    },

                    searchNewPlaces() {
                        var newData = false
                        if (this.tempMapPlaces.length > 0) {
                            if (this.tempMapPlaces[this.tempMapPlaces.length - 1].id != this.lastPlaceAdd) {
                                newData = true;
                            }
                        }


                        return newData;

                    },
                    goToSite() {


                    },
                    paintPlaces(map) {

                        var _this = this;
                        var latLast=null;
                        var lonlast=null;
                        for (var i = 0; i < this.tempMapPlaces.length; i++) {
                            if (this.tempMapPlaces[i].logo.length > 0) {
                                var el = document.createElement('div');

                                 latLast=this.tempMapPlaces[i].lat;
                                 lonlast=this.tempMapPlaces[i].lon;
                                el.className = 'marker';
                                el.style.backgroundImage = 'url(' + this.fotmatProfileLogoMap(this.tempMapPlaces[i].logo, this.tempMapPlaces[i].id) + ')';
                                el.style.width = 30 + 'px';
                                el.style.height = 30 + 'px';
                                el.id = this.tempMapPlaces[i].id;
                                el.name = JSON.stringify(this.tempMapPlaces[i]);

                                el.addEventListener('click', function () {
                                    var site = JSON.parse(this.name);
                                    //_this.scrollIntoView(id,name);

                                    //console.log(map);
                                    _this.temporalSite = site;





                                });

                                new mapboxgl.Marker(el)
                                        .setLngLat([this.tempMapPlaces[i].lon, this.tempMapPlaces[i].lat])
                                        .addTo(map);
                            }
                            
                            
                           

                        }
                        
                        
                         

                        this.lastPlaceAdd = this.tempMapPlaces[this.tempMapPlaces.length - 1].id;
                        this.lastPlaceAddData = this.tempMapPlaces[this.tempMapPlaces.length - 1];


                         if(this.flyselected){
                             map.flyTo({
                                center: [this.lastPlaceAddData.lon,this.lastPlaceAddData.lat],
                                zoom: 15,
                                speed: 1,
                                curve: 1,
                            });
                            this.flyselected=false;
                         }

                    },
                    paintMarker(map, data) {
                        if (!!data) {
                            const popupContent = Vue.extend({
                                template: '<div @click="popupClicked" class="container-popup-map"><img  src="' + this.formatGallerySite(data.images[0].url, data.id) + '"/><div class="conainer-text-popup"><h3>' + data.name + '</h3><p>' + data.address + '</p></div></div>',
                                methods: {
                                    popupClicked() {
                                        window.open("/site/" + data.id + "/" + data.name);

                                    },
                                }
                            });

                            // Populate the popup and set its coordinates
                            // based on the feature found.
                            const popup = new mapboxgl.Popup()
                                    .setLngLat([data.lon, data.lat])
                                    .setHTML('<div id="vue-popup-content"></div>')
                                    .addTo(map);

                            new popupContent().$mount('#vue-popup-content');
                            map.flyTo({
                                center: [data.lon, data.lat],
                                zoom: 15,
                                speed: 0.2,
                                curve: 1,
                            });



                            this.temporalSite = null;
                        }

                    },
                    clearData() {
                        this.list = [];

                        this.paginator = 1
                        this.tempMapPlaces = []
                        this.custommap = null
                        this.lastPlaceAdd = 0
                        this.noData = 0
                    },
                    clearMap(map) {
                        this.clearData()
                        this.latUser = 0
                        this.lonUser = 0
                        // map.removeLayer("points");
                        this.mapLoaded(map);

                    },
                    mapClicked(map, e) {

                        this.paintMarker(map, this.temporalSite);
                    },
                    getLocationUser(map) {
                        if (!this.paintInLocationInMap) {

                            var _this = this;
                            if (navigator.geolocation)
                            {

                                if (!this.paintUserLocationFlag) {
                                    // this.paintUserLocation(map);
                                }
                                _this.aleradyLocatedUser = true;

                                // Call getCurrentPosition with success and failure callbacks
                                navigator.geolocation.getCurrentPosition(function (position) {
                                    _this.clearMap(map);
                                    _this.latUser = position.coords.latitude;
                                    _this.lonUser = position.coords.longitude;
                                    if (!!position.coords.latitude) {
                                        Vue.localStorage.set('lat', position.coords.latitude)
                                    }
                                    if (!!position.coords.longitude) {
                                        Vue.localStorage.set('lon', position.coords.longitude)
                                    }

                                    _this.getSites(_this.paginator, 1);
                                    _this.paintInLocationInMap = true;

                                    _this.paintUserLocation(map);

                                    console.log("geolocalizado-->" + _this.aleradyLocatedUser);


                                }, function () {
                                    _this.latUser = _this.defaultLat;
                                    _this.lonUser = _this.defaultLon;

                                    Vue.localStorage.set('lat', _this.latUser)

                                    Vue.localStorage.set('lon', _this.lonUser)
                                    _this.paintUserLocation(map);

                                });
                            } else
                            {
                                alert("Sorry, your browser does not support geolocation services.");
                            }
                        } else {

                        }

                    },

                    setCustomProperties() {
                        var elements = document.getElementsByClassName("mapboxgl-marker");
                        for (var i = 0; i < elements.length; i++) {
                            if (elements[i].style.backgroundImage == 'url("/img/pin_user.png")') {
                                elements[i].classList.add("pin-location-user");

                            }

                        }
                    },
                    paintUserLocation(map) {

                        var el = document.createElement('div');
                        el.className = 'marker';
                        el.style.backgroundImage = 'url(/img/pin_user.png)';
                        el.style.backgroundSize = '100%';
                        //el.style.backgroundRepeat = 'no-repeat';
                        el.style.width = 40 + 'px';
                        el.style.class = 'localization-icon';
                        el.style.id = 'localization-icon';
                        el.style.height = 40 + 'px';

                        new mapboxgl.Marker(el)
                                .setLngLat([this.lonUser, this.latUser])
                                .addTo(map);

                        map.flyTo({
                            center: [this.lonUser, this.latUser],
                            zoom: 15,
                            speed: 1,
                            curve: 1,
                        });


                        this.paintUserLocationFlag = true;
                        this.setCustomProperties();

                    },
                    removeElementsByClass(className) {
                        var elements = document.getElementsByClassName(className);
                        while (elements.length > 0) {
                            elements[0].parentNode.removeChild(elements[0]);
                        }
                    },
                    addMapLayers(map) {

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
                                "icon-color": "#ff0000"
                            }

                        });

                        //this.paintPlaces(map);

                        var _this = this

                        var t = setInterval(function () {
                            //   _this.paintMarker(map);
                            if (_this.clearMapFlag) {
                                //       map.removeLayer("points");
                                _this.removeElementsByClass("marker");


                            }

                        }, 100);


                        var t = setInterval(function () {
                            if (!_this.aleradyLocatedUser) {
                                _this.getLocationUser(map);
                            }

                            if (_this.searchNewPlaces()) {
                                this.show = true;

                                _this.paintPlaces(map);
                            }

                        }, 300);


                        navigator.geolocation.watchPosition(function (position) {
                            //console.log(position);
                        })





                    },
                    mapLoaded(map) {
                        map.setCenter([this.defaultLon, this.defaultLat]);
                        map.setZoom(15);
                        this.addMapLayers(map);


                    },

                    infiniteHandler($state) {
                        if (this.noData == 0) {
                            this.show = true;

                            this.getSites(this.paginator, $state);
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
                    InfiniteLoading, Mapbox, loading, Carousel,
                    Slide, SweetModal
                },
            };


</script>