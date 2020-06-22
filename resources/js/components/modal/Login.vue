<template>
    <form
        id="app"
        class="form"
        method="post"
        @submit="checkForm"
    >
        <h2 class="form-title">
            Login
        </h2>

        <div class="form-line">
            <label
                class="form-label"
                for="email"
            >E-mail</label>
            <input
                id="email"
                v-model="email"
                class="form-text-input"
                type="email"
                name="email"
                placeholder="Email"
            >
        </div>

        <div class="form-line">
            <label
                class="form-label"
                for="password"
            >Wachtwoord</label>
            <input
                id="password"
                v-model="password"
                class="form-text-input"
                type="password"
                name="password"
                placeholder="Password"
            >
            <div
                class="form-afterinput-link"
                @click="$emit('forgotpassword')"
            >
                Wachtwoord vergeten
            </div>
        </div>
        <div class="form-line form-line-hasbutton">
            <input
                type="submit"
                value="Login"
                class="form-button"
            >
        </div>
    </form>
</template>

<script>
import API from '../../Api';
import axios from 'axios';
import Api from '../../Api';

export default {
    data () {
        return {
            // TODO: remove this later
            email: '',
            password: ''
        };
    },
    methods: {
        checkForm: function (e) {
            axios.post(window.location.origin + '/api/login', {
                email: this.email,
                password: this.password
            }).then(response => {
                if (response.status === 200 && response.data.data) {
                    this.$user.data = JSON.parse(response.request.response);
                    API.setToken(this.$user.data.data.api_token);
                    API.startInterval();
                    this.$emit('loggedIn', response.request.response);
                    Api.generateHtml('successfully logged in');
                    this.$emit('close');
                } else {
                    API.generateHtml(response.data.message);
                }
            });
            e.preventDefault();
        }
    }
};
</script>
