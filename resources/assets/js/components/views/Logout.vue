<template>
    
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

            this.logout();
            this.$router.push('login')

        },
        methods: {
          
            logout(){
                axios.get('/logoutPage')
                    .then(response => console.log(response.data))
                    .catch(error => console.log(error.response.data));
            }
        }
        ,
      components: {
      
      }
    }
</script>
