import Modal from './components/Modal.vue';
import CreateNote from './components/CreateNote.vue';
import Alert from './components/Alert.vue';
import VModal from 'vue-js-modal'

import VueAuthenticate from 'vue-authenticate'

import VueLocalStorage from 'vue-localstorage'
 
import VueLazyLoad from 'vue-lazyload'
import VueTouch from 'vue-touch'

var store = {
  state: {
    route:null
  },
  addRoute: function(route) {
  
     this.state.route=route;
  }
}
Vue.use(VModal)
Vue.use(VueLocalStorage)
Vue.use(VueLazyLoad)
Vue.use(VueTouch, { name: 'v-touch' })

Vue.component('v-select', VueSelect.VueSelect);

Vue.component('modal', Modal);
Vue.component('create-note', CreateNote);
Vue.component('alert', Alert);


Vue.use(VueAuthenticate, {
  baseUrl: 'http://localhost:3000', // Your API domain
  
  providers: {
    github: {
      clientId: '',
      redirectUri: 'http://localhost:8080/auth/callback' // Your client app URL
    }
  }
})