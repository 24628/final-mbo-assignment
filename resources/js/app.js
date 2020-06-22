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

// Global date Format fucntion
const formatDate = (hasTime, date_start, date_end = null)=>{
    const dateStart = new Date(date_start).getTime();
    const dateEnd = new Date(date_end).getTime();
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    if (dateEnd !== null && dateStart + 1000 * 60 * 60 * 24 > dateEnd) {

        return stringifyDate(dateStart);
    } else {
        return stringifyDate(dateStart) + '/' + stringifyDate(dateEnd);
    }

    function stringifyDate (date) {
        date = new Date(date);
        let formattedDate = date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
        if (hasTime){
            formattedDate += " ";
            let hours = date.getHours() + "";
            let minutes = date.getMinutes() + "";
            if (hours.length === 1){
                hours = "0" + hours;
            }
            if (minutes.length === 1){
                minutes = "0" + minutes;
            }
            formattedDate += hours + ":" + minutes;
        }
        return formattedDate;
    }
};
Vue.prototype.$formatDate = formatDate;

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
