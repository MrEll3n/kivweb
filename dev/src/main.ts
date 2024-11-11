import './assets/main.css';

import { createApp } from 'vue';
// TODO: Chyba App.vue
// @ts-ignore
import App from './App.vue';
import './assets/main.css';
import router from "@/router";

createApp(App)
  .use(router)
  .mount('#app');
