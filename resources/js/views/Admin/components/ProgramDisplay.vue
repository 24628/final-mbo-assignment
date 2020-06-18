<template>
    <div class="admin-main-program-container">
        <TitleDisplay
            v-if="program"
            :pretext="eventTitle+' | programma'"
            :display="program"
        />
        <div class="admin-item-container">
            <div class="admin-item-list">
                <div class="admin-block-list">
                    <div
                        v-for="item in program.program_items"
                        :key="item.id"
                        class="admin-item-list-holder"
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
                <div>
                    <div
                        class="admin-block-list-add-block"
                        @click="setModalState(`createProgramItemModal`)"
                    >
                        <i class="fas fa-plus-circle admin-block-list-add-icon" />Programma item toevoegen
                    </div>
                </div>
            </div>
            <div class="admin-item-display-container">
                <div
                    v-for="item in program.program_items"
                    :key="'program_item_' + item.id"
                    class="admin-item-display-container-for"
                >
                    <item :item="item" />
                    <div class="admin-item-hr" />
                </div>
            </div>
            <div class="admin-item-container-line" />
        </div>

        <create-program-item-modal
            v-if="createProgramItemModal"
            v-show="createProgramItemModal"
            :id="program.id"
            @close="setModalState(`createProgramItemModal`)"
        />

        <update-program-item-modal
            v-show="updateProgramItemModal"
            :id="ItemId"
            @close="setModalState(`updateProgramItemModal`)"
        />
    </div>
</template>

<script>
import TitleDisplay from './TitleDisplay';
import Item from './Item';
import CreateProgramItemModal from './modal/Create/CreateProgramItemModal';
import UpdateProgramItemModal from './modal/Update/UpdateProgramItemModal';
import API from '../../../Api';

export default {
    name: 'Program',
    props: ['program', 'event-title'],
    components: {
        CreateProgramItemModal,
        UpdateProgramItemModal,
        TitleDisplay,
        Item
    },
    data () {
        return {
            createProgramItemModal: false,
            updateProgramItemModal: false,
            ItemId: null
        };
    },
    methods: {
        async setModalState (state) {
            this[state] = !this[state];
            await this.forceUpdate();
        },
        updateItem (id) {
            this.ItemId = id;
            this.setModalState('updateProgramItemModal');
        },
        async forceUpdate () {
            const res = await API.get('/api/admin/program/' + this.program.id);
            this.program = res.data;
        },
        async deleteItem (id) {
            await API.delete('/api/program-item/' + id);
            await this.forceUpdate();
        }
    }
};
</script>
