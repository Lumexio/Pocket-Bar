// src/store.js (Vue CLI 1.x & 2.x) oder src/store/index.js (Vue CLI 3.x or newer)

import Vue from 'vue';
import Vuex from 'vuex';
import Axios from 'axios';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

const getDefaultState = () => {
    return {
        darkflag: false,
        token: null,
        recharge: 0,
        rol: null,
        userId: null,
        status: "Por entregar",
        notiLike: null,
        notiDanger: null,
        statcode: null,
        ticketArray: [],
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
        hasdarkflag(state) { return state.darkflag },
        getUserId(state) { return state.userId },
        gettickets(state) { return state.ticketArray },
    },

    mutations: {
        SET_TOKEN: (state, token) => {
            state.token = token;
        },
        setUserId(state, userId) {
            state.userId = userId;
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
        setdarkflag(state, payload) { state.darkflag = payload },
        settickets(state, payload) { state.ticketArray = payload },
        resetTicket: state => { state.ticketArray = [] },
        RESET: state => {
            state.token = null,
                state.recharge = 0,
                state.rol = null,
                state.status = "Por entregar",
                state.notiLike = null,
                state.notiDanger = null,
                state.statcode = null,
                state.ticketArray = null
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
        setdarkflag({ commit }) {
            commit('setdarkflag')
        },
        settickets({ commit }) {
            commit('settickets')
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