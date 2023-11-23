import { createApp } from 'vue';
import App from './App.vue';
// import VueSession from "vue-session";
// import css
import './assets/css/app.css';

// Bootstrap 5
import 'bootstrap/dist/css/bootstrap.css'; 
import 'bootstrap/dist/js/bootstrap.bundle.min.js'; 

import 'jquery';

// import icone
import '@fortawesome/fontawesome-free/css/all.min.css';

//import router
import router from './router';

const app = createApp(App)
      // app.use(VueSession)
      app.use(router)
      app.mount('#app')