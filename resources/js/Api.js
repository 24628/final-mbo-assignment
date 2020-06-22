import axios from 'axios';
import create from 'dom-create-element';
import visible from 'ifvisible.js';

export default class API {
    constructor () {
        this.token = null;
        this.headers = null;
        this.interval = null;

        visible.setIdleDuration(600);// 5 min

        visible.idle(() => { // logout the user
            localStorage.removeItem('user');
            window.location.href = window.location.origin;
        });
    }

    /**
     * Start the Interval
     */
    static async startInterval () {
        this.interval = setInterval(async () => { // half hour refresh token
            const res = await axios.get(window.location.origin + '/api/refresh-token', { headers: this.headers });
            this.setToken(res.data.message);
        }, (1000 * 60) * 30);
    }

    /**
     * Stop the Interval
     */
    static stopInterval () {
        if (this.interval !== null) this.interval = null;
    }

    /**
     * @param token
     */
    static setToken (token) {
        this.token = token;
        this.headers = {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + token
        };
    }

    /**
     * Error handling to front end that creates a snackbar to give user feedback
     * @param data
     */
    static async errorCheck (data) {
        let error = '';
        const res = data.response;

        switch (res.status) {
        case 401:
            error = res.data.message;
            break;
        case 422:
            if (res.data.errors) {
                for (const key in res.data.errors) {
                    if (Object.prototype.hasOwnProperty.call(res.data.errors, key)) error += res.data.errors[key] + '</br>';
                }
            } else {
                for (const key in res.data) {
                    if (Object.prototype.hasOwnProperty.call(res.data, key)) error += res.data[key] + '</br>';
                }
            }
            break;
        case 403:
            error = res.data.message;
            window.location.href = window.location.origin + '/logout';
            break;
        case 404:
            error = 'Not found';
            break;
        case 500:
            error = 'Internal server error';
            break;
        default:
            error = 'An error occurs try again';
        }

        await this.generateHtml(error);
    }

    static async generateHtml (message) {
        const html = create({
            selector: 'div',
            styles: 'error-display',
            children: create({
                selector: 'p',
                html: message
            })
        });

        document.getElementById('body').appendChild(html);

        setTimeout(() => {
            html.style.opacity = 0;
        }, 5000);

        setTimeout(() => {
            html.parentNode.removeChild(html);
        }, 7000);
    }

    /**
     * @param url
     * @returns {Promise<AxiosResponse<any>>}
     */
    static async get (url) {
        try {
            return await axios.get(window.location.origin + url, { headers: this.headers });
        } catch (e) {
            await this.errorCheck(e);
        }
    }

    /**
     * @param data
     * @param url
     * @param update
     * @returns {Promise<AxiosResponse<any>>}
     */
    static async post (data, url, update = false) {
        try {
            if (update) return await axios.patch(window.location.origin + url, data, { headers: this.headers });
            return await axios.post(window.location.origin + url, data, { headers: this.headers });
        } catch (e) {
            await this.errorCheck(e);
        }
    }

    /**
     * @param url
     * @returns {Promise<AxiosResponse<any>>}
     */
    static async delete (url) {
        try {
            return await axios.delete(window.location.origin + url, { headers: this.headers });
        } catch (e) {
            await this.errorCheck(e);
        }
    }
}
