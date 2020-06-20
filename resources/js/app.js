import './bootstrap';
import Vue from 'vue';

import Route from '@/js/routes.js';

import App from '@/js/App.vue';
import Api from './Api';

const user = {
    data: {}
};

user.install = function () {
    Object.defineProperty(Vue.prototype, '$user', {
        get () {
            return user;
        }
    });
};

Vue.use(user);

Route.beforeEach((to, from, next) => {
    const loginData = JSON.parse(localStorage.getItem('user'));
    if (loginData !== null) {
        user.data = loginData;
        Api.setToken(loginData.api_token);
        to.params.loggedIn = true;
    }
    if ((!!to.meta.auth && to.meta.auth) || (!!to.params.loggedIn && !to.params.loggedIn)) {
        if (user.data.api_key) {
            next();
        } else {
            to.params.loggedIn = false;
            next();
        }
    } else {
        next();
    }
});

const app = new Vue({
    el: '#app',
    router: Route,
    render: h => h(App)
});

export default app;
