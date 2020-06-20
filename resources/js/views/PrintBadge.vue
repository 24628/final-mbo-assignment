<template>
    <div class="print-main">
        <div class="badge-wrapper">
            <div class="badge-name" :style="{color: data.event.settings.color}">
                {{ data.user.name }}
            </div>
            <div v-if="data.user.profile.company" class="badge-company" :style="{color: data.event.settings.color}">
                {{ data.user.profile.company }}
            </div>
            <div v-else class="badge-role" :style="{color: data.event.settings.color}">
                {{ data.user.role.role_name }}
            </div>
            <div class="badge-date">
                {{ data.event.settings.date_start }}
            </div>
            <div class="badge-event-loc">
                {{ data.event.settings.location }}
            </div>
            <img class="badge-image" src="/img/badge-inspiratiedag.png" alt="">
            <div class="badge-color" :style="{backgroundColor: data.user.role.color}" />
        </div>
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
            data: null
        };
    },
    methods: {},
    async mounted () {
        const res = await API.get('/api/print/badge/' + this.rEventId);
        const data = res.data;
        this.data = res.data;
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
    .badge-image{
        width: inherit;
    }
    .badge-color{
        margin: 0;
        width: 100%;
        height: 120px;
        padding: 0;
        position: relative;
        top: -4px;
    }
    .badge-wrapper{
        width: 608px;
        margin: 0 auto;
    }
    .badge-name{
        position: absolute;
        font-weight: 900;
        font-size: 44px;
        top: 335px;
        width: inherit;
        text-align: center;
    }
    .badge-role,.badge-company{
        position: absolute;
        font-weight: 100;
        font-size: 40px;
        top: 385px;
        width: inherit;
        text-align: center;
        font-style: italic;
    }
    .badge-date{
        position: absolute;
        font-weight: 900;
        font-size: 26px;
        color: white;
        top: 470px;
        width: inherit;
        text-align: center;
    }
    .badge-event-loc{
        position: absolute;
        font-weight: 900;
        font-size: 26px;
        color: white;
        top: 500px;
        width: inherit;
        text-align: center;
    }
</style>
