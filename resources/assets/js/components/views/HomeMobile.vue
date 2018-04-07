<template>
    <div class="row">


        <div class=" col-12 col-md-7  site-scroll-container">

            <div class="row filterMap">
                <div class="col-md-6 filder-inputs" v-bind:class="{'is-hidden' : hidden,'is-show' : !hidden  }" >
                    <p>Tipos de espacios</p>

                    <select multiple="multiple" v-model="selectedSpace">
                        <option v-for="spaceOption in optionsSpace" v-bind:value="spaceOption">{{spaceOption.name}}</option>
                    </select>
                
                </div>

                <div class="col-md-6 filder-inputs" v-bind:class="{'is-hidden' : hidden,'is-show' : !hidden }">
                    <p>Servicios ofrecidos</p>

 
                      <select multiple="multiple" v-model="selectedService">
                        <option v-for="serviceOption in optionsService" v-bind:value="serviceOption">{{serviceOption.name}}</option>
                    </select>
                    
                </div>
                <div class="col-md-6 col-6">
                    <button class="btn-purple filterMobile btn-small" v-on:click="hidden = !hidden">Filtros</button>
                </div>

                <div class="col-md-6 col-6">
                    <button class="btn-purple filterMobile btn-small" v-on:click="showMapView()">Mapa</button>
                </div>


            </div>
            <div class="">
                 

                <div class="row">
                    <div class="col-12 col-md-6" v-for="item in list" >
                        <div class="place " v-on:click="popupClickedList(item)"  :id="'sp_' + item.id" >
                             <div class="slick-initialized slick-slider">
                                <div class="slick-list">
                                    <div class="slick-track" >
                                        <div class="slick-slide slick-cloned photo_place" data-index="-1"
                                             v-bind:style="{ 'background-image': 'url(' + formatGallerySite(item.images[0].url,item.id) + ')' }"
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
                        </div>
                    </div>
                    <infinite-loading @infinite="infiniteHandler"></infinite-loading>

                </div>
            </div>
        </div>
      
        <loading
            :show="show"
            :label="label">
        </loading>


        <sweet-modal ref="modalNoSite" class="container-modal-review">
            <div class="row" >
                <div class="col-md-12">
                    <div class=" login-container profile-edit-container">
                        <h1 class="title">¡Eres el primero en esta zona!</h1>


                        <h3 class="title">Juntos vamos a encontrar el mejor espacio de trabajo, nos contactaremos contigo en unos minutos</h3>
                        <div class="loading-container" v-if="showRecomendationLoading">
                            <i class="fa fa-cog fa-spin"></i>  Enviando...

                        </div>

                        <form method="post" v-if="!showRecomendationLoading" >
                            <div class="form-group">
                                <input required="required" type="text" v-model="user.name"  id="name"  placeholder="Ingresa tu Nombre" class="form-control"> <!---->
                            </div>

                            <div class="form-group">
                                <input required="required" type="email" v-model="user.email"  id="email"  placeholder="Ingresa tu Correo Electrónico" class="form-control"> <!---->
                            </div>


                            <div class="form-group">
                                <input required="required" type="text" v-model="user.cel"  id="cel"  placeholder="Ingresa tu celular" class="form-control"> <!---->
                            </div>

                            <div class="form-group container-button-login">
                                <button type="button" v-on:click="submitRecomendation" class="btn-white">Completar Reserva</button>


                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </sweet-modal>


        <sweet-modal ref="modalUserRegister" class="container-modal-review">
            <div class="row" >
                <div class="col-md-12">
                    <div class=" login-container profile-edit-container">

                        <h3 class="title text-center">Mientras esperas, conoce donde está trabajando nuestra comunidad</h3>
                        <div class="form-group container-button-login">
                            <button type="button" v-on:click="hideModalUserRegister" class="btn-white">Entendido</button>

                        </div>
                    </div>
                </div>

            </div>
        </sweet-modal>

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
    import { SweetModal, SweetModalTab } from 'sweet-modal-vue'
import { Carousel, Slide } from 'vue-carousel';

            export default {
                data() {
                       return {
                        temporalSite: null,
                        list: [],
                        paginator: 1,
                        showMap: false,
                        tempMapPlaces: [],
                        custommap: null,
                        lastPlaceAdd: 0,
                        showMapFlag:true,
                        latUser: 0,
                        user: {
                            name: "",
                            id: 0,
                            cel: ""
                        },
                        defaultLat:-34.5892671959861,
                        defaultLon:-58.43098330510253,
                        lonUser: 0,
                        noData: 0,
                        firstLoad: 0,
                        hidden: true,
                        showRecomendationLoading: false,
                        selectedSpace: [],
                        selectedService: [],
                        optionsSpace: [],
                        optionsService: [],
                        spaceArray: [],
                        serviceArray: [],
                        clearMapFlag: false,
                        show: false,
                        fly: false,
                        paintInLocationInMap: false,
                        label: 'Cargando Espacios...',
                        geolocalize: false
                    };
                },

                watch: {

                    selectedSpace: function (val) {

                        // this.show = true;
                        var ids = [];
                        for (var i = 0; i < val.length; i++) {
                            ids.push(val[i].id);
                        }
                        this.spaceArray = ids
                        
                        this.filterSelectData('space');

                        this.hidden=true;

                    },
                    selectedService: function (val) {
                        //    this.show = true;

                        var ids = [];
                        for (var i = 0; i < val.length; i++) {
                            ids.push(val[i].id);
                        }
                        this.serviceArray = ids
                        
                        this.filterSelectData('service');
                        this.hidden=true;

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


                    this.latUser = Vue.localStorage.get('lat');
                    this.lonUser = Vue.localStorage.get('lon');
                        console.log("fefault lat"+this.latUser);

                    this.getSitesJson(this.paginator, 1);
                    
                    this.getFilters();
                    
                    if(this.isMobile().any()){
                        this.showMapFlag=false;
                    }else{
                     }



                }
                ,
                methods: {
                    filterCat(type, idArray, object) {

                        var result = null;
                        result = object.map(function (objectIndi) {
                            var selection = null;

                            if (type == "service") {

                                selection = objectIndi.services.map(function (i) {
                                    if (idArray.indexOf(i.id) != -1) {
                                        return objectIndi;
                                    }
                                });

                            } else {

                                selection = objectIndi.spaces.map(function (i) {
                                    if (idArray.indexOf(i.id) != -1) {
                                        return objectIndi;
                                    }

                                });

                            }
                            var resultselection;
                            for (var o = 0; o < selection.length; o++) {
                                if (!!selection[o]) {
                                    resultselection = selection[o];
                                }
                            }

                            if (!!resultselection) {
                                return resultselection;
                            }
                        });
                        var finalResult = [];

                        for (var i = 0; i < result.length; i++) {
                            if (!!result[i]) {
                                finalResult.push(result[i]);
                            }
                        }
                        if (!!finalResult) {
                            return finalResult;
                        }
                    },
                    filterSelectData(type){
                        var resultFilter = this.filterCat(type, this.spaceArray, this.list);
                        this.clearMapFlag = true;
                        var _this = this;

                        if (resultFilter.length > 0) {
                            this.temporalSites = this.list;

                            this.clearData();
                            setTimeout(function () {
                                _this.formatSites({data: resultFilter}, 1);

                            }, 400);

                        } else {

                            this.clearData();
                            setTimeout(function () {
                                _this.formatSites({data: _this.temporalSites}, 1);
                                this.temporalSites = [];

                            }, 400);
                        }
                    },
                     closeOnBoarding(){
                                              Vue.localStorage.set('onboarding', "ok")

                        this.$refs.modalOnBoarding.close();
                    },
                    submitRecomendation(e) {
                        this.showRecomendationLoading = true;
                        axios.post('submitRecomendation', {lat: this.latUser, lon: this.lonUser, user: this.user})
                                .then(response => this.succesRecomenation(response.data))
                                .catch(error => console.log(error));
                    },
                    succesRecomenation() {

                        if (!this.$root.$data.user) {
                            Vue.localStorage.set('tempUser', JSON.stringify(this.user))
                            window.location = "/register";
                            // this.$router.replace({name: 'register'})
                        } else {

                            this.latUser = this.defaultLat;
                            this.lonUser = this.defaultLon;
                            this.getSites(this.paginator, 1);
                            this.fly = true;
                        }

                        this.$refs.modalNoSite.close();
                        this.$refs.modalUserRegister.open();

                        this.showRecomendationLoading = false;


                    },
                    hideModalUserRegister() {
                        this.$refs.modalUserRegister.close();

                    },
                    noSite() {
                        this.$refs.modalNoSite.open();

                    },
                    sourcedataMap(map) {
                        /*	console.log("sourcedataMap");
                         this.show=true;
                         */
                    },
                    showMapView() {
                        this.$router.push({name: 'map'})

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
                        console.log(map);
                    },
                    getFilters: function (page, $state) {
                        axios.get('/getFilters')
                                .then(response => this.formatFilters(response.data, $state))
                                .catch(error => console.log(error));
                    },
                    formatFilters(data) {
                        this.optionsSpace = data.spaces;
                        this.optionsService = data.services;
                        console.log(data);
                    },
                    
                    getSitesJson(page, $state) {

                        axios.get('https://s3.amazonaws.com/staticthigs/sites.json')
                                .then(response => this.formatSites(response.data, $state))
                                .catch(function (thrown) {
                                    console.log(thrown);
                                });

                    },
                    getSites: function (page, $state) {

                        axios.get('/sites?page=' + page + '&services=' + this.serviceArray + '&selectedSpace=' + this.spaceArray)
                                .then(response => this.formatSites(response.data, $state))
                                .catch(error => console.log(error));
                    },
                    scrollIntoView(eleID, name) {
                        console.log("cuando hace click " + eleID)
                        this.$router.push({name: 'view_site', params: {id: eleID, name: name}})

                    },
                    formatSites(data, $state) {




                        this.clearMapFlag = false;
                        if (data.next_page_url == null) {
                            this.noData = 1;
                        }

                        var data = data.data;
                        if (data.length == 0) {
                            
                            this.noData = 1;

                            if (this.firstLoad == 0) {
                                if (!this.$root.$data.user) {
                                    this.noSite();
                                } else {
                                    this.latUser = this.defaultLat;
                                    this.lonUser = this.defaultLon;
                                    this.getSites(this.paginator, 1);
                                    this.fly = true;
                                    this.$refs.modalUserRegister.open();
                                }

                                if (!!this.$root.$data.user) {
                                    this.user = this.$root.$data.user;
                                }
                                this.firstLoad = 1;
                            }


                        } else {
                            this.firstLoad = 1;
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
                        console.log("aquii");
                    },
                    paintPlaces(map) {
                        console.log(this.tempMapPlaces);
                        var _this = this;
                        for (var i = 0; i < this.tempMapPlaces.length; i++) {
                            if (this.tempMapPlaces[i].logo.length > 0) {
                                var el = document.createElement('div');


                                el.className = 'marker';
                                el.style.backgroundImage = 'url(' + this.formatImagesLogo(this.tempMapPlaces[i].logo[0].url, "small") + ')';
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


                    },
                    paintMarker(map, data) {
                        var _this=this; 
                        if (!!data) {
                            const popupContent = Vue.extend({
                                template: '<div @click="popupClicked" class="container-popup-map"><img  src="' + this.formatImagesGallery(data.images[0].url, 'uploads', 'thumbs') + '"/><div class="conainer-text-popup"><h3>' + data.name + '</h3><p>' + data.address + '</p></div></div>',
                                methods: {
                                    popupClicked() {
                                        window.open("/site/" + data.id + "/" + _this.string_to_slug(data.name));

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
                                zoom: 13,
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
                    popupClickedList(data) {

                        window.open("/site/" + data.id + "/" + this.string_to_slug(data.name));

                    },
                    getLocationUser(map) {

                        if (!this.paintInLocationInMap) {
                            var _this = this;


                            if (navigator.geolocation)
                            {

                                // Call getCurrentPosition with success and failure callbacks
                                navigator.geolocation.getCurrentPosition(function (position) {
                                    if (!_this.geolocalize) {
                                        _this.clearMap(map);
                                        console.log("aqui  " + Vue.localStorage.get('lat'));


                                        _this.latUser = position.coords.latitude;
                                        _this.lonUser = position.coords.longitude;
                                        if (!!position.coords.latitude) {
                                            Vue.localStorage.set('lat', position.coords.latitude)
                                        }
                                        if (!!position.coords.longitude) {
                                            Vue.localStorage.set('lon', position.coords.longitude)
                                        }
                                        console.log("localizando->" + _this.latUser + _this.lonUser);
                                        _this.getSites(_this.paginator, 1);
                                        _this.paintInLocationInMap = true;

                                        var el = document.createElement('div');
                                        el.className = 'marker';
                                        el.style.backgroundImage = 'url(/img/iconlocation.png)';
                                        el.style.width = 30 + 'px';
                                        el.style.height = 85 + 'px';


                                        new mapboxgl.Marker()
                                                .setLngLat([_this.lonUser, _this.latUser])
                                                .addTo(map);
                                        map.setCenter([_this.lonUser, _this.latUser]);

                                        _this.geolocalize = true;
                                    }


                                }, function () {

                                    if (!_this.geolocalize) {
                                        _this.clearMap(map);


                                        _this.latUser = _this.defaultLat;
                                        _this.lonUser = _this.defaultLon;
                                        Vue.localStorage.set('lat', _this.defaultLat)

                                        Vue.localStorage.set('lon', _this.defaultLon)

                                        console.log("localizando->" + _this.latUser + _this.lonUser);
                                        _this.getSites(_this.paginator, 1);
                                        _this.paintInLocationInMap = true;

                                        var el = document.createElement('div');
                                        el.className = 'marker';
                                        el.style.backgroundImage = 'url(/img/iconlocation.png)';
                                        el.style.width = 30 + 'px';
                                        el.style.height = 85 + 'px';


                                        new mapboxgl.Marker()
                                                .setLngLat([_this.lonUser, _this.latUser])
                                                .addTo(map);
                                        map.setCenter([_this.lonUser, _this.latUser]);

                                        _this.geolocalize = true;
                                    }



                                });
                            } else
                            {
                                alert("Sorry, your browser does not support geolocation services.");
                            }
                        } else {

                        }

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

                            if (!!_this.fly) {
                                if (!!_this.tempMapPlaces) {
                                    var site = _this.tempMapPlaces[_this.tempMapPlaces.length / 2]
                                    if (!!site) {
                                        map.flyTo({
                                            center: [site.lon, site.lat],
                                            zoom: 13,
                                            speed: 0.7,
                                            curve: 1,
                                        });
                                        _this.fly = false;
                                    }

                                }

                            }

                        }, 100);


                        var t = setInterval(function () {
                            _this.getLocationUser(map);

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
                        map.setCenter([this.defaultLat, this.defaultLon]);
                        map.setZoom(13);
                        this.addMapLayers(map);


                    },

                    infiniteHandler($state) {
                        if (this.noData == 0) {
                            this.show = true;

                            this.getSites(this.paginator, $state);
                        }



                    },
                },
                components: {
                    InfiniteLoading, Mapbox, loading, SweetModal, Carousel,
    Slide
                },
            };

</script>