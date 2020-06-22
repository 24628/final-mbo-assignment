<template>
    <div class="profile-container">
        <div class="flex-wrapper profile-top">
            <h1>{{ name }}'s profiel</h1>
        </div>
        <form
            autocomplete="off"
            method="post"
            class="form-profile-edit"
            @submit.prevent="editProfile"
        >
            <div class="mobile-wrapper flex-grid column-desktop-full column-tablet-12">
                <div class="column-desktop-4 column-tablet-12 column-mobile-12 profile-border profile-main-wrapper">
                    <p class="profile-edit" :class="{'profile-save-padding': edit}">
                        <a
                            id="disableLink"
                            href="#"
                            @click.prevent="edit = !edit"
                        ><span>Profiel aanpassen </span><i
                            class="fas fa-cog"
                        /></a>
                    </p>
                    <input
                        v-if=" edit"
                        type="submit"
                        :class="{'profile-save': edit, 'invis': !edit}"
                        value="Profiel opslaan"
                    >
                    <div class="profile-foto" :style="!!image ? {backgroundImage: 'url('+image+')'} : ''" />
                    <input
                        v-if="edit"
                        type="file"
                        name="image"
                        class="profile-photo-input"
                        @change="onFileChange"
                    >
                    <div class="profile-main">
                        <p v-if="!edit" class="profile-name">
                            {{ name }}
                        </p>
                        <input
                            v-else
                            v-model="name"
                            type="text"
                            class="profile-name-edit"
                        >
                        <p v-if="!edit" class="profile-role">
                            {{ role_name }}
                        </p>
                        <select v-else v-model="role_id" class="form-text-input">
                            <option
                                v-for="userrole in roles"
                                :value="userrole.id"
                            >
                                {{ userrole.role_name }}
                            </option>
                        </select>
                        <div v-if="!!company && !edit && role_name !== 'Werkzoekende'">
                            <p class="profile-company">
                                Bedrijf
                            </p>
                            <p class="profile-company-name">
                                {{ company }}
                            </p>
                        </div>
                        <div v-if="!!company_func && !edit && role_name !== 'Werkzoekende'">
                            <p class="profile-function">
                                Functie
                            </p>
                            <p class="profile-company-name">
                                {{ company_func }}
                            </p>
                        </div>
                        <div v-if="edit && role_name !== 'Werkzoekende'">
                            <label class="profile-company" for="company">
                                Bedrijf
                            </label>
                            <input
                                id="company"
                                v-model="company"
                                type="text"
                                class="profile-name-edit"
                            >
                            <label class="profile-function" for="company_func">
                                Functie
                            </label>
                            <input
                                id="company-func"
                                v-model="company_func"
                                type="text"
                                class="profile-name-edit"
                            >
                        </div>
                        <hr v-if="!edit " class="profile-line-phone">
                        <input
                            v-if="edit && role_name === 'Werkzoekende'"
                            ref="cv"
                            type="file"
                            name="cv"
                            class="profile-cv-input"
                            @change="CvChangeEventHandler"
                        >
                        <button v-if="!edit && !!cvString && cvString.length && role_name === 'Werkzoekende'" class="download-cv" @click.prevent="showCV">
                            bekijk CV
                        </button>
                    </div>
                </div>
                <div class="column-desktop-8 column-tablet-12 column-mobile-12 profile-about-mobile profile-row-right">
                    <div class="profile-border profile-about">
                        <div class="profile-about-border">
                            <p class="profile-about-title">
                                Vertel over jezelf
                            </p>
                            <p
                                v-if="!edit"
                                class="profile-about-description"
                            >
                                {{ about }}
                            </p>
                            <textarea
                                v-else
                                v-model="about"
                                rows="10"
                                cols="50"
                                class="profile-about-description"
                            />
                        </div>
                        <table class="table-qr">
                            <tr>
                                <th colspan="3">
                                    Geregistreerde evenementen
                                </th>
                            </tr>
                            <div v-for="ev in registerEvents" :key="'event-r-' + ev.id">
                                <tr>
                                    <td>{{ ev.event.name }}</td>
                                    <td @click="viewEvent(ev.event_id)">
                                        Bekijk het evenement
                                    </td>
                                    <td @click="viewQRCode(ev.id)">
                                        Bekijk je QR code
                                    </td>
                                </tr>
                            </div>
                        </table>
                    </div>
                    <div class="profile-border profile-contact profile-border">
                        <p class="contact-title">
                            Contact
                        </p>
                        <div class="contact-logo" :class="{'contact-logo-edit' : edit}">
                            <div>
                                <a v-if="!edit && linkedin || edit" :href="linkedin" target="_blank">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 8c0 .557-.447 1.008-1 1.008s-1-.45-1-1.008c0-.557.447-1.008 1-1.008s1 .452 1 1.008zm0 2h-2v6h2v-6zm3 0h-2v6h2v-2.861c0-1.722 2.002-1.881 2.002 0v2.861h1.998v-3.359c0-3.284-3.128-3.164-4-1.548v-1.093z"
                                        />
                                    </svg>
                                </a>
                                <input
                                    v-if="edit"
                                    v-model="linkedin"
                                    type="url"
                                    class="contact-input"
                                    placeholder="https://www.linkedin.com/"
                                >
                            </div>
                            <div>
                                <a v-if="!edit && facebook || edit" :href="facebook">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 10h-2v2h2v6h3v-6h1.82l.18-2h-2v-.833c0-.478.096-.667.558-.667h1.442v-2.5h-2.404c-1.798 0-2.596.792-2.596 2.308v1.692z"
                                        />
                                    </svg>
                                </a>
                                <input
                                    v-if="edit"
                                    v-model="facebook"
                                    type="url"
                                    class="contact-input"
                                    placeholder="https://www.facebook.com/"
                                >
                            </div>
                            <div>
                                <a v-if="!edit && twitter || edit" :href="twitter">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.5 8.778c-.441.196-.916.328-1.414.388.509-.305.898-.787 1.083-1.362-.476.282-1.003.487-1.564.597-.448-.479-1.089-.778-1.796-.778-1.59 0-2.758 1.483-2.399 3.023-2.045-.103-3.86-1.083-5.074-2.572-.645 1.106-.334 2.554.762 3.287-.403-.013-.782-.124-1.114-.308-.027 1.14.791 2.207 1.975 2.445-.346.094-.726.116-1.112.042.313.978 1.224 1.689 2.3 1.709-1.037.812-2.34 1.175-3.647 1.021 1.09.699 2.383 1.106 3.773 1.106 4.572 0 7.154-3.861 6.998-7.324.482-.346.899-.78 1.229-1.274z"
                                        />
                                    </svg>
                                </a>
                                <input
                                    v-if="edit"
                                    v-model="twitter"
                                    type="url"
                                    class="contact-input"
                                    placeholder="https://twitter.com/"
                                >
                            </div>
                            <div>
                                <a v-if="!edit && contact_email || edit" :href="contact_email">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 2.02c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm0 12.55l-5.992-4.57h11.983l-5.991 4.57zm0 1.288l-6-4.629v6.771h12v-6.771l-6 4.629z"
                                        />
                                    </svg>
                                </a>
                                <input
                                    v-if="edit"
                                    v-model="contact_email"
                                    type="email"
                                    class="contact-input"
                                    placeholder="voorbeeld@email.nl"
                                >
                            </div>
                            <div>
                                <a v-if="!edit && phonenumber || edit" :href="phonenumber">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.5 17.311l-1.76-3.397-1.032.505c-1.12.543-3.4-3.91-2.305-4.497l1.042-.513-1.747-3.409-1.053.52c-3.601 1.877 2.117 12.991 5.8 11.308l1.055-.517z"
                                        />
                                    </svg>
                                </a>
                                <input
                                    v-if="edit"
                                    v-model="phonenumber"
                                    type="text"
                                    class="contact-input"
                                    placeholder="+31 6 1234567"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div ref="cvHolder" class="cv-holder" @click="emptyCV" />
    </div>
</template>

<script>
import API from '../Api';
import PDFJS from 'pdfjs-dist';
import { bytesToBase64 } from 'byte-base64';

export default {
    name: 'Profile',
    data () {
        return {
            name: null,
            data: {},
            roles: [],
            role_name: null,
            role: null,
            about: null,
            facebook: null,
            twitter: null,
            linkedin: null,
            phonenumber: null,
            contact_email: null,
            edit: false,
            cvString: null,
            company: null,
            image: null,
            user_id: null,
            profileExist: false,
            profileId: null,
            registerEvents: [],
            company_func: null,
        };
    },
    async mounted () {
        // TODO: add route.id so user can check other profiles
        const res = await API.get('/api/profile-check');

        if (!res.data) return;
        const data = res.data;
        this.user_id = data.id;
        const rE = await API.get('/api/register/event/user/' + this.user_id);
        this.registerEvents = rE.data;
        if (data.name) {
            this.name = data.name;
        }
        if (data.profile) {
            this.profileId = data.profile.id;
            this.profileExist = true;
            this.about = data.profile.about;
            this.image = data.profile.image;
            this.cvString = data.profile.cv;
            this.facebook = data.profile.facebook;
            this.twitter = data.profile.twitter;
            this.linkedin = data.profile.linkedin;
            this.phonenumber = data.profile.phonenumber;
            this.contact_email = data.profile.contact_email;
            this.company = data.profile.company;
        } else {
            this.profileExist = false;
        }

        this.role_name = data.role.role_name;
        this.role_id = data.role.id;

        const roles = await API.get('/api/selectable-roles');
        if (!roles.data) return (this.roles = 'Er zijn geen selecteerbare rollen.');
        this.roles = roles.data;
    },
    methods: {
        viewEvent (id) {
            window.location.href = window.location.origin + '/event/' + id;
        },
        viewQRCode (id) {
            window.location.href = window.location.origin + '/profile/qr-code/' + id;
        },
        emptyCV () {
            this.$refs.cvHolder.innerHTML = '';
        },
        CvChangeEventHandler () {
            const fileReader = new FileReader();
            let file = this.$refs.cv;
            file = file.files[0];
            let base64 = null;
            const _this = this;

            fileReader.onload = function () {
                const typedArray = new Uint8Array(this.result);
                base64 = bytesToBase64(typedArray);
                _this.cvString = base64;

                uploadBase64(base64);
            };
            const uploadBase64 = function (string) {
                API.post({ cv: string }, '/api/profile-cv');
            };

            fileReader.readAsArrayBuffer(file);
        },
        showCV () {
            let numPages = 0;
            const _this = this;
            const typedArray = Uint8Array.from(atob(this.cvString), c => c.charCodeAt(0));
            PDFJS.getDocument(typedArray).then(function (pdf) {
                numPages = pdf.numPages;
                for (let i = 0; i <= numPages; i++) {
                    pdf.getPage(i).then(handlePages);
                }
            });

            const handlePages = function (page) {
                const viewport = page.getViewport(1);
                const canvas = document.createElement('canvas');
                canvas.style.display = 'block';
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                page.render({ canvasContext: context, viewport: viewport });
                _this.$refs.cvHolder.appendChild(canvas);
            };
        },
        async onFileChange (e) {
            const imgData = this;
            const file = e.target.files[0];
            const reader = new FileReader();
            const imageTypes = ['png', 'jpeg'];
            if (!imageTypes.includes(file.type.split('/')[1])) {
                e.target.value = null;
                this.image = null;
                alert('Verkeerde image type toegestaan [png, jpeg]');
                return;
            }
            reader.onload = function () {
                imgData.image = this.result;
            };
            await reader.readAsDataURL(file);
        },
        async editProfile () {
            let data = {};
            const pObj = ['about', 'image', 'facebook', 'twitter', 'linkedin', 'phonenumber', 'contact_email', 'company'];
            const roleData = {
                id: this.role_id,
                user_id: this.user_id
            };
            pObj.forEach((i) => {
                if (this[i] === null) return;
                const tmp = {};
                tmp[i] = this[i];
                data = window._.merge(data, tmp);
            });
            if (this.profileExist) {
                await API.post(data, '/api/profile/' + this.profileId, true);
            } else {
                await API.post(data, '/api/profile');
            }

            await API.post(roleData, '/api/selectable-role-edit', true);

            await API.post({ name: this.name }, '/api/user/name/' + this.user_id, true);
            this.edit = false;
        }
    }
};
</script>

<style lang="scss">
    @import "@/sass/_variables";
    .cv-holder{
        position: absolute;
        top: $navbar-height;
        left: 50%;
        transform: translateX(-50%);
        height: max-content;
        max-height: calc(100vh - #{$navbar-height});
        z-index: 99;
        overflow-y: auto;
        background: $basic-gray;
    }
</style>
