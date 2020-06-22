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
                            Inschrijven voor stand
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
                    <slot v-if="item !== null" name="body">
                        <h1>test</h1>

                        <div @click="openUrl">
                            De stand ticket
                        </div>
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
export default {
    data () {
        return {};
    },
    name: 'MapModal',
    props: ['item'],
    methods: {
        close () {
            this.$emit('close');
        },
        openUrl () {
            const pattern = /^((http|https|ftp):\/\/)/;
            let url = this.item.url;
            if (!pattern.test(url)) url = 'http://' + url;
            const win = window.open(url, '_blank');
            if (win) win.focus();
            else alert('Please allow popups for this website');
        }
    },
    watch: {
        item: async function (newVal, oldVal) {
            console.log(this.item);
        }
    }
};
</script>
