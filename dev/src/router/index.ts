import {
    createMemoryHistory,
  createRouter,
  createWebHashHistory,
  createWebHistory,
  type Router,
} from 'vue-router';
// @ts-ignore
import MainView from "../views/MainView.vue";
// @ts-ignore
import LoginView from "../views/LoginView.vue";
// @ts-ignore
import RegisterView from "../views/RegisterView.vue";
// @ts-ignore
import DashboardView from "../views/DashboardView.vue";

const baseRoute = '/kivweb/frontend/'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: MainView,
    },  
    {
        path: '/login',
        name: 'Login',
        component: LoginView,
    },
    {
        path: '/register',
        name: 'Register',
        component: RegisterView,
    },
    {
        path: '/dashboard',
        name: "Dashboard",
        component: DashboardView
    }
];

const router: Router = createRouter({
    history: createWebHistory(baseRoute),
    routes
});

export default router;
