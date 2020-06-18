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
                            Aanpassen ronde
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
            block_id: null,
            date_start: null,
            date_end: null
        };
    },
    name: 'CreateBlockModal',
    props: ['id'],
    methods: {
        close () {
            this.$emit('close');
        },
        checkForm: function (e) {
            const data = {
                date_start: this.date_start,
                date_end: this.date_end
            };

            API.post(data, '/api/block/' + this.block_id, true);

            this.close();

            e.preventDefault();
        },
        setFormData (data) {
            this.date_start = data.data.date_start;
            this.date_end = data.data.date_end;
            this.block_id = data.data.id;
        }
    },
    watch: {
        id: async function (newVal, oldVal) {
            this.setFormData(await API.get('/api/block/' + this.id));
        }
    },
    async mounted () {
        this.setFormData(await API.get('/api/block/' + this.id));
    }
};
</script>
