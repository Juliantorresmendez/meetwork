import Modal from './components/Modal.vue';
import CreateNote from './components/CreateNote.vue';
import Alert from './components/Alert.vue';
import VModal from 'vue-js-modal'

 
import VueLocalStorage from 'vue-localstorage'
 import FBSignInButton from 'vue-facebook-signin-button'

import VueLazyLoad from 'vue-lazyload'
import VueTouch from 'vue-touch'
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'

import VueTabs from 'vue-nav-tabs'
import 'vue-nav-tabs/themes/vue-tabs.css'
import VuejsDialog from "vuejs-dialog"

var store = {
  state: {
    route:null
  },
  addRoute: function(route) {
  
     this.state.route=route;
  }
}

Vue.use(VueTabs)

Vue.use(FBSignInButton)
Vue.use(VueFormWizard)

 
// Tell Vue to install the plugin.
Vue.use(VuejsDialog)

const moment = require('moment')
require('moment/locale/es')

Vue.use(require('vue-moment'), {
    moment
})



Vue.use(VModal)
Vue.use(VueLocalStorage)
Vue.use(VueLazyLoad)
Vue.use(VueTouch, { name: 'v-touch' })

Vue.component('v-select', VueSelect.VueSelect);

Vue.component('modal', Modal);
Vue.component('create-note', CreateNote);
Vue.component('alert', Alert);


 