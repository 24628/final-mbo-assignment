<template>
    <div class="full-page-form">
        <div class="form-holder">
            <form
                class="form"
                autocomplete="off"
                @submit.prevent="requestRegister"
            >
                <h2 class="form-title">
                    Register Account
                </h2>

                <div
                    v-if="isLoading"
                    class="form-line form-loading"
                >
                    <loading />
                </div>

                <div class="form-line">
                    <label
                        class="form-label"
                        for="name"
                    >Naam</label>
                    <input
                        id="name"
                        v-model="name"
                        class="form-text-input"
                        type="text"
                    >
                </div>
                <div class="form-line">
                    <label
                        class="form-label"
                        for="email"
                    >E-mail</label>
                    <input
                        id="email"
                        v-model="email"
                        class="form-text-input"
                        type="text"
                        required
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
                    >
                </div>
                <div class="form-line">
                    <label
                        class="form-label"
                        for="password_confirmation"
                    >Bevestig Wachtwoord</label>
                    <input
                        id="password_confirmation"
                        v-model="password_confirmation"
                        class="form-text-input"
                        type="password"
                    >
                </div>
                <div class="form-line">
                    <label
                        class="form-label"
                        for="role"
                    >Soort Bezoeker</label>
                    <select
                        id="role"
                        v-model="role"
                        class="form-text-input"
                    >
                        <option
                            v-for="userrole in roles"
                            :key="userrole.id"
                            :value="userrole.id"
                        >
                            {{ userrole.role_name }}
                        </option>
                    </select>
                </div>
                <div class="form-line">
                    <input id="terms" v-model="terms" type="checkbox">
                    <label class="form-label" for="terms">Algemene voorwaarden</label>
                </div>
                <div class="form-line form-line-hasbutton">
                    <input
                        type="submit"
                        class="form-button"
                        value="registreer account"
                    >
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import loading from '@/js/components/loading';
import API from '../../Api';

export default {
    name: 'Registration',
    data () {
        return {
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
            role: null,
            terms: false,
            isLoading: false,
            roles: []
        };
    },
    components: { loading },
    methods: {
        async requestRegister () {
            this.isLoading = true;
            const data = {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
                role_id: this.role,
                terms: this.terms
            };

            let response;
            try {
                response = await axios.post('/api/register', data);
            } catch (e) {
                await API.errorCheck(e);
            }
            localStorage.setItem('user', JSON.stringify(response.data));
            location.reload(true);
            this.$emit('loggedIn', response.data);
            window.location.href = window.location.origin;
        }
    },
    mounted () {
        axios
            .get('/api/selectable-roles')
            .then(response => (this.roles = response.data));
    }
};
</script>

<style scoped>

</style>
