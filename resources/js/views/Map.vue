<template>
    <div>
        <div ref="mapHolder" class="map-holder" />
    </div>
</template>

<script>
import API from '../Api';
import interact from 'interactjs';
import create from 'dom-create-element';

export default {
    name: 'Map',
    data () {
        return {
            event_id: this.$route.params.event_id
        };
    },
    methods: {
        init () {
            interact('.draggable')
                .on('tap', function (event) {
                    window.dispatchEvent(new CustomEvent('show-modal', { detail: event }));
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
        showModal (event) {
            console.log(event);
        }
    },
    async mounted () {
        this.showModal = this.showModal.bind(this);
        const res = await API.get('/api/event/map/' + this.event_id);
        const data = res.data;

        const mapData = JSON.parse(data.json);
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

        const permissions = await API.get('/api/map/register/');
        console.log(permissions);
        this.init();
    },
    beforeDestroy () {
        window.removeEventListener('show-modal', this.showModal, false);
    }
};
</script>
