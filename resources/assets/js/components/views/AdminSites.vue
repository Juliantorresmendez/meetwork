<template>
    <div class="site-container">

        <div class="row">

            <div class="col-md-12 dashboard-user">
                <h2  v-show="!nospaces">Espacios Listados</h2>

                <div class="row" v-show="!nospaces">
                    <div class="col-md-3 filder-inputs" v-bind:class="{'is-hidden' : hidden,'is-show' : !hidden  }" >
                        <p>Tipos de espacios</p>
                        <v-select multiple label="name" :options="optionsSpace" v-model="selectedSpace"></v-select>
                    </div>

                    <div class="col-md-3 filder-inputs" v-bind:class="{'is-hidden' : hidden,'is-show' : !hidden }">
                        <p>Servicios ofrecidos</p>

                        <v-select multiple label="name"  :options="optionsService" v-model="selectedService"></v-select>

                    </div>
                    
                    <div class="col-md-2 filder-inputs">
                        <p>Estado</p>

                        <select class="dropDown-Simple" v-model="status_id">
                                        <option v-for="option in options" v-bind:value="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                    </div>
                    <div class="col-md-2 filder-inputs">
                        <p>Buscador</p>

                        <input v-model="siteSearch" style="margin-top:0px" type="text" class="form-control" name="text" placeholder="Busqueda">
                        
                    </div>
                     <div class="col-md-2 filder-inputs">
                                                 <p>.</p>

                         <button class="btn-purple btn-small" v-on:click="SearchByWord()"> Buscar</button>
                        
                    </div>

                <hr/>
                </div>
                <div class="row no-site-message" v-if="nospaces">
                    <p>Aún no has registrado ningún espacio <button onclick="window.location='/create'" class="btn-purple">Registra uno Ahora!</button></p>
                </div>
                <div class="row " v-for="group in list" style="margin-top:50px">
                    <div class="col-12 col-md-4" v-for="item in group" >
                        <div class="place " v-on:click="popupClickedList(item)"  :id="'sp_' + item.id" >
                             <div class="slick-initialized slick-slider">
                                <div class="slick-list">
                                    <div class="slick-track" >
                                        <div class="slick-slide slick-cloned photo_place" v-if="item.images.length" data-index="-1"
                                             v-bind:style="{ 'background-image': 'url(' + formatesetresImages(item.id,item.images[0].url) + ')' }"
                                             ></div>

                                    </div>
                                </div>

                            </div>
                            <div class="header">
                                <div class="place-info">
                                    <h3>{{item.name}} <span style="color:red" v-if="item.status_id==1">Inactivo</span> <span  style="color:green" v-if="item.status_id==2">Activo</span></h3>
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


                </div>
                <div class="row" v-if="showMore">
                    <button style="margin: auto;margin-bottom: 30px;" class="btn-purple" v-on:click="viewMoreSites()">Ver más Espacios</button>
                </div>


            </div>
        </div>




    </div>
</template>

<script>
    import helpers from './helpers';
    import { SweetModal, SweetModalTab } from 'sweet-modal-vue'



            export default {
                data: function () {
                    return {
                        showMore:false,
                        nospaces:false,
                        list: [

                        ],
                        selectedSpace: [],
                        selectedService: [],
                        optionsSpace: [],
                        optionsService: [],
                        spaceArray: [],
                        serviceArray: [],
                        status_id:2,
                        hidden: true,
                        paginator:1,
                        siteSearch:"",
                        options: [
                            {text: 'Inactivo', value: 1},
                            {text: 'Activo', value: 2},
                        ],

                    }

                },

                 watch: {

                    selectedSpace: function (val) {
                        this.show = true;
                        var ids = [];
                        for (var i = 0; i < val.length; i++) {
                            ids.push(val[i].id);
                        }
                        this.spaceArray = ids
                        this.clearData();
                        this.getSites(this.paginator);

                    },
                    selectedService: function (val) {
                        this.show = true;

                        var ids = [];
                        for (var i = 0; i < val.length; i++) {
                            ids.push(val[i].id);
                        }
                        this.serviceArray = ids

                        this.clearData();
                        this.getSites(this.paginator);

                    }
                },
                mixins: [helpers],

                created: function () {
                    this.getFilters();

                    this.getSites(this.paginator);
                },
                methods: {
                    viewMoreSites(){
                        this.paginator= this.paginator+1;
                        this.getSites(this.paginator);
                    },
                    clearData() {
                        this.list = [];

                        this.paginator = 1
                       
                    },
                    SearchByWord(){
                        this.clearData();

                        this.getSites(this.paginator);
                    },
                    popupClickedList(data) {
                        window.open("/edit/"+data.id)
                    },
                    getSites(page) {
                        
                        axios.get('/getUserSites?page='+page+'&services=' + this.serviceArray + '&selectedSpace=' + this.spaceArray+'&siteSearch='+this.siteSearch+'&status_id='+this.status_id)
                                .then(response => this.formatProfile(response.data))
                                .catch(error => console.log(error.response.data));

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
                    formatProfile(data) {

                        this.list.push(data.data);
                
                if(data.data.length==0){
                    this.nospaces=true;
                }
                if(!!data.next_page_url){
                    this.showMore=true;
                }

                    }
                }
                ,
                components: {
                    SweetModal
                }
            }
</script>
