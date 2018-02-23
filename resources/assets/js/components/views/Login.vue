<template>
    <div class="site-container">
        <div class="row">
    
             <div class="col-md-6 offset-md-3 login-general-container">
                <div class="purple-container login-container">
                    <h1 class="title">Ingresa a tu cuenta</h1>
                     <p>Este es una descripcion de entrar a la cuenta </p>

                     <form>
                      <div class="form-group">
                        <input type="email" v-model="dataLogin.email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ingresa el Correo Electrónico ">
                        <small v-if="dataLogin.email" class="form-error-input">{{error.name}}</small>

                      </div>
                      <div class="form-group">
                        <input type="password" v-model="dataLogin.password" class="form-control" id="exampleInputPassword1" placeholder="Contraseña">
                        <small v-if="!dataLogin.password" class="form-error-input">{{error.password}}</small>

                      </div>
                       <div class="form-group container-button-login">
                            <button v-on:click="checkForm()" type="button" class="btn-white">Ingresar</button>
                       </div>
                    </form>
                </div>
                <p class="forgot-register-container">
                <router-link  :to="{name: 'register'}">¿Olvidaste tu contraseña?</router-link>
                 o <router-link  :to="{name: 'register'}">registra tu cuenta</router-link>  
                </p>
             </div>

   
        </div>


    </div>
</template>

<script>



    export default {
        data: function () {
            return {
                dataLogin:{
                    email:null,
                    password:null
                },
                error:{
                    email:null,
                    password:null

                }
                
           }
        },
        created: function () { 
           if(Vue.localStorage.get('users')){
              this.$router.push('/')
          }
        },
        methods: {
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
           successLogin(data){
                Vue.localStorage.set('users', JSON.stringify(data.data))
                this.$root.$data.user=data.data;
               
                if(!!this.$route.query.redirect){
                  this.$router.push(this.$route.query.redirect)

                }else{
                   this.$router.push('/')
                }


           },
           errorLogin(error){
             swal("Error",error, "error");
           }

        }
        ,
      components: {
      
      }
    }
</script>
