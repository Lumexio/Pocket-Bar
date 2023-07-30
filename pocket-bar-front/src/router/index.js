import Vue from 'vue'
import VueRouter from 'vue-router'
import store from "@/store";
//import * as main from "@/main.js";
//import { nextTick } from 'vue/types/umd';
//Vue.use(main);
Vue.use(VueRouter)
Vue.use(store)


const routes = [
  {
    path: '/',
    redirect: {
      name: 'login',
      component: () => import(/*webpackChunkName: "Login" */'../components/global/login.vue'),
      meta: {
        layout: 'auth',
        name: 'auth'
      }
    }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import(/*webpackChunkName: "Login" */'../components/global/login.vue'),

    meta: {
      layout: 'auth',
      name: 'auth'
    }, beforeEnter(to, from, next) {
      /*if (store.state.token != null && (to.path === '/login' && from.path === '/')) {
        next({});
      } else*/ if ((to.path === '/login' || to.path === '/') && store.state.token === null) {

        next();
      } else if (store.state.token != null && (to.path === '/login' || to.path === '/')) {

        switch (store.getters.hasrol) {
          case 1:
            next({
              path: "/usuarios"
            });
            break;
          case 2:

            next({
              path: "/articulos"
            });
            break;
          case 3:

            next({
              path: "/ordenes"
            });
            break;
          case 4:

            next({
              path: "/mesero"
            });
            break;
          case 5:

            next({
              path: "/barra"
            });
            break;

          default:
            next({
              path: from.path
            });
            break;
        }

      }

    }
  },
  {
    path: '/home',
    name: 'home',
    component: () => import(/*webpackChunkName: "Home" */'../views/Home.vue'),

    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {

        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {

        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  //Cruds
  {
    path: '/crearusuario',
    name: 'crearusuario',
    component: () => import('../components/cruds/crearusuario.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },

  //Listas
  {
    path: '/usuarios',
    name: 'usuarios',
    component: () => import('../views/main-usuarios.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/articulos',
    name: 'artículos',
    component: () => import('../views/main-articulos.vue'), meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/guardias',
    name: 'guardias',
    component: () => import('../pwa-components/specialized-screens/main-guardias.vue'), meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/categorias',
    name: 'categorias',
    component: () => import('../views/main-categorias.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/mesas',
    name: 'mesas',
    component: () => import('../views/main-mesas.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/marcas',
    name: 'marcas',
    component: () => import('../views/main-marcas.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/tipos',
    name: 'tipos',
    component: () => import('../views/main-tipo.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/proveedores',
    name: 'proveedores',
    component: () => import('../views/main-proveedores.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },


  {
    path: '/historial',
    name: 'historial',
    component: () => import('../views/main-activitylog.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/ordenes',
    name: 'ordenes',
    component: () => import('../views/main-ordenes.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/pagos',
    name: 'pagos',
    component: () => import('../views/main-pagos.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/nominas',
    name: 'nominas',
    component: () => import('../components/payments/pagos-options-card.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/cortes',
    name: 'cortes',
    component: () => import('../components/payments/corte-main.vue'),
    meta: {
      authenticated: true,
      layout: 'default',
      name: 'default'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/mesero',
    name: 'mesero',
    component: () => import('../views/pwa-mains/main-mesero.vue'),
    meta: {
      authenticated: true,
      layout: 'pwa',
      name: 'pwa'
    },
    beforeEnter: (to, from, next) => {

      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '/barra',
    name: 'barra',
    component: () => import('../views/pwa-mains/main-barra.vue'),
    meta: {
      authenticated: true,
      layout: 'pwa',
      name: 'pwa'
    },
    beforeEnter: (to, from, next) => {
      let validado = to.matched.some(record => record.meta.authenticated);
      if (store.state.token) {
        next();
        if (validado == true && from.path !== '/') {
          next();
        }
      }
      else if (!validado) {
        next({ name: 'login' });
      }
      else {
        next({ name: 'login' });
      }

    }
  },
  {
    path: '*',
    name: 'Error',
    component: () => import('@/layouts/error.vue'),
    meta: {
      layout: 'error' // name of the layout
    }
  },
]



const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
})




export default router