require('./bootstrap');

import {routes} from "./routes.js";
import { createApp } from 'vue';
import {NavigationFailureType, RouterLink, RouterView, START_LOCATION, createMemoryHistory, createRouter, createRouterMatcher, createWebHashHistory, createWebHistory, isNavigationFailure, loadRouteLocation, matchedRouteKey, onBeforeRouteLeave, onBeforeRouteUpdate, parseQuery, routeLocationKey, routerKey, routerViewLocationKey, stringifyQuery, useLink, useRoute, useRouter, viewDepthKey} from "vue-router"
import Welcome from './components/Welcome.vue';

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

const app = createApp({})

app.use(router)

app.component('welcome', Welcome)

app.mount('#app')