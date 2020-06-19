<template>
    <div class="qr-container">
            qr code generator
        <qrcode-vue :value="value" :size="size" level="H"></qrcode-vue>
    </div>
</template>

<script>
    import QrcodeVue from 'qrcode.vue'

    export default {
        name: 'QRCode',
        components: {
            QrcodeVue,
        },
        data () {
            return {
                rEventId: this.$route.params.id,
                value: 'https://example.com',
                size: 300,
            };
        },
        methods: {

        },
        mounted () {

        }
    };
</script>

<style>
    .qr-container {
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        margin-top: 100px;
    }
</style>
