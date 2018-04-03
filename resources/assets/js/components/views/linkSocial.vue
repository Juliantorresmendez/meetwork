<template>
    <div class="site-container">
        <div class="row">
    
             <div class="col-md-6 offset-md-3 login-general-container">
                <div class="purple-container login-container">
                    
                    <div class="form-group container-link-facebook">
                        <img :src="idFacebook"/>
                     </div>
                    
                    <p>Hola <b>{{name}}</b> vamos a confirmar que eres tú, por favor ingresa tu contraseña</p>
                        <div class="loading-container" v-if="ShowLoadingContainer">
                            <i class="fa fa-cog fa-spin"></i>  Ingresando...   
                        </div>
                     
                     <form>
                  
                      <div class="form-group">
                        <input type="password" v-model="dataLink.password" class="form-control" id="exampleInputPassword1" placeholder="Escribe contraseña">
                        <small v-if="!dataLink.password" class="form-error-input">{{error.password}}</small>

                      </div>
                         <div class="form-group container-button-login">
                            <button v-on:click="checkForm()" type="button" class="btn-white">Vincular Cuenta</button>

                       </div>
                       
                    </form>
                </div>
                   <p class="forgot-register-container">
                <router-link  :to="{name: 'login'}">¿No eres tu?</router-link>
                o <a href="javascript:void(0)" v-on:click="requestPassword()">Olvide mi contraseña</a> 
                </p> 
             </div>

   
        </div>

  <loading
            :show="show"
            :label="label">
        </loading>
    </div>
</template>

<script>
    import loading from 'vue-full-loading'



    export default {
        data: function () {
            return {
                name:null,
                email:null,
                idFacebook:null,
                dataLink:{
                    email:null,
                    hash:null,
                    password:null,
                    name:null
                },
                error:{
                    email:null,
                    password:null

                },
                        ShowLoadingContainer:false,
                        show : false,
              label: 'Enviando...',

           }
        },
        created: function () { 
            if(!!this.$route.params.email && !!this.$route.params.hash){
                this.idFacebook='http://graph.facebook.com/'+this.$route.params.hash+'/picture?type=square';
                this.name=this.$route.params.name;
                this.email=this.$route.params.email;
                
                this.dataLink.name=this.$route.params.name;
                this.dataLink.email=this.$route.params.email;
                this.dataLink.hash=this.$route.params.hash;
        }else{
                window.location="/login"
            }
           
        }, 
        methods: {
            
            forgotPassword(){
                
            },
           checkForm(){
            
             
              if(!this.dataLink.password){
                    swal("Error","Ingresa la contraseña", "error");

                return false;
              }
              
              
              
              this.show=true;

            
            axios.post('/linksocialData', this.dataLink) 
                    .then(response => this.successLogin(response.data))
                    .catch(error => this.errorLogin(error));
           },
           requestPassword(){
               this.show=true;
                axios.post('/resetPasswordByEmail',{email:this.email}) 
                    .then(response => this.successPasswordReset(response.data))
                    .catch(error => this.errorLogin(error));
           },
           successPasswordReset(data){
               this.show=false;
                if(!!data.error){
                swal("Error",data.message, "error");
                return false;
                }
                if(!!data.success){
                    
                swal("Envio con éxito",data.data, "success");
                return false;
                }
                

           },
           successLogin(data){
               

               
              if(!!data.error){
                  var errorResponse="";                  
                 for(var key in data.message) {
                    errorResponse+="\n"+data.message[key][0];
                }
                swal("Error",errorResponse, "error");
                return false;
            }else{
                Vue.localStorage.set('users', JSON.stringify(data.data))
                this.$root.$data.user=data.data;
               
                if(!!this.$route.query.redirect){
                    window.location=this.$route.query.redirect;
                }else{
                     window.location="/";
                }
                
                this.ShowLoadingContainer=false;
                }
           },
           errorLogin(error){
                             this.show=false;

             swal("Error",error, "error");
                         this.ShowLoadingContainer=false;
           }

        }
        ,
      components: {
      loading
      }
    }
</script>
