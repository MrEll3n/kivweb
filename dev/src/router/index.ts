import {
    createMemoryHistory,
    createRouter,
    createWebHashHistory,
    createWebHistory,
    type Router,
} from 'vue-router';

import { useAuthStore } from '@/stores/auth.store';
import { useArticleStore } from '@/stores/article.store';

// @ts-ignore
import MainView from "../views/MainView.vue";
// @ts-ignore
import LoginView from "../views/LoginView.vue";
// @ts-ignore
import RegisterView from "../views/RegisterView.vue";
// @ts-ignore
import DashboardView from "../views/DashboardView.vue";
// @ts-ignore
import NewPostView from "../views/NewPostView.vue";
// @ts-ignore
import ArticleView from "../views/ArticleView.vue";
// @ts-ignore
import ProfileView from "../views/ProfileView.vue";
// @ts-ignore
import NotFoundView from "../views/NotFoundView.vue";
// @ts-ignore
import ForbiddenView from "../views/ForbiddenView.vue";
// @ts-ignore
import ReviewsView from "../views/ReviewsView.vue";
// @ts-ignore
import ModerationView from "../views/ModerationView.vue";


import { getCurrentUser, getCurrentPerm, getArticle, getArticles, getToken, refreshToken } from '@/utils/rest-api';

const baseRoute = '/kivweb/frontend/'

const routes = [
    {
        path: '/',
        redirect: '/news?page=1',
        name: 'Home',
        // @ts-ignore
        component: () => import('../views/MainView.vue'),
        children: [],
    },
    {
        path: '/news',
        name: 'News',
        component: MainView,
        beforeEnter: (to: any, from: any, next: any) => {
            // Check if the query parameter is missing
            if (!to.query.page) {
            // Redirect with default query parameter
                next({
                    path: to.path,
                    query: { ...to.query, page: 1 },
                });
            } else {
                next();
            }
        },
    },
    {
        path: '/post/:article',
        name: 'Article',
        component: ArticleView,
    },
    {
        path: '/login',
        name: 'Login',
        component: LoginView,
    },
    {
        path: '/newpost',
        name: 'NewPost',
        component: NewPostView,
    },
    {
        path: '/register',
        name: 'Register',
        component: RegisterView,
    },
    {
        path: '/dashboard',
        name: "Dashboard",
        component: DashboardView,
    },
    {
        path: '/profile',
        name: "Profile",
        component: ProfileView,
    },
    {
        path: '/reviews',
        name: "Reviews",
        component: ReviewsView,
    },
    {
        path: '/moderation',
        name: "Moderation",
        component: ModerationView,
    },
    {
        path: '/forbidden',
        name: "Forbidden",
        component: ForbiddenView,
    },
    {
        path: '/:catchAll(.*)*',
        component: NotFoundView,
    }
];

const router: Router = createRouter({
    scrollBehavior(to, from, savedPosition) {
        // always scroll to top
        return { top: 0 }
    },
    history: createWebHistory(baseRoute),
    routes
});

// Navigation guard - checking for user permissions
router.beforeEach(async (to, from, next) => {
    const allowedRoutes = ['Login', 'Register', 'Home', 'News', 'Article', 'NotFound', 'Forbidden'];

    // Check if the user is logged in
    const isUserLogged = localStorage.getItem('isUserLogged') ? (localStorage.getItem('isUserLogged') == 'true') : false;
    if (isUserLogged) { 
        const token = await getToken();
        if (token == null) { 
            localStorage.setItem('isUserLogged', 'false');
            //next({ name: 'Login' });
            router.push({ name: 'Login' });
            return;
        }
        
        //refreshToken();
        useAuthStore().userData = await getCurrentUser();
        useAuthStore().userPerm = await getCurrentPerm();
        //console.log(useAuthStore().getDissallowedRoutes);
        //console.log(to.name);
        //console.log(useAuthStore().getDissallowedRoutes.includes(to.name as string));


        if (useAuthStore().getDissallowedRoutes.includes(to.name as string)) {
            router.replace({ name: 'Forbidden' });
            return;
        }

        //if (userAuthStore().userPerm)


        // Getting the number of articles
        const articles = await getArticles();
        useArticleStore().count = articles ? articles.length : 0;
        // Getting the articles
        useArticleStore().articles = await getArticles(useArticleStore().page);
        // End of setup

        next();
        return
    }

    // If one of this permitted routes is accessed, allow the user to navigate
    if (allowedRoutes.includes(to.name as string)) { 
        next(); 
        return;
    }

    // Check if the user has a valid token


    //useAuthStore().userData = await getCurrentUser();
    //useAuthStore().userPerm = await getCurrentUserPerm();

    //console.log(useAuthStore().userData);


    
    next();
});

export default router;
