import VueRouter from 'vue-router';
import Home from './components/views/Home.vue';
import About from './components/views/About.vue';
import Contact from './components/views/Contact.vue';
import Notes from './components/views/Notes.vue';
import ViewSite from './components/views/Site.vue';
import Login from './components/views/Login.vue';
import Register from './components/views/Register.vue';
import EmailConfirmation from './components/views/EmailConfirmation.vue';
import AlreadyConfirmation from './components/views/AlreadyConfirmation.vue';
import Logout from './components/views/Logout.vue';
import Profile from './components/views/Profile.vue';
import Helpers from './components/views/Profile.vue';

let routes = [ 
    { name:'all_site',path: '/', component: Home },
    { name:'logout',path: '/logout', component: Logout},
    { path: '/about', component: About, meta: { requiresAuth: true}  },
    { name:'contact', path: '/contact', component: Contact, meta: { requiresAuth: true} },
    { path: '/notes', component: Notes },
    { name:'login',path: '/login', component: Login },
    { name:'email_confirmation',path: '/email_confirmation', component: EmailConfirmation },
    { name:'already_confirmation',path: '/already_confirmation', component: AlreadyConfirmation },
    { name:'register',path: '/register', component: Register },
    { name:'profile',path: '/profile/:id/:name', component: Profile },
    { name:'singleProfile',path: '/profile/', component: Profile, meta: { requiresAuth: true}  },

    {
        name: 'view_site',
        path: '/site/:id/:name',
        component: ViewSite
    }
];



export default new VueRouter({
  mode: 'history',

    routes,
    linkActiveClass: 'active'
});