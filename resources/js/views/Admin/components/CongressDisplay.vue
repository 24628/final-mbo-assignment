<template>
    <div class="admin-main-program-container">
        <TitleDisplay
            :display="congress"
            :pretext="eventTitle +' | congres'"
        />
        <div class="admin-item-container">
            <div class="admin-item-list">
                <div class="admin-block-list">
                    <div
                        v-for="block in congressData.block"
                        :key="'congressData.block_' + block.id"
                        class="admin-block-list-holder"
                    >
                        <div
                            class="admin-block-list-header"
                        >
                            <div class="admin-block-date-holder" :class="{'open' : block.open}" @click="block.open = !block.open">
                                <div class="admin-block-list-date">
                                    {{ block.date_start.slice(5) }} / {{ block.date_end.slice(5) }}
                                </div>
                                <div
                                    class="admin-block-action"
                                    @click.prevent="displayBlockActions(block)"
                                >
                                    <i class="fas fa-chevron-left" />
                                </div>
                            </div>
                            <div
                                :id="'block-' + block.id"
                                class="admin-block-action-holder"
                            >
                                <div
                                    class="admin-block-action-icon-holder"
                                    title="add item"
                                    @click="addItemToBlock(block.id)"
                                >
                                    <i class="fas fa-plus-circle admin-block-action-icon" />
                                </div>
                                <div
                                    class="admin-block-action-icon-holder"
                                    title="update this block"
                                    @click="updateBlock(block.id)"
                                >
                                    <i class="fas fa-pencil admin-block-action-icon" />
                                </div>
                                <div
                                    class="admin-block-action-icon-holder"
                                    title="delete this block"
                                    @click="deleteBlock(block.id)"
                                >
                                    <i class="fas fa-trash admin-block-action-icon" />
                                </div>
                            </div>
                        </div>
                        <div
                            :id="'block-items-' + block.id"
                            class="admin-block-item-list"
                            :class="{'showing': block.open}"
                        >
                            <div
                                v-for="item in block.items"
                                :key="'block-items-' + block.id + '-' + item.id"
                                class="admin-item-list-holder in-congress"
                            >
                                <div>{{ item.name }}</div>
                                <div class="admin-sidebar-item-action-container">
                                    <div
                                        class="admin-sidebar-item-action-update"
                                        @click="updateItem(item.id)"
                                    >
                                        <i class="fas fa-pencil" />
                                    </div>
                                    <div
                                        class="admin-sidebar-item-action-delete"
                                        @click="deleteItem(item.id)"
                                    >
                                        <i class="fas fa-trash" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div
                        class="admin-block-list-add-block"
                        @click="setModalState(`createBlockModal`)"
                    >
                        <i class="fas fa-plus-circle admin-block-list-add-icon" />Ronde Toevoegen
                    </div>
                </div>
            </div>
            <div class="admin-item-display-container">
                <div
                    v-for="block in congress.block"
                    :key="'congress-block' + block.id"
                    class="admin-item-display-container-for"
                >
                    <div
                        v-for="item in block.items"
                        :key="'congress-block' + block.id + '-' + item.id"
                    >
                        <item :item="item" />
                        <div class="admin-item-hr" />
                    </div>
                </div>
            </div>
            <div class="admin-item-container-line" />
        </div>

        <create-block-modal
            v-show="createBlockModal"
            :id="congress.id"
            @close="setModalState(`createBlockModal`)"
        />

        <update-block-modal
            v-if="updateBlockId"
            v-show="updateBlockModal"
            :id="updateBlockId"
            @close="setModalState(`updateBlockModal`)"
        />

        <create-item-modal
            v-if="blockId"
            v-show="createItemModal"
            :id="blockId"
            @close="setModalState(`createItemModal`)"
        />

        <update-item-modal
            v-if="itemId"
            v-show="updateItemModal"
            :id="itemId"
            @close="setModalState(`updateItemModal`)"
        />
    </div>
</template>

<script>
import TitleDisplay from './TitleDisplay';
import Item from './Item';
import CreateBlockModal from './modal/Create/CreateBlockModal';
import API from '../../../Api';
import UpdateBlockModal from './modal/Update/UpdateBlockModal';
import CreateItemModal from './modal/Create/CreateItemModal';
import UpdateItemModal from './modal/Update/UpdateItemModal';

export default {
    name: 'CongressDisplay',
    components: {
        TitleDisplay,
        CreateBlockModal,
        UpdateBlockModal,
        CreateItemModal,
        UpdateItemModal,
        Item
    },
    // eslint-disable-next-line vue/prop-name-casing
    props: ['congress', 'event-title'],
    data () {
        return {
            congressData: [],
            createBlockModal: false,
            updateBlockModal: false,
            createItemModal: false,
            updateItemModal: false,
            blockId: null,
            itemId: null,
            updateBlockId: null
        };
    },
    mounted () {
        const congressData = JSON.parse(JSON.stringify(this.congress));
        for (let i = 0; i < congressData.block.length; i++) {
            congressData.block[i].open = false;
        }
        this.congressData = congressData;
    },
    methods: {
        async setModalState (state) {
            this[state] = !this[state];
            await this.forceUpdate();
        },
        async deleteBlock (id) {
            await API.delete('/api/block/' + id);
            await this.forceUpdate();
        },
        displayBlockItems (id, amount) {
            const target = document.getElementById('block-items-' + id);
            const height = target.getBoundingClientRect().height;
            if (height === 0) target.style.height = (60 * amount) + 'px';
            else target.style.height = '0px';
        },
        displayBlockActions (block) {
            block.open = !block.open;
            const target = document.getElementById('block-' + block.id);
            const width = target.getBoundingClientRect().width;
            if (width === 0) target.style.width = '120px';
            else target.style.width = '0px';
        },
        addItemToBlock (id) {
            this.blockId = id;
            this.setModalState('createItemModal');
        },
        updateBlock (id) {
            this.updateBlockId = id;
            this.setModalState('updateBlockModal');
        },
        updateItem (id) {
            this.itemId = id;
            this.setModalState('updateItemModal');
        },
        async deleteItem (id) {
            await API.delete('/api/item/' + id);
            await this.forceUpdate();
        },
        async forceUpdate () {
            const res = JSON.parse(JSON.stringify(await API.get('/api/admin/congress/' + this.congress.id)));
            const congressData = res.data;
            for (let i = 0; i < congressData.block.length; i++) {
                congressData.block[i].open = false;
            }
            this.congressData = congressData;
        }
    }
};
</script>
