<template>
    <div class="site-container">
         <div class="row">
             <div class="col-md-12">
             <h1 class="purple-header text-center">Saliendo de tu oficina...</h1>
             </div>
         </div>
    </div>
</template>

<script>



    export default {
        data: function () {
            return {
                
                
           }
        },
        created: function () { 
            Vue.localStorage.remove('users')
            this.$root.$data.user=null;
            this.$router.push('login')

        },
        methods: {
          
           
        }
        ,
      components: {
      
      }
    }
</script>
