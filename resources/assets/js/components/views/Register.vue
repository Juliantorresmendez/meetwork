<template>
    <div class="site-container">
        <div class="row">
    
             <div class="col-md-6 offset-md-3 login-general-container">
                <div class="purple-container login-container">
                    <h1 class="title">Registra a tu cuenta</h1>
                     <p>Este es una descripcion de entrar a la cuenta</p>

                     <form method="post"  @submit="checkForm" action="registerAccount">
                     <div class="form-group">

                        <input v-model="dataRegister.name" type="text" class="form-control" name="name"  aria-describedby="emailHelp" placeholder="Nombre">
                         <small v-if="!dataRegister.name" class="form-error-input">{{error.name}}</small>

                      </div>
                      <div class="form-group">
                        <input v-model="dataRegister.email" type="email" class="form-control" name="email"  aria-describedby="emailHelp" placeholder="Correo Electrónico">
                             <small  v-if="!dataRegister.email"  class="form-error-input">{{error.email}}</small>

                      </div>
                      <div class="form-group">
                        <input v-model="dataRegister.password" type="password" class="form-control" name="password" placeholder="Contraseña">
                             <small v-if="!dataRegister.password" class="form-error-input">{{error.password}}</small>

                      </div>
                      <div class="form-group">
                        <input v-model="dataRegister.password_confirmation" type="password" class="form-control" name="repeat-password"  placeholder="Repetir Contraseña">
                         <small v-if="!dataRegister.password_confirmation"  class="form-error-input">{{error.repeatPassword}}</small>

                      </div>
                       <div class="form-group container-button-login">
                            <button type="submit" class="btn-white">Registra tu cuenta</button>
                       </div>
                    </form>
                </div>
                <p class="forgot-register-container">
                    <router-link  :to="{name: 'login'}">¿Ya tienes cuenta?</router-link>
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
              dataRegister:{
                name:null,
                email:null,
                password:null,
                password_confirmation:null,
              },
              show:false,
              label: 'Registrando Usuario...',
              error:{
                name:null,
                email:null,
                password:null,
                repeatPassword:null,
              }

           }
        },
        created: function () {
        
          if(Vue.localStorage.get('users')){
              this.$router.push('/')
          }
        },
        methods: {
           checkEmail(emailAddress) {
              var sQtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
              var sDtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
              var sAtom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
              var sQuotedPair = '\\x5c[\\x00-\\x7f]';
              var sDomainLiteral = '\\x5b(' + sDtext + '|' + sQuotedPair + ')*\\x5d';
              var sQuotedString = '\\x22(' + sQtext + '|' + sQuotedPair + ')*\\x22';
              var sDomain_ref = sAtom;
              var sSubDomain = '(' + sDomain_ref + '|' + sDomainLiteral + ')';
              var sWord = '(' + sAtom + '|' + sQuotedString + ')';
              var sDomain = sSubDomain + '(\\x2e' + sSubDomain + ')*';
              var sLocalPart = sWord + '(\\x2e' + sWord + ')*';
              var sAddrSpec = sLocalPart + '\\x40' + sDomain; // complete RFC822 email address spec
              var sValidEmail = '^' + sAddrSpec + '$'; // as whole string

              var reValidEmail = new RegExp(sValidEmail);

              return reValidEmail.test(emailAddress);
            },
            registerAccount(){
                    this.show=true;

               axios.post('/registerAccount', this.dataRegister) 
                    .then(response => this.successRegister(response.data))
                    .catch(error => this.errorRegister(error));
            },
            successRegister(data){
                 if(!!data.success){
                  this.$router.push('/email_confirmation')
                  this.$root.$data.confirmationEmail=data.data;
                 }

                 if(!!data.error){
                  var errorResponse="";
                 for(var key in data.message) {
                    errorResponse+="\n"+data.message[key][0];
                }
              
                 swal("Error",errorResponse, "error");
                                     this.show=false;

               }

            },
            errorRegister(error){
                 swal("Error",error, "error");
                                                      this.show=false;


            },
            checkForm:function(e) {
              if(!this.dataRegister.name){
                this.error.name="Ingresa el nombre"
                return false;
              }
               if(!this.dataRegister.email){
                this.error.email="Ingresa el Correo Electrónico"
                return false;
              }
               if(!this.dataRegister.password){
                this.error.password="Ingresa la Contraseña"
                return false;
              }
               if(!this.dataRegister.password_confirmation){
                this.error.repeatPassword="Repite la contraseña"
                return false;
              }

              if(!this.checkEmail(this.dataRegister.email)){
                this.error.email="Ingresa un email válido"
                return false;
              }
              if(this.dataRegister.password != this.dataRegister.password_confirmation){
                this.dataRegister.password_confirmation=null;
                this.error.repeatPassword="Repite la contraseña correctamente"
                return false;
              }
              this.registerAccount();
            e.preventDefault();
          }
        }
        ,
      components: {
        loading
      }
    }
</script>
