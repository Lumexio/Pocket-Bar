// src/store.js (Vue CLI 1.x & 2.x) oder src/store/index.js (Vue CLI 3.x or newer)

import Vue from 'vue';
import Vuex from 'vuex';
import Axios from 'axios';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

const getDefaultState = () => {
 return {
  token: null,
  recharge: 0,
  rol: null,
  notiLike: null,
  notiDanger: null,
 };
};

export default new Vuex.Store({
 strict: false,
 plugins: [createPersistedState()],
 state: getDefaultState(),

 getters: {
  isLoggedIn: state => {
   return state.token;
  },
  counter(state) { return state.recharge },
  hasrol(state) { return state.rol },
  hassuccess(state) { return state.notiLike },
  hasdanger(state) { return state.notiDanger },
 },

 mutations: {
  SET_TOKEN: (state, token) => {
   state.token = token;
  },
  increment(state, payload) {
   // mutate state
   state.recharge += payload;
  },
  setrol(state, payload) { state.rol = payload },
  setsuccess(state, payload) { state.notiLike = payload },
  setdanger(state, payload) { state.notiDanger = payload },
  RESET: state => {
   Object.assign(state, getDefaultState());
  }
 },

 actions: {
  increment({ commit }) {
   commit('increment')
  },
  setdanger({ commit }) {
   commit('setdanger')
  },
  setsuccess({ commit }) {
   commit('setsuccess')
  },
  login: ({ commit, dispatch }, { token }) => {

   commit('SET_TOKEN', token);
   dispatch;
   // set auth header
   Axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  },
  logout: ({ commit }) => {
   commit('RESET', '');
  }
 }
});