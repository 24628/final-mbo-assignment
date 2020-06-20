<template>
    <div class="admin-display-page">
        <div class="admin-display-page-main">
            <form
                class="form admin-form-find-user"
                method="post"
                @submit.prevent="checkForm"
            >
                <div class="form-line admin-form-find-user-input">
                    <input
                        id="search"
                        v-model="search"
                        class="form-text-input"
                        type="text"
                        name="search"
                        placeholder="Zoek profiel"
                    >
                </div>

                <div class="admin-from-submit">
                    <input
                        type="submit"
                        value="Zoek"
                        class="submit-btn admin-form-submit"
                    >
                </div>
            </form>
            <div class="admin-display-page-main-container">
                <div class="admin-find-user-display-table">
                    <div class="admin-find-user-table-cell">
                        Naam
                    </div>
                    <div class="admin-find-user-table-cell">
                        E-mail
                    </div>
                    <div class="admin-find-user-table-cell">
                        Rol
                    </div>
                    <div class="admin-find-user-table-cell-actions" />
                </div>
                <div
                    v-for="user in users"
                    :id="'admin-user-' + user.id"
                    :key="'admin-user-' + user.id"
                    class="admin-find-user-display-table"
                >
                    <div class="admin-find-user-table-cell">
                        {{ user.name }}
                    </div>
                    <div class="admin-find-user-table-cell">
                        {{ user.email }}
                    </div>
                    <div class="admin-find-user-table-cell">
                        {{ user.role.role_name }}
                    </div>
                    <div class="admin-find-user-table-cell-actions">
                        <div
                            class="admin-find-user-action-icon"
                            @click="updateUser(user.id)"
                        >
                            <i class="fas fa-pencil" />
                        </div>
                        <div
                            class="admin-find-user-action-icon"
                            @click="deleteUser(user.id)"
                        >
                            <i class="fas fa-trash" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-item-container-footer" />

        <update-user-modal
            v-if="user_id"
            v-show="updateUserModal"
            :id="user_id"
            @close="setModalState(`updateUserModal`)"
        />
    </div>
</template>

<script>
import API from '../../../Api';
import UpdateUserModal from './modal/Update/UpdateUserModal';

export default {
    data () {
        return {
            search: '',
            users: [],
            updateUserModal: false,
            user_id: null
        };
    },
    components: {
        UpdateUserModal
    },
    name: 'FindUser',
    methods: {
        async checkForm (e) {
            const data = {
                search: this.search
            };

            const response = await API.post(data, '/api/admin/search');
            this.users = response.data.message;

            e.preventDefault();
        },
        updateUser (id) {
            this.user_id = id;
            this.setModalState('updateUserModal');
        },
        async deleteUser (id) {
            if (!confirm('Weet u zeker dat u deze user wilt verweideren')) return;
            await API.delete('/api/admin/user/' + id);
            const item = document.getElementById('admin-user-' + id);
            item.parentNode.removeChild(item);
        },
        async setModalState (state) {
            this[state] = !this[state];
        }
    }
};
</script>
