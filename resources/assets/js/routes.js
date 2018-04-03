import VueRouter from 'vue-router';
import Home from './components/views/Home.vue';
import HomeMobile from './components/views/HomeMobile.vue';
import About from './components/views/About.vue';
import Contact from './components/views/Contact.vue';
import Notes from './components/views/Notes.vue';
import ViewSite from './components/views/Site.vue';
import Login from './components/views/Login.vue';
import Register from './components/views/Register.vue';
import EmailConfirmation from './components/views/EmailConfirmation.vue';
import AlreadyConfirmation from './components/views/AlreadyConfirmation.vue';
import Logout from './components/views/Logout.vue';
import ProfilePerson from './components/views/ProfilePerson.vue';
import Profile from './components/views/Profile.vue';
import Helpers from './components/views/Profile.vue';
import Map from './components/views/Map.vue';
import CreateSite from './components/views/CreateSite.vue';
import EditSite from './components/views/EditSite.vue';
import EditSiteAdmin from './components/views/EditSiteAdmin.vue';
import Admin from './components/views/Admin.vue';
import AdminSites from './components/views/AdminSites.vue';



import Reservations from './components/views/Reservations.vue';
import resetPassword from './components/views/resetPassword.vue';
import linkSocial from './components/views/linkSocial.vue';
import forgotPasword from './components/views/forgotPasword.vue';
import sendValidation from './components/views/sendValidation.vue';
  
let routes = [ 
    { name:'allsite',path: '/', component: Home },
    { name:'home',path: '/home', component: Home },
    { name:'mobile',path: '/mobile', component: HomeMobile },
    { name:'forgotPasword',path: '/forgotPasword', component: forgotPasword },
    { name:'sendValidation',path: '/sendValidation', component: sendValidation },
    
    
        
    { name:'linkSocial',path: '/linkSocial', component: linkSocial },
    
    { name:'admin',path: '/adminMeetwork', component: Admin },
    { name:'adminSites',path: '/dashboard', component: AdminSites },
    { name:'map',path: '/map', component: Map },
    { name:'logoutPage',path: '/logoutPage', component: Logout},
    { path: '/about', component: About, meta: { requiresAuth: true}  },
    { name:'contact', path: '/contact', component: Contact, meta: { requiresAuth: true} },
    { path: '/notes', component: Notes },
    { name:'login',path: '/login', component: Login },
    { name:'email_confirmation',path: '/email_confirmation', component: EmailConfirmation },
    { name:'already_confirmation',path: '/already_confirmation', component: AlreadyConfirmation },
    { name:'register',path: '/register', component: Register },
    { name:'profile',path: '/profile/:id/:name', component: ProfilePerson },
    { name:'singleProfile',path: '/profile/', component: Profile  },
    { name:'create_site',path: '/create', component: CreateSite },
    { name:'edit_site',path: '/edit/:id', component: EditSite },
    { name:'edit_site_admin',path: '/editadmin/:id', component: EditSiteAdmin },
    { name:'edit_site_new',path: '/edit/:id/:new', component: EditSite, meta: { requiresAuth: true}  },
    { name:'site_reservations',path: '/reservations/:id/:name', component: Reservations, meta: { requiresAuth: true}  },
    { name:'site_reset_password',path: '/resetPassword', component: resetPassword },
 
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