<template>
    <div class="homepage-full">
        <div class="flex-wrapper">
            <h1>Evenementen</h1>
        </div>

        <div class="flex-wrapper homepage-inner">
            <div class="home-events flex-grid">
                <div
                    v-for="item in data"
                    :key="item.id"
                    class="home-event-outer column-desktop-4 column-tablet-6 column-mobile-12"
                >
                    <div
                        class="home-event"
                        @click="$router.push('event/' + item.id)"
                    >
                        <div
                            class="home-event-background"
                            :style="{backgroundColor: item.settings.color, backgroundImage: 'url(' + item.image + ')'}"
                        />
                        <div class="home-event-content">
                            <h2>{{ item.name }}</h2>
                            <hr
                                class="home-divider"
                                :style="{borderColor: item.settings.color}"
                            >
                            <p class="home-description">
                                {{ item.description }}
                            </p>
                            <hr class="home-divider-2">
                            <div class="home-event-details">
                                <div class="flex-grid">
                                    <p class="home-date column-desktop-6 column-tablet-6 column-mobile-12">
                                        {{ formatDate(item.settings) }}
                                    </p>
                                    <p class="home-location column-desktop-6 column-tablet-6 column-mobile-12">
                                        {{ item.settings.location }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import API from '@/js/Api';

export default ({
    async mounted () {
        const response = await API.get('/api/event-overview');
        this.data = response.data;
    },
    data () {
        return {
            data: []
        };
    },
    methods: {
        formatDate (settings) {
            const dateStart = new Date(settings.date_start).getTime();
            const dateEnd = new Date(settings.date_end).getTime();
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            if (dateStart + 1000 * 60 * 60 * 24 > dateEnd) {
                return stringifyDate(dateStart);
            } else {
                return stringifyDate(dateStart) + '/' + stringifyDate(dateEnd);
            }

            function stringifyDate (date) {
                date = new Date(date);
                const formattedDate = date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
                return formattedDate;
            }
        }
    }
});
</script>
