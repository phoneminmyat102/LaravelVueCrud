
import {createApp} from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
// import { fa } from '@fortawesome/fontawesome-free';
import { fas } from '@fortawesome/free-solid-svg-icons';

require('./bootstrap');

import App from './App.vue';
import axios from 'axios';
import router from './router';

// library.add(faBars);
library.add(fas);

const app = createApp(App);
app.component('font-awesome-icon', FontAwesomeIcon);
app.config.globalProperties.$axios = axios;
app.use(router);
app.mount('#app');
