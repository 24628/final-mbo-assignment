<template>
    <div class="map-container">
        <div class="map-settings-container">
            <button class="button-create-item map-settings-container-items" @click="addNewItem">
                AddNewItem
            </button>
            <div class="map-settings-container-items map-settings-container-items-color">
                <hr>
                <label for="select-color">Select color</label>
                <input
                    id="select-color"
                    type="color"
                    name="select-color"
                    :value="backgroundColorCodeItem"
                    @change="setItemBackgroundColorData($event)"
                >
                <hr>
                <label for="map_width">Select Map Width in meters</label>
                <input
                    id="map_width"
                    type="text"
                    name="map_width"
                    :value="mapWidth"
                    @change="updateMapWidth($event)"
                >
                <label for="map_height">Select Map Height in meters</label>
                <input
                    id="map_height"
                    type="text"
                    name="map_height"
                    :value="mapHeight"
                    @change="updateMapHeight($event)"
                >
                <hr>
                <div v-if="selectedItem.id">
                    <label for="item_width">Zet Item Breedte in meters</label>
                    <input
                        id="item_width"
                        type="number"
                        name="item_width"
                        :value="(selectedItem.style.width / 50)"
                        @change="updateItemWidth($event, selectedItem.id)"
                    >
                    <label for="item_height">Zet Item Hoogte in meters</label>
                    <input
                        id="item_height"
                        type="number"
                        name="item_height"
                        :value="(selectedItem.style.height / 50)"
                        @change="updateItemHeight($event, selectedItem.id)"
                    >
                    <label for="item_url">Zet ticket url</label>
                    <input
                        id="item_url"
                        type="text"
                        name="item_url"
                        :value="selectedItem.url"
                        @change="updateItemUrl($event, selectedItem.id)"
                    >
                </div>
            </div>
            <button class="button-create-item map-settings-container-items" @click="storeMap">
                Store Map
            </button>
            <button class="button-create-item map-settings-container-items" @click="clearMap">
                Clear map
            </button>
            <button
                class="button-create-item map-settings-container-items"
                @click="setModalState(`modalMapForm`)"
            >
                Map info
            </button>
        </div>
        <div ref="mapHolder" class="map-holder" />
        <modal-map-form
            v-show="modalMapForm"
            @close="setModalState(`modalMapForm`)"
        />
    </div>
</template>

<script>
import interact from 'interactjs';
import create from 'dom-create-element';
import API from '../../Api';
import ModalMapForm from './ModalMapForm';

const meterToPixel = 50;

export default {
    components: {
        ModalMapForm
    },
    data () {
        return {
            event_id: this.$route.params.event_id,
            items: [],
            mapWidth: 30,
            mapHeight: 50,
            map: { width: (30 * meterToPixel), height: (50 * meterToPixel) },
            backgroundColorCodeItem: '#2195e8',
            counter: 0,
            copyState: false,
            selectedItem: {},
            timeoutPaste: undefined,
            timeoutUndo: undefined,
            modalMapForm: false,
            mapExist: false,
            mapId: null
        };
    },
    methods: {
        setModalState (state) {
            this[state] = !this[state];
        },
        init () {
            interact('.draggable')
                .resizable({
                    edges: { left: true, right: true, bottom: true, top: true },
                    listeners: {
                        move: this.resizeElement,
                        end: this.setDragPosition
                    },
                    modifiers: [
                        interact.modifiers.restrictEdges({
                            outer: 'parent'
                        }),
                        interact.modifiers.restrictSize({
                            min: { width: 20, height: 20 }
                        })
                    ],

                    inertia: true
                })
                .draggable({
                    inertia: true,
                    modifiers: [
                        interact.modifiers.restrictRect({
                            restriction: 'parent',
                            endOnly: true
                        }),
                        interact.modifiers.snap({
                            targets: [
                                interact.createSnapGrid({ x: 10, y: 10 })
                            ],
                            range: Infinity,
                            relativePoints: [{ x: 0, y: 0 }]
                        })
                    ],
                    autoScroll: true,
                    listeners: {
                        move: this.updatePosition,
                        end: this.setDragPosition
                    }
                })
                .on('doubletap', function (e) {
                    if (confirm('Wilt u dit item verweideren')) {
                        const id = e.currentTarget.id;
                        e.currentTarget.parentNode.removeChild(e.currentTarget);
                        window.dispatchEvent(new CustomEvent('delete-item', { detail: id }));
                    }
                    e.preventDefault();
                })
                .on('hold', function (event) {
                    window.dispatchEvent(new CustomEvent('update-background-color', { detail: event }));
                })
                .on('tap', function (event) {
                    window.dispatchEvent(new CustomEvent('set-copied-item', { detail: event }));
                });
            window.addEventListener('delete-item', this.deleteItemFromArray, false);
            window.addEventListener('update-background-color', this.updateItemBackgroundColor, false);
            window.addEventListener('keydown', this.startCopyPasteState, false);
            window.addEventListener('set-copied-item', this.setSelectedItem, false);

            const container = this.$refs.mapHolder;
            container.style.minWidth = this.map.width + 'px';
            container.style.minHeight = this.map.height + 'px';
        },
        updateMapWidth (event) {
            let width = event.target.value;
            if (isNaN(parseInt(width))) width = 30;
            this.mapWidth = width;
            this.map.width = this.mapWidth * meterToPixel;

            const container = this.$refs.mapHolder;
            container.style.minWidth = this.map.width + 'px';
        },
        updateMapHeight (event) {
            let height = event.target.value;
            if (isNaN(parseInt(height))) height = 50;
            this.mapHeight = height;
            this.map.height = this.mapHeight * meterToPixel;

            const container = this.$refs.mapHolder;
            container.style.minHeight = this.map.height + 'px';
        },
        updatePosition (event) {
            const target = event.target;
            const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
            const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;
            target.style.webkitTransform =
                    target.style.transform =
                        'translate(' + x + 'px, ' + y + 'px)';
            target.setAttribute('data-x', x);
            target.setAttribute('data-y', y);
        },
        addNewItem () {
            this.items.push(this.generateItemObject());
            this.createNewDomElement();
        },
        createNewDomElement (counter = this.counter, backgroundColorCodeItem = this.backgroundColorCodeItem, width = 150, height = 150, parentOffsetX = 0, parentOffsetY = 0) {
            const item = create({
                selector: 'div',
                id: `stand-id-${counter}`,
                styles: 'draggable',
                children: create({
                    selector: 'p',
                    html: `stand-id-${counter}`
                })
            });

            const paragraph = create({
                selector: 'div',
                id: 'dimensions',
                html: 'width: ' + (width / meterToPixel) + 'm,<br> height: ' + (height / meterToPixel) + 'm'
            });

            item.style.backgroundColor = backgroundColorCodeItem;
            item.style.width = width + 'px';
            item.style.height = height + 'px';
            item.appendChild(paragraph);

            item.style.webkitTransform =
                item.style.transform =
                    'translate(' + parentOffsetX + 'px, ' + parentOffsetY + 'px)';
            item.setAttribute('data-x', parentOffsetX);
            item.setAttribute('data-y', parentOffsetY);

            const container = this.$refs.mapHolder;
            container.appendChild(item);
        },
        deleteItemFromArray (event) {
            this.items = this.items.filter((obj) => {
                return obj.id !== event.detail;
            });
        },
        setDragPosition (event) {
            setTimeout(() => {
                this.items.forEach((element, index) => {
                    if (element.id === event.target.id) {
                        this.items[index].positionFromParent.x = parseInt(event.target.getAttribute('data-x'));
                        this.items[index].positionFromParent.y = parseInt(event.target.getAttribute('data-y'));
                        this.items[index].style.width = event.target.getBoundingClientRect().width;
                        this.items[index].style.height = event.target.getBoundingClientRect().height;
                    }
                });
            }, 100);
        },
        resizeElement (event) {
            const target = event.target;
            let x = (parseFloat(target.getAttribute('data-x')) || 0);
            let y = (parseFloat(target.getAttribute('data-y')) || 0);
            target.style.width = event.rect.width + 'px';
            target.style.height = event.rect.height + 'px';
            x += event.deltaRect.left;
            y += event.deltaRect.top;
            target.style.webkitTransform = target.style.transform =
                    'translate(' + x + 'px,' + y + 'px)';
            target.setAttribute('data-x', x);
            target.setAttribute('data-y', y);
            target.lastChild.innerHTML = 'width: ' + (event.rect.width / meterToPixel) + 'm,<br> height: ' + (event.rect.height / meterToPixel) + 'm';
        },
        setItemBackgroundColorData (event) {
            this.backgroundColorCodeItem = event.target.value;
        },
        updateItemBackgroundColor (event) {
            const el = event.detail.currentTarget;
            el.style.backgroundColor = this.backgroundColorCodeItem;
            setTimeout(() => {
                this.items.forEach((element, index) => {
                    if (element.id === el.id) this.items[index].style.backgroundColor = this.backgroundColorCodeItem;
                });
            }, 100);
        },
        startCopyPasteState (event) {
            if (event.code === 'KeyC' &&
                event.ctrlKey === true
            ) {
                this.copyState = true;
            }
            if (event.code === 'KeyV' &&
                event.ctrlKey === true &&
                this.selectedItem.id !== undefined &&
                this.copyState === true
            ) {
                this.copyState = false; // set copy state on false and start pasting the items
                if (this.timeoutPaste === undefined) {
                    this.items.push(this.generateItemObject(
                        this.selectedItem.style.width,
                        this.selectedItem.style.height,
                        this.selectedItem.positionFromParent.x,
                        this.selectedItem.positionFromParent.y,
                        this.selectedItem.style.backgroundColor
                    ));
                    const container = this.$refs.mapHolder;
                    container.appendChild(this.createNewDomElement(
                        this.counter,
                        this.selectedItem.style.backgroundColor,
                        this.selectedItem.style.width,
                        this.selectedItem.style.height,
                        this.selectedItem.positionFromParent.x,
                        this.selectedItem.positionFromParent.y
                    ));
                    this.timeoutPaste = setTimeout(() => {
                        this.timeoutPaste = undefined;
                    }, 500);
                }
            }
            if (event.code === 'KeyZ' &&
                event.ctrlKey === true &&
                this.copyState === true
            ) {
                if (this.items[this.items.length - 1].id !== this.selectedItem.id && this.timeoutUndo === undefined) {
                    this.items.pop();
                    const container = this.$refs.mapHolder;
                    container.removeChild(container.lastChild);
                    this.timeoutUndo = setTimeout(() => {
                        this.timeoutUndo = undefined;
                    }, 500);
                }
            }
            if (event.code === 'Escape') {
                this.clearCopyState();
            }
        },
        clearCopyState () {
            this.copyState = false; // clear copy state
            this.selectedItem = {};
        },
        setSelectedItem (event) {
            this.selectedItem = this.items.find(el => el.id === event.detail.currentTarget.id);
        },
        updateItemWidth (event, itemId) {
            const item = document.getElementById(itemId);
            item.style.width = (parseInt(event.target.value) * meterToPixel) + 'px';
            item.lastChild.innerHTML =
                    'width: ' + (parseInt(item.style.width.slice(0, -2)) / meterToPixel) + 'm,<br>' +
                    'height: ' + (parseInt(item.style.height.slice(0, -2)) / meterToPixel) + 'm';
            this.items.forEach(el => {
                if (el.id === itemId) el.style.width = parseInt(item.style.width.slice(0, -2));
            });
        },
        updateItemHeight (event, itemId) {
            const item = document.getElementById(itemId);
            item.style.height = (parseInt(event.target.value) * meterToPixel) + 'px';
            item.lastChild.innerHTML =
                    'width: ' + (parseInt(item.style.width.slice(0, -2)) / meterToPixel) + 'm,<br>' +
                    'height: ' + (parseInt(item.style.height.slice(0, -2)) / meterToPixel) + 'm';
            this.items.forEach(el => {
                if (el.id === itemId) el.style.height = parseInt(item.style.height.slice(0, -2));
            });
        },
        updateItemUrl (event, itemId) {
            console.log(event);
            this.items.forEach(el => {
                if (el.id === itemId) {
                    console.log(el);
                    el.url = event.target.value;
                }
            });
        },
        generateItemObject (width = 150, height = 150, x = 0, y = 0, backgroundColorCodeItem = this.backgroundColorCodeItem) {
            this.counter++;
            return {
                id: `stand-id-${this.counter}`,
                user_id: null,
                url: null,
                style: {
                    width: width,
                    height: height,
                    backgroundColor: backgroundColorCodeItem
                },
                positionFromParent: {
                    x: x,
                    y: y
                }
            };
        },
        storeMap () {
            const data = {
                name: 'some map name for now to test',
                json: JSON.stringify({
                    items: this.items,
                    map: this.map
                }),
                event_id: this.event_id
            };
            if (!this.mapExist) {
                API.post(data, '/api/map');
            } else {
                API.post(data, '/api/map/' + this.mapId, true);
            }
        },
        clearMap () {
            if (!confirm('Weet u zeker dat u de map wilt leeg maken')) return null;
            const container = this.$refs.mapHolder;
            container.innerHTML = '';
            this.counter = 0;
            this.items = [];
            this.clearCopyState();
        },
        async fillMapIfNeeded () {
            const res = await API.get('/api/map/' + this.$route.params.event_id);
            if (res.data === 'Error') return;
            if (!res.data.json) return;
            this.mapId = res.data.id;
            this.mapExist = true;
            const data = JSON.parse(res.data.json);
            this.mapHeight = data.map.height;
            this.mapWidth = data.map.width;
            const items = data.items;
            let counter = 1;
            items.forEach((el) => {
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
            this.items = items;
            this.counter = items.length;
        }
    },
    mounted () {
        this.deleteItemFromArray = this.deleteItemFromArray.bind(this);
        this.updateItemBackgroundColor = this.updateItemBackgroundColor.bind(this);
        this.startCopyPasteState = this.startCopyPasteState.bind(this);
        this.setSelectedItem = this.setSelectedItem.bind(this);
        this.init();
        this.fillMapIfNeeded();
    },
    beforeDestroy () {
        this.clearCopyState();
        window.removeEventListener('delete-item', this.deleteItemFromArray, false);
        window.removeEventListener('update-background-color', this.updateItemBackgroundColor, false);
        window.removeEventListener('keydown', this.startCopyPasteState, false);
        window.removeEventListener('set-copied-item', this.setSelectedItem, false);
    }
};
</script>

<style>
    .map-container {
        display: flex;
        flex-direction: row;
        width: 100%;
    }

    .map-holder {
        background-color: #f6f6f6;
        background-size: 50px 50px;
        background-image: linear-gradient(to right, grey 1px, transparent 1px), linear-gradient(grey 1px, transparent 1px);
    }

    .draggable {
        position: absolute;
        color: white;
        touch-action: none;
        user-select: none;
        -webkit-transform: translate(0px, 0px);
        transform: translate(0px, 0px);
    }

    .map-settings-container {
        min-width: 200px;
        max-width: 200px;
        display: flex;
        justify-content: flex-start;
        flex-direction: column;
        padding: 8px;
    }

    .button-create-item {
        height: 30px;
    }

    .map-settings-container-items-color {
        color: white;
    }

    .map-settings-container-items {
        padding-top: 4px;
        padding-bottom: 4px;
        width: 100%;
    }
</style>
