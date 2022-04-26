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
  status: "Por entregar",
  notiLike: null,
  notiDanger: null,
  statcode: null,
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
  hastoken(state) { return state.token },
  hassuccess(state) { return state.notiLike },
  hasdanger(state) { return state.notiDanger },
  hasstatus(state) { return state.status },
  hasstatcode(state) { return state.statcode },
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
  setstatus(state, payload) { state.status = payload },
  setstatcode(state, payload) { state.statcode = payload },
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
  setstatus({ commit }) {
   commit('setstatus')
  },
  setstatcode({ commit }) {
   commit('setstatcode')
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