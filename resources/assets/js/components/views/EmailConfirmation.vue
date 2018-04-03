<template>
    <div class="site-container">
        <div class="row">
    
             <div class="col-md-6 offset-md-3 login-general-container">
                <div class="purple-container login-container">
                    <h1 class="title">Confirmación de Código</h1>
                     <p>Enviamos un Correo Electrónico a<strong> {{email}}</strong> con un código ingresalo a continuación...</p>

                     <form>
                      <div class="form-group">
                        <input type="text" v-model="code" class="form-control" id="exampleInputEmail1"  placeholder="Ingresa el código de confirmación">
                      </div>

                       <div class="form-group container-button-login" v-if="code">
                            <button type="button" v-on:click="validateCode()" class="btn-white">Validar Codigo</button>
                       </div>
                    </form>
                </div>
                <p class="forgot-register-container" >
                  <a v-on:click="resendCode()" href="javascript:void(0)">Reenviar Código</a>
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
              email:null,
              code:null,
              show:false,
              label: 'Validando código...',

           }
        },
        created: function () {
            if(!!this.$root.$data.confirmationEmail){
                this.email=this.$root.$data.confirmationEmail;

            }else{
                              this.$router.push('login')

            }
                this.show=false;
        },  
        methods: {
          successResendCode(){
                                        this.show=false;

           swal("Envio Correcto!","Se ha enviado correctamente el código de activacion", "success");
          },
          errorResendCode(){
                                                    this.show=false;

           swal("Error","Hay un error en el envio del codigo", "error");
          },

          resendCode(){
                            this.show=true;
            axios.get('/resendCode/')
                    .then(response => this.successResendCode(response.data))
                    .catch(error => this.errorResendCode(error.response.data));
          },
          successVerify(data){
                        
             if(!!data.success){
                    Vue.localStorage.set('users', JSON.stringify(data.data))
                    this.$root.$data.user=data.data;
                        swal("!Registro Exitoso!","El registro se ha hecho con éxito", "success");

                   window.location='/login';
                 }

                  if(!!data.error){
                    window.location='/register';
                    swal("Error","Hay un error en la validación del codigo", "error");
                  }      
          },
          errorVerify(data){
            swal("Error","Hay un error en la validación del codigo", "error");

          },
          validateCode(){
              
                          

                            this.show=true;
            if(this.code){
               axios.get('/register/verify/'+this.code+'/'+this.email+'/?page=1')
                    .then(response => this.successVerify(response.data))
                    .catch(error => this.errorVerify(error.response.data));
              }else{
                           swal("Error","Ingresa el código de activación", "error");

              }
           
          }
        }
        ,
      components: {
        loading
      }
    }
</script>
