<template>
    <div>
        <p v-if="!hasModal" class="no-map-text">Op het moment is er geen plattegrond ingevoerd. Probeer het op een later moment opnieuw. <br><br><a @click="goBack" href="#">Klik hier om terug te gaan</a></p>
        <div ref="mapHolder" class="map-holder" />

        <map-modal
            v-show="mapModal"
            :item="mapItemModal"
            @close="setModalState(`mapModal`)"
        />
    </div>
</template>

<script>
import API from '../Api';
import interact from 'interactjs';
import create from 'dom-create-element';
import MapModal from './MapModal';

export default {
    components: {
        MapModal
    },
    name: 'Map',
    data () {
        return {
            hasModal: false,
            allowedIn: true,
            event_id: this.$route.params.event_id,
            mapData: null,
            mapModal: false,
            mapItemModal: null
        };
    },
    methods: {
        goBack(e){
            e.preventDefault;
            this.$router.go(-1)
        },
        init () {
            const _this = this;
            interact('.draggable')
                .on('tap', function (e) {
                    _this.showModal(e.currentTarget.id);
                });
            window.addEventListener('show-modal', this.showModal, false);
        },
        createNewDomElement (counter, backgroundColorCodeItem, width, height, parentOffsetX, parentOffsetY) {
            const item = create({
                selector: 'div',
                id: `stand-id-${counter}`,
                styles: 'draggable',
                children: create({
                    selector: 'p',
                    html: `stand-id-${counter}`
                })
            });

            item.style.backgroundColor = backgroundColorCodeItem;
            item.style.width = width + 'px';
            item.style.height = height + 'px';

            item.style.webkitTransform =
                    item.style.transform =
                        'translate(' + parentOffsetX + 'px, ' + parentOffsetY + 'px)';
            item.setAttribute('data-x', parentOffsetX);
            item.setAttribute('data-y', parentOffsetY);

            const container = this.$refs.mapHolder;
            container.appendChild(item);
        },
        showModal (id) {
            if (this.allowedIn) {
                this.mapItemModal = this.mapData.items.filter(i => i.id === id)[0];
                if (this.mapItemModal.available === true) {
                    alert('deze stand is bezet');
                } else {
                    this.setModalState('mapModal');
                }
            }
        },
        setModalState (state) {
            this[state] = !this[state];
        }
    },
    async mounted () {
        this.showModal = this.showModal.bind(this);

        const res = await API.get('/api/event/map/' + this.event_id);
        const data = res.data;
        if (data === {}){
            this.hasModal = true;
        }

        const mapData = JSON.parse(data.json);
        this.mapData = mapData;

        const container = this.$refs.mapHolder;
        container.style.minWidth = mapData.map.width + 'px';
        container.style.minHeight = mapData.map.height + 'px';

        let counter = 1;
        mapData.items.forEach((el) => {
            this.createNewDomElement(
                counter,
                el.style.backgroundColor,
                el.style.width,
                el.style.height,
                el.positionFromParent.x,
                el.positionFromParent.y
            );
            counter++;
        });

        const resp = await API.get('/api/map/register/');
        this.allowedIn = resp.data;

        this.init();
    },
    beforeDestroy () {
        window.removeEventListener('show-modal', this.showModal, false);
    }
};
</script>

<style lang="scss">
    @import "@/sass/_variables";
    .no-map-text{
        position: absolute;
        max-width: 600px;
        width: calc(100vw - 64px);
        left: 50%;
        top: 50%;
        font-size: 24px;
        color: white;
        transform: translate(-50%,-50%);
        text-align: center;
        a{
            color: $theme-color;
        }
    }
</style>