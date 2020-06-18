<template>
    <transition name="modal-fade">
        <div class="admin-modal-backdrop">
            <div
                class="admin-modal"
                role="dialog"
                aria-labelledby="modalTitle"
                aria-describedby="modalDescription"
            >
                <header
                    id="modalTitle"
                    class="admin-modal-header"
                >
                    <slot name="header">
                        <p
                            class="admin-modal-title"
                        >
                            Aanpassen rol
                        </p>

                        <button
                            type="button"
                            class="admin-modal-btn-close"
                            aria-label="Close modal"
                            @click="close"
                        >
                            x
                        </button>
                    </slot>
                </header>
                <section
                    id="modalDescription"
                    class="admin-modal-body"
                >
                    <slot name="body">
                        <form
                            class="form"
                            method="post"
                            @submit.prevent="checkForm"
                        >
                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="role_name"
                                >Rol Naam</label>
                                <input
                                    id="role_name"
                                    v-model="role_name"
                                    class="form-text-input"
                                    type="text"
                                    name="role_name"
                                    placeholder="Rol naam"
                                >
                            </div>
                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="color"
                                >Kleur</label>
                                <input
                                    id="color"
                                    v-model="color"
                                    class="form-color-input"
                                    type="color"
                                    name="color"
                                    placeholder="kleur"
                                >
                            </div>
                            <div class="form-line">
                                <dropdown
                                    placeholder="rol permissies"
                                    :options="options"
                                    :current="current"
                                    @setCurrent="setCurrent"
                                />
                            </div>

                            <div class="form-line admin-from-submit">
                                <input
                                    type="submit"
                                    value="Indienen"
                                    class="submit-btn admin-form-submit"
                                >
                            </div>
                        </form>
                    </slot>
                </section>
                <footer class="admin-modal-footer">
                    <slot name="footer">
                        <button
                            type="button"
                            class="admin-modal-btn-green"
                            aria-label="Close modal"
                            @click="close"
                        >
                            Sluiten
                        </button>
                    </slot>
                </footer>
            </div>
        </div>
    </transition>
</template>

<script>
import API from '../../../../../Api';
import dropdown from '@/js/components/dropdown';

export default {
    components: { dropdown },
    data () {
        return {
            role_id: null,
            role_name: '',
            color: '',
            permissions: [],
            options: [],
            current: []
        };
    },
    name: 'UpdateRollModal',
    props: ['id'],
    methods: {
        close () {
            this.$emit('close');
        },
        setCurrent (v) {
            this.current = v;
        },
        checkForm: function (e) {
            const data = {
                color: this.color,
                role_name: this.role_name,
                permissions: this.current
            };

            API.post(data, '/api/role/' + this.role_id, true);

            this.close();

            e.preventDefault();
        },
        setFormData (data) {
            const res = data.data;
            this.role_id = res.id;
            this.color = res.color;
            this.role_name = res.role_name;
            this.current = JSON.parse(res.permissions);
        }
    },
    watch: {
        id: async function (newVal, oldVal) {
            if (this.id) this.setFormData(await API.get('/api/role/' + this.id));
        }
    },
    async mounted () {
        const data = await API.get('/api/permissions');
        const options = this.options;
        for (const key in data.data.message) {
            if (Object.prototype.hasOwnProperty.call(data.data.message, key) && key.substring(0, 2) === '__') {
                options.push(data.data.message[key]);
            }
        }
        this.options = options;
        if (this.id) this.setFormData(await API.get('/api/role/' + this.id));
    }
};
</script>
