<template>
    <div class="site-container">
        <div class="row">
    
             <div class="col-md-6 offset-md-3 login-general-container">
                <div class="purple-container login-container">
                    <h1 class="title">Confirmación de Código</h1>
                     <p>Ya activaste tu cuenta, ingresa <router-link  :to="{name: 'all_site'}" class="simple-link">AQUÍ</router-link> para volver MeetWork</p>

                    
                       <div class="form-group container-button-login row">
                          <router-link  :to="{name: 'all_site'}" class="btn-white full-link">Volver a MeetWork</router-link>
                       </div>
                    
                </div>
                <p class="forgot-register-container">
                
                </p>
             </div>

   
        </div>


    </div>
</template>

<script>



    export default {
        data: function () {
            return {
              email:null
           }
        },
        created: function () {
          this.email=this.$root.$data.confirmationEmail
        },  
        methods: {
           
        }
        ,
      components: {
      
      }
    }
</script>
