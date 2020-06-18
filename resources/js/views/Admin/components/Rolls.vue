<template>
    <div class="admin-display-page">
        <div class="admin-display-page-main">
            <div class="admin-display-page-main-title">
                titel
            </div>
            <div class="admin-display-page-main-container">
                <div
                    v-for="roll in rolls"
                    :key="roll.id"
                >
                    <div class="admin-roll-list-item">
                        <div class="admin-roll-list-item-title">
                            {{ roll.role_name }}
                        </div>
                        <div class="admin-roll-action-holder">
                            <input
                                :id="roll.id"
                                v-model="roll.selectable"
                                class="admin-roll-action-icon-holder"
                                type="checkbox"
                                title="Of de rol ge selecteerd kan worden bij het regristeren"
                                @change="handleChange"
                            >
                            <div
                                class="admin-roll-action-icon-holder"
                                title="Aanpassen van de rol"
                                @click="updateRoll(roll.id)"
                            >
                                <i class="fas fa-pencil admin-roll-action-icon" />
                            </div>
                            <div
                                class="admin-roll-action-icon-holder"
                                title="Verwijder de rol"
                                @click="deleteRoll(roll.id)"
                            >
                                <i class="fas fa-trash admin-roll-action-icon" />
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="admin-role-create-container"
                    @click="setModalState('createRollModal')"
                >
                    <i class="fas fa-plus-circle admin-role-create-icon" />Creëer rol
                </div>
            </div>
        </div>
        <div class="admin-item-container-footer" />

        <update-role-modal
            v-show="updateRollModal"
            :id="rollId"
            @close="setModalState(`updateRollModal`)"
        />

        <create-role-modal
            v-show="createRollModal"
            @close="setModalState(`createRollModal`)"
        />
    </div>
</template>

<script>
import API from '../../../Api';
import UpdateRoleModal from './modal/Update/UpdateRoleModal';
import CreateRoleModal from './modal/Create/CreateRoleModal';

export default {
    name: 'Rolls',
    data () {
        return {
            rolls: [],
            updateRollModal: false,
            createRollModal: false,
            rollId: null
        };
    },
    components: {
        UpdateRoleModal,
        CreateRoleModal
    },
    methods: {
        handleChange (e) {
            e.preventDefault();

            const target = e.target;
            if (target.checked !== false) {
                if (confirm('Weet u zeker dat elke gebruiker deze rol kan selecteren?')) {
                    API.get('/api/role-update-selectable/' + target.id);
                } else {
                    setTimeout(() => {
                        target.checked = false;
                    }, 100);
                }
            } else {
                API.get('/api/role-update-selectable/' + target.id);
            }
        },
        async setModalState (state) {
            this[state] = !this[state];
            const res = await API.get('/api/role');
            this.rolls = res.data;
        },
        updateRoll (id) {
            this.rollId = id;
            this.setModalState('updateRollModal');
        },
        deleteRoll (id) {
            if (confirm('Weet u zeker dat u deze rol wilt verweideren')) {
                API.delete('/api/role/' + id);
            }
        }
    },
    async mounted () {
        const res = await API.get('/api/role');
        this.rolls = res.data;
    }
};
</script>
