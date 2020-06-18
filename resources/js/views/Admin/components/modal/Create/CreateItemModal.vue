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
                            Creëer spreker
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
                                    for="name"
                                >Naam spreker</label>
                                <input
                                    id="name"
                                    v-model="name"
                                    class="form-text-input"
                                    type="text"
                                    name="name"
                                    placeholder="naam spreker"
                                >
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="description"
                                >Omschrijving</label>
                                <textarea
                                    id="description"
                                    v-model="description"
                                    class="form-text-input"
                                    type="text"
                                    name="description"
                                    placeholder="omschrijving spreker"
                                />
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="type"
                                >Type spreker</label>
                                <select
                                    id="type"
                                    v-model="type"
                                >
                                    <option value="keynotes">
                                        Keynote spreker
                                    </option>
                                    <option value="speaker">
                                        Spreker
                                    </option>
                                    <option value="none">
                                        Geen
                                    </option>
                                </select>
                            </div>

                            <date-picker
                                v-if="type !== 'speaker'"
                                v-model="date_start"
                                @update="(v)=>{date_start = v}"
                            />
                            <date-picker
                                v-if="type !== 'speaker'"
                                v-model="date_end"
                                @update="(v)=>{date_end = v}"
                            />

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="active"
                                >Zet de spreker op actief</label>
                                <input
                                    id="active"
                                    v-model="active"
                                    class="form-text-input"
                                    type="checkbox"
                                    name="active"
                                    placeholder="Event name"
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
            name: null,
            type: null,
            description: null,
            date_start: null,
            date_end: null,
            active: false
        };
    },
    name: 'CreateItemModal',
    props: ['id'],
    methods: {
        close () {
            this.$emit('close');
        },
        checkForm: function (e) {
            let data;

            if (this.type !== 'speaker') {
                data = {
                    name: this.name,
                    type: this.type,
                    description: this.description,
                    block_id: this.id,
                    date_start: this.date_start,
                    date_end: this.date_end,
                    active: this.active
                };
            } else {
                data = {
                    name: this.name,
                    type: this.type,
                    description: this.description,
                    block_id: this.id,
                    active: this.active
                };
            }

            API.post(data, '/api/item');

            this.name = null;
            this.type = null;
            this.description = null;
            this.date_start = null;
            this.date_end = null;
            this.active = false;

            this.close();

            e.preventDefault();
        }
    }
};
</script>
