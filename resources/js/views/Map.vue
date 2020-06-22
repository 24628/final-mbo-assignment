<template>
    <div>
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
            allowedIn: true,
            event_id: this.$route.params.event_id,
            mapData: null,
            mapModal: false,
            mapItemModal: null
        };
    },
    methods: {
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
            if (!this.allowedIn) return;
            this.mapItemModal = this.mapData.items.filter(i => i.id === id)[0];
            if (this.mapItemModal.user_id !== null) {
                alert('deze stand is bezet');
                return;
            }
            this.setModalState('mapModal');
        },
        setModalState (state) {
            this[state] = !this[state];
        }
    },
    async mounted () {
        this.showModal = this.showModal.bind(this);

        const res = await API.get('/api/event/map/' + this.event_id);
        const data = res.data;

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
