<template>
	<div class="site-container">
	     
	    <div class="row">
	    	<div class="col-md-4 ">
	    		<div class="row container-info">
	    			<div class="col-md-12 container-info-site user-individual">
	    				<div class="row logo-info-site">
	    					<div class="col-md-3 container-site-logo">
			    				<img class="logo-site" src="/img/logo.png" />
			    			</div>
			    			<div class="col-md-9">
			    				<h3>{{user.name}}</h3> 
			    				 
			    				
			    			</div>
	    				</div>
	    			 
		    				 
	    			</div> 
	    			
	    		</div>
	    	</div>
	    	<div class="col-md-8 container-description-site">
	    		<h2>Lugares Visitados</h2>
	    
	    		<div class="row ">
	    				<div class="col-12 col-md-6" v-for="item in list">
			  				<router-link class="place "  :key="$route.fullPath"  :to="{name: 'view_site', params: { id: item.site.id,name:string_to_slug(item.site.name) }}"   :id="'sp_' + item.site.id" >
								   <div class="slick-initialized slick-slider">
								      <div class="slick-list">
								         <div class="slick-track" >
								            <div class="slick-slide slick-cloned photo_place" data-index="-1" style="background-image: url(&quot;https://storage.googleapis.com/meetwork/Espacios/1/0.png&quot;); width: 100%;"></div>
							
								         </div>
								      </div>
								     
								   </div>
								   <div class="header">
								      <div class="place-info">
								         <h3>{{item.site.name}}</h3>
								         <h5>Buenos aires - {{item.site.address}}</h5>
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
	    		 
	    	</div>
	    </div>


		 
 


    </div>
</template>

<script>
import helpers from './helpers';


    export default {
        data: function () {
            return {
            	list:[
            	
            	],
            	user:{}
            }
              
        },
           mixins: [ helpers ],

        created: function () {
        	console.log(this.string_to_slug("este es un texto"));
             this.getUser();
        },
        methods: {
        	 getUser(){
        	 	if(this.$route.params.id){
        	 		   axios.get('/app/profile/'+this.$route.params.id)
                    .then(response => this.formatProfile(response.data))
                    .catch(error => console.log(error.response.data));
        	 	}else{
        	 		 axios.get('/app/profile/')
                    .then(response => this.formatProfile(response.data))
                    .catch(error => console.log(error.response.data));
        	 	}
        	 	
        	 },
        	 formatProfile(data){
        	 	this.list=data.reservations;
        	 	this.user=data;
        	 	console.log(data);
        	 }
        }
        ,
	  components: {
	
	  }
    }
</script>
