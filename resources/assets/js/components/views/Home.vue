<template>
    <div class="row">


        <div class=" col-12 col-md-7  site-scroll-container">

            <div class="row filterMap">
                <div class="col-md-6 filder-inputs espacio-container-search" v-bind:class="{'is-hidden' : hidden,'is-show' : !hidden  }" >
                    <p>Tipos de espacios</p>
                    <v-select multiple label="name" :options="optionsSpace" v-model="selectedSpace"></v-select>
                </div>

                <div class="col-md-6 filder-inputs servicios-container-search" v-bind:class="{'is-hidden' : hidden,'is-show' : !hidden }">
                    <p>Servicios ofrecidos</p>

                    <v-select multiple label="name"  :options="optionsService" v-model="selectedService"></v-select>

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
                                    <img v-bind:src="service.icon" alt="icon">
                                    <p>{{service.name}}</p>
                                </div>

                            </div>  
                        </div>
                    </div>
                    <infinite-loading @infinite="infiniteHandler"></infinite-loading>

                </div>
            </div>
        </div>
        <div class="col-12 col-md-5 nopadding container-map">
            <mapbox
                v-if="showMapFlag"
                v-bind:class="{'is-hidden-map' : !showMap,'is-show-map' : showMap }"
                ref="mapgeneral"
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
                @map-click="mapClicked"
                @map-zoomend="dragMap"


                ></mapbox>
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

        <sweet-modal hide-close-button blocking  ref="modalOnBoarding" class="container-on-boarding"  width="450">

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

                        temporalSite: null,
                        list: [],
                        paginator: 1,
                        showMap: false,
                        tempMapPlaces: [],
                        custommap: null,
                        lastPlaceAdd: 0,
                        showMapFlag: true,
                        latUser: 0,
                        user: {
                            name: "",
                            id: 0,
                            cel: ""
                        },
                        defaultLat: -34.5892671959861,
                        defaultLon: -58.43098330510253,
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
                        geolocalize: false,
                        paintUserLocationFlag: false,
                        aleradyLocatedUser: false,
                        flyselected: false

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
                        this.clearData();
                        this.clearMapFlag = true;
                        this.getSites(this.paginator, 1);

                    },
                    selectedService: function (val) {
                        //    this.show = true;

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
                        // this.show = true;
                        //this.$refs.swiper.preventClicks(false);
                        //this.$refs.swiper.attachEvents();	

                        if (!Vue.localStorage.get('onboarding')) {
                            this.$refs.modalOnBoarding.open();
                        }

                    }, 100)


                    this.latUser = Vue.localStorage.get('lat');
                    this.lonUser = Vue.localStorage.get('lon');
                    this.latUser = this.defaultLat;
                    this.lonUser = this.defaultLon;


                    this.getSitesJson(this.paginator, 1);

                    this.getFilters();

                    if (this.isMobile().any()) {
                        this.showMapFlag = false;

                    } else {

                    }



                }

                ,
                methods: {
                    closeOnBoarding() {
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
                            // this.getSites(this.paginator, 1);
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
                        //this.getSites(this.paginator, 1);


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
                    getSitesJson(page, $state) {

                        axios.get('https://s3.amazonaws.com/staticthigs/sites.json')
                                .then(response => this.formatSites(response.data, $state))
                                .catch(function (thrown) {
                                    console.log(thrown);
                                });

                    },
                    getSites: function (page, $state) {



                        axios.get('/sites?page=' + page + '&lat=' + this.latUser + '&lon=' + this.lonUser + '&services=' + this.serviceArray + '&selectedSpace=' + this.spaceArray)
                                .then(response => this.formatSites(response.data, $state))
                                .catch(error => console.log(error));

                        /*
                         axios.get('/sites?page=' + page + '&lat=' + this.latUser + '&lon=' + this.lonUser + '&services=' + this.serviceArray + '&selectedSpace=' + this.spaceArray)
                         .then(response => this.formatSites(response.data, $state))
                         .catch(error => console.log(error));*/
                    },
                    scrollIntoView(eleID, name) {

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
                                    // this.getSites(this.paginator, 1);
                                    this.fly = true;
                                    // this.$refs.modalUserRegister.open();
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
                        // this.show = false;




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
                        for (var i = 0; i < this.tempMapPlaces.length; i++) {



                            var el = document.createElement('div');


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

                        this.lastPlaceAdd = this.tempMapPlaces[this.tempMapPlaces.length - 1].id;
                        this.lastPlaceAddData = this.tempMapPlaces[this.tempMapPlaces.length - 1];

                        if (this.flyselected) {
                            map.flyTo({
                                center: [this.lastPlaceAddData.lon, this.lastPlaceAddData.lat],
                                zoom: 15,
                                speed: 1,
                                curve: 1,
                            });
                            this.flyselected = false;
                        }



                    },
                    paintMarker(map, data) {
                        var _this = this;
                        if (!!data) {
                            const popupContent = Vue.extend({
                                template: '<div @click="popupClicked" class="container-popup-map"><img  src="' + this.formatGallerySite(data.images[0].url, data.id) + '"/><div class="conainer-text-popup"><h3>' + data.name + '</h3><p>' + data.address + '</p></div></div>',
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
                    popupClickedList(data) {

                        window.open("/site/" + data.id + "/" + this.string_to_slug(data.name));

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
                                    if (!_this.geolocalize) {
                                        // _this.clearMap(map);


                                        _this.latUser = position.coords.latitude;
                                        _this.lonUser = position.coords.longitude;
                                        if (!!position.coords.latitude) {
                                            Vue.localStorage.set('lat', position.coords.latitude)
                                        }
                                        if (!!position.coords.longitude) {
                                            Vue.localStorage.set('lon', position.coords.longitude)
                                        }
                                        //  _this.getSites(_this.paginator, 1);
                                        _this.paintInLocationInMap = true;

                                        _this.paintUserLocation(map);

                                    }


                                }, function () {

                                    if (!_this.geolocalize) {
                                        _this.clearMap(map);


                                        _this.latUser = _this.defaultLat;
                                        _this.lonUser = _this.defaultLon;
                                        Vue.localStorage.set('lat', _this.defaultLat)

                                        Vue.localStorage.set('lon', _this.defaultLon)


                                        //  _this.getSites(_this.paginator, 1);
                                        _this.paintInLocationInMap = true;

                                        _this.paintUserLocation(map);

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
                    findIconLocation() {
                        var elements = document.getElementsByClassName("mapboxgl-marker");
                        for (var i = 0; i < elements.length; i++) {
                            if (elements[i].style.backgroundImage == 'url("/img/pin_user.png")') {
                                elements[i].parentNode.removeChild(elements[i]);

                            }

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
                        this.findIconLocation();
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
                        // map.setCenter([this.lonUser, this.latUser]);

                        map.flyTo({
                            center: [this.lonUser, this.latUser],
                            zoom: 15,
                            speed: 1,
                            curve: 1,
                        });


                        this.paintUserLocationFlag = true;
                        this.setCustomProperties();
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
                                            zoom: 15,
                                            speed: 0.7,
                                            curve: 1,
                                        });
                                        _this.fly = false;
                                    }

                                }

                            }

                        }, 100);


                        var t = setInterval(function () {
                            if (!_this.aleradyLocatedUser) {
                                _this.getLocationUser(map);
                            }

                            if (_this.searchNewPlaces()) {
                                //         this.show = true;

                                _this.paintPlaces(map);
                            }

                        }, 300);


                        navigator.geolocation.watchPosition(function (position) {

                        })





                    },
                    mapLoaded(map) {
                        map.setCenter([this.defaultLon, this.defaultLat]);

                        map.setZoom(15);
                        this.addMapLayers(map);


                    },

                    infiniteHandler($state) {
                        if (this.noData == 0) {
                            //     this.show = true;

                            //   this.getSites(this.paginator, $state);
                        }



                    },
                },
                components: {
                    InfiniteLoading, Mapbox, loading, SweetModal, Carousel,
                    Slide
                },
            };

</script>