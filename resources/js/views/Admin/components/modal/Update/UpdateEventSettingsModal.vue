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
                            Aanpassen van de evenement instellingen
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
                            <date-picker
                                v-model="date_start"
                                @update="(v)=>{date_start = v}"
                            />
                            <date-picker
                                v-model="date_end"
                                @update="(v)=>{date_end = v}"
                            />

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="location"
                                >Evenement locatie</label>
                                <input
                                    id="location"
                                    v-model="location"
                                    class="form-text-input"
                                    type="text"
                                    name="location"
                                    placeholder="Evenement locatie"
                                >
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="max_registrations"
                                >Maximale registraties</label>
                                <input
                                    id="max_registrations"
                                    v-model="max_registrations"
                                    class="form-text-input"
                                    type="text"
                                    name="max_registrations"
                                    placeholder="200"
                                >
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="visible_registrations"
                                >Waneer zijn de registraties zichtbaar</label>
                                <input
                                    id="visible_registrations"
                                    v-model="visible_registrations"
                                    class="form-text-input"
                                    type="text"
                                    name="visible_registrations"
                                    placeholder="70"
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
                                    placeholder="Kleur"
                                >
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="light_theme"
                                >Lichte thema</label>
                                <input
                                    id="light_theme"
                                    v-model="light_theme"
                                    class="form-text-input"
                                    type="checkbox"
                                    name="light_theme"
                                >
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
import DatePicker from '../../../../../components/datePicker';

export default {
    components: { DatePicker },
    data () {
        return {
            event_id: null,
            date_end: null,
            date_start: null,
            max_registrations: null,
            visible_registrations: null,
            location: null,
            color: '#000000',
            light_theme: false
        };
    },
    name: 'UpdateEventSettingsModal',
    props: ['id'],
    methods: {
        close () {
            this.$emit('close');
        },
        checkForm: function (e) {
            const data = {
                date_end: this.date_end,
                date_start: this.date_start,
                max_registrations: this.max_registrations,
                visible_registrations: this.visible_registrations,
                color: this.color,
                light_theme: this.light_theme,
                location: this.location
            };

            API.post(data, '/api/event-settings/' + this.id, true);

            this.close();

            e.preventDefault();
        },
        setFormData (res) {
            const data = res.data;

            this.date_end = data.date_end;
            this.date_start = data.date_start;
            this.max_registrations = data.max_registrations;
            this.visible_registrations = data.visible_registrations;
            this.color = data.color;
            this.light_theme = data.light_theme;
            this.location = data.location;
        }
    },
    watch: {
        id: async function (newVal, oldVal) {
            if (this.id) this.setFormData(await API.get('/api/event-settings/' + this.id));
        }
    },
    async mounted () {
        if (this.id) this.setFormData(await API.get('/api/event-settings/' + this.id));
    }
};
</script>
