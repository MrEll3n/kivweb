import './assets/main.css';

import { createApp } from 'vue'
import { createPinia} from 'pinia'
// TODO: Chyba App.vue
// @ts-ignore
import App from './App.vue';
import './assets/main.css';
import router from "@/router";

createApp(App)
  .use(router)
  .use(createPinia())
  .mount('#app');
