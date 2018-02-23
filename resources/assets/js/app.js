
import  './bootstrap';

import router from './routes';

import './components';

router.beforeEach((to, from, next) => {
	var userAuth=null;
	if(Vue.localStorage.get('users')!='undefined'){
    	var userAuth=JSON.parse(Vue.localStorage.get('users'));
	}

  if (to.matched.some(record => record.meta.requiresAuth) && !userAuth) {
  		
    next({ path: '/login', query: { redirect: to.name }});
  } else {
    next();
  }

});

  

const app = new Vue({
    el: '#app',
    router,

    created:function (){
		this.$root.$data.user=JSON.parse(Vue.localStorage.get('users'))

    },
     methods: {
       
     }
});
