<template>
    <div class="print-main">
        <h1>test</h1>

    </div>
</template>

<script>
    import API from '../Api';

    export default {
        name: 'PrintBadge',
        components: {},
        data () {
            return {
                rEventId: this.$route.params.id,
            };
        },
        methods: {},
        async mounted () {
            const res = await API.get('/api/print/badge/' + this.rEventId);
            const data = res.data;
            console.log(data);
        }
    };
</script>

<style>

    .print-main {
        position: absolute;
        height: 100vh;
        width: 100vw;
        z-index: 100 !important;
        background: white;
        margin-top: -80px;
        border-radius: unset !important;
    }
</style>
