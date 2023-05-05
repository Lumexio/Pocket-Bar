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
        order: 0,
        rol: null,
        userId: null,
        status: "Por entregar",
        notiLike: null,
        notiLikeMessage: "",
        notiDangerMessage: "",
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
        hassuccessMessage(state) { return state.notiLikeMessage },
        hasdanger(state) { return state.notiDanger },
        hasdangerMessage(state) { return state.notiDangerMessage },
        hasstatus(state) { return state.status },
        hasstatcode(state) { return state.statcode },
        hasdarkflag(state) { return state.darkflag },
        getUserId(state) { return state.userId },
        gettickets(state) { return state.ticketArray },
        hasorders(state) { return state.order },
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
        setorder(state, payload) { state.order = payload },
        setsuccess(state, payload) { state.notiLike = payload },
        setsuccessMessage(state, payload) { state.notiLikeMessage = payload },
        setdanger(state, payload) { state.notiDanger = payload },
        setdangerMessage(state, payload) { state.notiDangerMessage = payload },
        setstatus(state, payload) { state.status = payload },
        setstatcode(state, payload) { state.statcode = payload },
        setdarkflag(state, payload) { state.darkflag = payload },
        settickets(state, payload) { state.ticketArray = payload },
        resetTicket: state => { state.ticketArray = [] },
        RESET: state => {
            state.token = null,
                state.recharge = 0,
                state.order = 0,
                state.rol = null,
                state.status = "Por entregar",
                state.notiLike = null,
                state.notiLikeMessage = "",
                state.notiDanger = null,
                state.notiDangerMessage = "",
                state.statcode = null,
                state.ticketArray = null
            state.order = 0
        }
    },

    actions: {
        increment({ commit }) {
            commit('increment')
        },
        setorder({ commit }) {
            commit('setorder')
        },
        setdanger({ commit }) {
            commit('setdanger')
        },
        setdangerMessage({ commit }) {
            commit('setdangerMessage')
        },
        setsuccess({ commit }) {
            commit('setsuccess')
        },
        setsuccessMessage({ commit }) {
            commit('setsuccessMessage')
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