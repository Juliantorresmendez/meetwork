<template>
    <div class="site-container">
        <div class="row">
    
             <div class="col-md-6 offset-md-3 login-general-container">
                <div class="purple-container login-container">
                    <h1 class="title">Estamos activando tu cuenta</h1>
                    <p>Te hemos enviado un <b>correo electrónico</b> a <b>{{email}}</b> para que puedas generar tu contraseña. </p>
                </div>
                 
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
            
            this.email=this.$route.query.email;
           
        }, 
        methods: {
            

        }
        ,
      components: {
      
      }
    }
</script>
