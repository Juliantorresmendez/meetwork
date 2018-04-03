<template>
    <div class="site-container">
        <div class="row">
    
             <div class="col-md-6 offset-md-3 login-general-container">
                <div class="purple-container login-container">
                    <h1 class="title">Cambia tu contraseña</h1>
                     <p>En este lugar podrás cambiar tu contraseña</p>
                        <div class="loading-container" v-if="ShowLoadingContainer">
                            <i class="fa fa-cog fa-spin"></i>  Ingresando...   

                        </div>
                     <form>
                      <div class="form-group">
                        <input type="password" v-model="dataRecover.password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ingresa la contraseña ">
                        <small v-if="dataRecover.password" class="form-error-input">{{error.password}}</small>

                      </div>
                      <div class="form-group">
                        <input type="password" v-model="dataRecover.password_confirmation" class="form-control" id="exampleInputPassword1" placeholder="Repite la contraseña">
                        <small v-if="!dataRecover.password_confirmation" class="form-error-input">{{error.password_confirmation}}</small>

                      </div>
                         <div class="form-group container-button-login">
                            <button v-on:click="checkForm()" type="button" class="btn-white">Recuperar Contraseña</button>

                       </div>
                       
                    </form>
                </div>
                 
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
                    password:null,
                    password_confirmation:null,
                    token_recover:null
                },
                error:{
                    email:null,
                    password:null

                },
                        ShowLoadingContainer:false,
                        show : false,
              label: 'Validando código...',

           }
        },
        created: function () { 
            
           
        }, 
        methods: {
            

           checkForm(){
            
             if(!this.dataRecover.password_confirmation){
                swal("Error","Repite la contraseña", "error");

                 return false;
              }

              if(!this.dataRecover.password){
                    swal("Error","Ingresa la contraseña", "error");

                return false;
              }
              
              
              if(this.dataRecover.password!=this.dataRecover.password_confirmation){
                     
                swal("Error","Repite la contraseña correctamente", "error");

                return false;
              }
              this.show=true;
            this.dataRecover.token_recover=this.$route.query.code;
            axios.post('/changePasswordUser', this.dataRecover) 
                    .then(response => this.successLogin(response.data))
                    .catch(error => this.errorLogin(error));
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
