<template>
    <div class="site-container">
        <div class="row">
    
             <div class="col-md-6 offset-md-3 login-general-container">
                <div class="purple-container login-container">
                                        <h1 class="title text-center">Recupera tu contraseña</h1>

                        <div class="loading-container" v-if="ShowLoadingContainer">
                            <i class="fa fa-cog fa-spin"></i>  Ingresando...   
                        </div>
                     
                     <form>
                  
                      <div class="form-group">
                        <input type="email" v-model="dataRecover.email" class="form-control" id="exampleInputPassword1" placeholder="Escribe tu correo electrónico">
                        <small v-if="!error.email" class="form-error-input">{{error.email}}</small>

                      </div>
                         <div class="form-group container-button-login">
                            <button v-on:click="checkForm()" type="button" class="btn-white">Recuperar Contraseña</button>

                       </div>
                       
                    </form>
                </div>
                   <p class="forgot-register-container">
                <router-link  :to="{name: 'login'}">Volver a inicio de sesión</router-link>
                o <router-link  :to="{name: 'register'}">Ir a Registro</router-link> 
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
 
                dataRecover:{
                    email:null,
                },
                error:{
                    email:null,

                },
                        ShowLoadingContainer:false,
                        show : false,
              label: 'Enviando...',

           }
        },
        created: function () { 
  
        }, 
        methods: {
            
            forgotPassword(){
                
            },
           checkForm(){
            
             
              if(!this.dataRecover.email){
                    swal("Error","Ingresa una contraseña válida", "error");

                return false;
              }
              
              
              
              this.show=true;
              axios.post('/resetPasswordByEmail',{email:this.dataRecover.email}) 
                    .then(response => this.successPasswordReset(response.data))
                    .catch(error => this.errorLogin(error));
        
           },
           requestPassword(){
               this.show=true;
               
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
