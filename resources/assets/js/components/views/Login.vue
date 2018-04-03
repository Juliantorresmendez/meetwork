<template>
    <div class="site-container">
        <div class="row">
    
             <div class="col-md-6 offset-md-3 login-general-container">
                <div class="purple-container login-container">
                    <h1 class="title">Ingresa a tu cuenta</h1>
                     <p>Si tienes una cuenta ingresa tus datos, si no es así ingresa desde facebook o has click en registrar cuenta</p>
                        <div class="loading-container" v-if="ShowLoadingContainer">
                            <i class="fa fa-cog fa-spin"></i>  Ingresando...   

                        </div>
                     <form>
                      <div class="form-group">
                          <input autofocus="false" type="email" v-model="dataLogin.email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ingresa el Correo Electrónico ">
                        <small v-if="dataLogin.email" class="form-error-input">{{error.name}}</small>

                      </div>
                      <div class="form-group" v-if="!social">
                        <input autofocus="false" type="password" v-model="dataLogin.password" class="form-control" id="exampleInputPassword1" placeholder="Contraseña">
                        <small v-if="!dataLogin.password" class="form-error-input">{{error.password}}</small>

                      </div>
                      
                            <div class="form-group container-button-login">
                            <button v-on:click="checkForm()" type="button" class="btn-white">Ingresar</button>

                            </div>
                    </form>
                </div>
                 
                  <div class="form-group container-button-login">

                            <fb-signin-button
                                v-if="!social"
    :params="fbSignInParams"
    @success="onSignInSuccess"
    @error="onSignInError">
   <i class="fa fa-facebook-official" aria-hidden="true"></i> Ingresar con Facebook
  </fb-signin-button>
                            
                       </div>
                 
                <p class="forgot-register-container">
                <router-link  :to="{name: 'forgotPasword'}">¿Olvidaste tu contraseña?</router-link>
                 o <router-link  :to="{name: 'register'}">Registra tu cuenta</router-link>  
                </p> 
             </div>

   
        </div>


    </div>
</template>

<script>



    export default {
        data: function () {
            return {
                 fbSignInParams: {
        scope: 'email',
        return_scopes: true,
      },
      social:false,
                dataLogin:{
                    email:null,
                    password:null,
                    social:0,
                    name:null
                },
                error:{
                    email:null,
                    password:null

                },
                        ShowLoadingContainer:false

           }
        },
        created: function () { 
            
                
        }, 
        methods: {
            
 onSignInSuccess (response) {
     var _this=this;
     this.ShowLoadingContainer=true;
     FB.api('/me', { locale: 'en_US', fields: 'name, email,id' },
        function(response) {
        

            _this.dataLogin.email=response.email;
            _this.dataLogin.name=response.name;
            _this.dataLogin.password=response.id;
            _this.dataLogin.social=response.id;
            _this.social=true;
            _this.checkForm();
        }
      )
    },
    onSignInError (error) {
      console.log('OH NOES', error)
    },
           checkForm(){
            
             if(!this.dataLogin.email){

                this.error.email="Ingresa el Correo Electrónico"
                return false;
              }

              if(!this.dataLogin.password){
                this.error.password="Ingresa la contraseña"
                return false;
              }

            axios.post('/app/LoginUser', this.dataLogin) 
                    .then(response => this.successLogin(response.data))
                    .catch(error => this.errorLogin(error));
           },
           
           b64EncodeUnicode(str) {
    
                return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
                    function toSolidBytes(match, p1) {
                        return String.fromCharCode('0x' + p1);
                }));
            },
           
           
           successLogin(data){
                 this.show=false;


                


            if(!!data.error){
                  var errorResponse="";                  
             
                if(!!data.message.vinculacion){
                    swal("Vinculacion con Facebook",data.message.message, "success");
                        this.$router.push({name: 'linkSocial', params:{email:this.dataLogin.email,hash:this.dataLogin.social,name:this.dataLogin.name}})

                }else{
                      for(var key in data.message) {
                        errorResponse+="\n"+data.message[key][0];
                    }
                        swal("No pudiste ingresar.",errorResponse, "error");

                  }
                

                
                return false;
            }else{
                
            
              
             
                Vue.localStorage.set('users', JSON.stringify(data.data))
                this.$root.$data.user=data.data;

                if(!!this.$route.query.redirect){
                 window.location=this.$route.query.redirect;
                 //   this.$router.push(this.$route.query.redirect)

                }else{
                     window.location="/";
                    //this.$router.push('/')

                }
                this.ShowLoadingContainer=false;
                }
           },
           errorLogin(error){
             swal("Error",error, "error");
                this.ShowLoadingContainer=false;
                this.show=false;


           }

        }
        ,
      components: {
      
      }
    }
</script>
