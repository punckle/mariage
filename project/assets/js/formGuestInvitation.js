import Vue from "vue";
import Vuex from "vuex";
import FormGuestInvitation from "./Vue/FormGuestInvitation";
import axios from 'axios';

export default Vue;

Vue.use(Vuex)
Vue.prototype.$http = axios;

const store = new Vuex.Store({
    state: {},
    getters: {},
    mutations: {},
    actions: {}
})

new Vue({
    el: '#app_form-guest-invitation',
    template: '<FormGuestInvitation/>',
    components: { FormGuestInvitation },
    store: store,
    data: {}
})
