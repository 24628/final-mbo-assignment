<template>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-sidebar-content">
                <p class="admin-sidebar-title-divider">
                    Gebruikers:
                </p>
                <div class="admin-sidebar-links">
                    <button class="admin-sidebar-link" @click="setPage(3)">
                        Rollen en permissies
                    </button>
                    <button class="admin-sidebar-link" @click="setPage(2)">
                        Vind een gebruiker
                    </button>
                    <button class="admin-sidebar-link" @click="setModalState(`uploadExcelUsersModal`)">
                        Upload meerdere gebruikers (excel)
                    </button>
                    <button class="admin-sidebar-link" @click="checkEmails()">
                        Informeer gebruikers voor evenementen
                    </button>
                </div>
                <p class="admin-sidebar-title-divider">
                    Evenementen:
                </p>
                <div
                    v-for="event in events"
                    :key="event.name"
                    class="admin-sidebar-event-container"
                >
                    <div
                        class="admin-sidebar-event-list-item admin-sidebar-links"
                        @click="setSelectedEventId(event.id)"
                    >
                        <div
                            class="admin-sidebar-name-holder"
                            :class="{'open' : event.open}"
                            @click="event.open = !event.open"
                        >
                            <div
                                class="admin-sidebar-text-name"
                            >
                                {{ event.name }}
                            </div>
                            <div class="admin-sidebar-icon-container">
                                <div
                                    class="admin-sidebar-icon"
                                    @click="
                                        openEventSettings(
                                            event
                                        )
                                    "
                                >
                                    <i class="fas fa-cog" />
                                </div>
                                <div
                                    class="admin-sidebar-icon"
                                    @click="updateEvent(event)"
                                >
                                    <i class="fas fa-pencil" />
                                </div>
                                <div
                                    class="admin-sidebar-icon"
                                    title="evenment verweideren"
                                    @click="deleteEvent(event)"
                                >
                                    <i class="fas fa-trash" />
                                </div>
                            </div>
                        </div>
                        <div
                            :id="'event-' + event.id"
                            :class="{'closed' : !event.open}"
                            class="admin-sidebar-display-event-options"
                        >
                            <!--for looping programs-->
                            <div
                                v-for="prog in event.program"
                                v-if="event.program"
                                :key="'event.program-prog_' + prog.id"
                                class="admin-sidebar-event-option"
                                @click="updateDisplay(prog, 'program')"
                            >
                                <div
                                    v-if="prog.event_id === event.id"
                                    class="admin-sidebar-program-container"
                                >
                                    <div
                                        class="admin-sidebar-program-title"
                                    >
                                        {{ prog.name }}
                                    </div>
                                    <div
                                        class="admin-sidebar-program-action-container"
                                    >
                                        <div
                                            class="admin-sidebar-program-action-update"
                                            @click="updateProgram(prog.id)"
                                        >
                                            <i class="fas fa-pencil" />
                                        </div>
                                        <div
                                            class="admin-sidebar-program-action-delete"
                                            @click="deleteProgram(prog.id)"
                                        >
                                            <i class="fas fa-trash" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--for looping congress-->
                            <div
                                v-for="cong in event.congress"
                                v-if="event.congress"
                                :key="'event.congress-congress_' + cong.id"
                                class="admin-sidebar-event-option"
                                @click="updateDisplay(cong, 'congress')"
                            >
                                <div
                                    v-if="cong.event_id === event.id"
                                    class="admin-sidebar-program-container"
                                >
                                    <div
                                        class="admin-sidebar-program-title"
                                    >
                                        {{ cong.name }}
                                    </div>
                                    <div
                                        class="admin-sidebar-program-action-container"
                                    >
                                        <div
                                            class="admin-sidebar-program-action-update"
                                            @click="updateCongress(cong.id)"
                                        >
                                            <i class="fas fa-pencil" />
                                        </div>
                                        <div
                                            class="admin-sidebar-program-action-delete"
                                            @click="deleteCongress(cong.id)"
                                        >
                                            <i class="fas fa-trash" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="admin-action-container">
                                <div class="admin-create-program-sidebar">
                                    <div
                                        class="admin-sidebar-program-create-button"
                                        @click="
                                            setModalState(`createProgramModal`)
                                        "
                                    >
                                        <i class="fas fa-plus-circle" />
                                        Nieuw programma
                                    </div>
                                </div>

                                <div class="admin-create-program-sidebar">
                                    <div
                                        class="admin-sidebar-program-create-button"
                                        @click="
                                            setModalState(`createCongressModal`)
                                        "
                                    >
                                        <i class="fas fa-plus-circle" />
                                        Nieuw congres
                                    </div>
                                </div>

                                <div class="admin-create-program-sidebar">
                                    <div
                                        class="admin-sidebar-program-create-button"
                                        @click="
                                            goToMap(event.id)
                                        "
                                    >
                                        <i class="fas fa-plus-circle" />
                                        Plattegrond
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-sidebar-create-event-container">
                <div
                    class="admin-create-event-sidebar"
                    @click="setModalState(`createEventModal`)"
                >
                    <i
                        class="fas fa-plus-circle admin-sidebar-event-create-icon"
                    />Creëer Evenement
                </div>
            </div>
        </div>
        <div class="admin-main">
            <congress-display
                v-if="page === 1 && displayType === 'congress'"
                :congress="display"
                :event-title="(events.filter(v=>v.id === display.event_id)[0]).name"
            />
            <program-display
                v-if="page === 1 && displayType === 'program'"
                :program="display"
                :event-title="(events.filter(v=>v.id === display.event_id)[0]).name"
            />
            <div v-if="page === 1" class="admin-item-container-footer" />
            <find-user v-if="page === 2" />
            <rolls v-if="page === 3" />
        </div>

        <create-program-modal
            v-if="currentEvent"
            v-show="createProgramModal"
            :id="currentEvent.id"
            @close="setModalState(`createProgramModal`)"
        />

        <update-program-modal
            v-if="currentEvent"
            v-show="updateProgramModal"
            :id="updateProgramId"
            @close="setModalState(`updateProgramModal`)"
        />

        <create-congress-modal
            v-if="currentEvent"
            v-show="createCongressModal"
            :id="currentEvent.id"
            @close="setModalState(`createCongressModal`)"
        />

        <create-event-modal
            v-show="createEventModal"
            @close="setModalState(`createEventModal`)"
        />

        <upload-excel-users-modal
            v-show="uploadExcelUsersModal"
            @close="setModalState(`uploadExcelUsersModal`)"
        />

        <update-event-modal
            v-show="updateEventModal"
            :id="updateEventId"
            @close="setModalState(`updateEventModal`)"
        />

        <create-event-settings-modal
            v-if="settingsId"
            v-show="createEventSettingsModal"
            :id="settingsId"
            @close="setModalState(`createEventSettingsModal`)"
        />

        <update-event-settings-modal
            v-if="updateSettingsId"
            v-show="updateEventSettingsModal"
            :id="updateSettingsId"
            @close="setModalState(`updateEventSettingsModal`)"
        />

        <update-congress-modal
            v-show="updateCongressModal"
            :id="updateCongressId"
            @close="setModalState(`updateCongressModal`)"
        />
    </div>
</template>

<script>
import API from '@/js/Api';
import CreateEventModal from './components/modal/Create/CreateEventModal';
import CreateProgramModal from './components/modal/Create/CreateProgramModal';
import UploadExcelUsersModal from './components/modal/UploadExcelUsersModal';
import UpdateEventModal from './components/modal/Update/UpdateEventModal';
import FindUser from './components/FindUser';
import Rolls from './components/Rolls';
import CreateEventSettingsModal from './components/modal/Create/CreateEventSettingsModal';
import CreateCongressModal from './components/modal/Create/CreateCongressModal';
import CongressDisplay from './components/CongressDisplay';
import ProgramDisplay from './components/ProgramDisplay';
import UpdateEventSettingsModal from './components/modal/Update/UpdateEventSettingsModal';
import UpdateProgramModal from './components/modal/Update/UpdateProgramModal';
import UpdateCongressModal from './components/modal/Update/UpdateCongressModal';

export default {
    name: 'Admin',
    data () {
        return {
            page: 1,
            updateEventId: null,
            events: [],
            selectedEventId: null,
            updateCongressId: null,
            currentEvent: [],
            display: null,
            createEventModal: false,
            createProgramModal: false,
            updateEventSettingsModal: false,
            uploadExcelUsersModal: false,
            updateEventModal: false,
            createEventSettingsModal: false,
            createCongressModal: false,
            updateProgramModal: false,
            updateCongressModal: false,
            updateProgramId: null,
            settingsId: null,
            updateSettingsId: null,
            displayType: null,
            timeOut: null
        };
    },
    components: {
        CreateEventModal,
        CreateProgramModal,
        UploadExcelUsersModal,
        UpdateEventModal,
        FindUser,
        Rolls,
        CreateEventSettingsModal,
        CreateCongressModal,
        CongressDisplay,
        ProgramDisplay,
        UpdateEventSettingsModal,
        UpdateProgramModal,
        UpdateCongressModal
    },
    methods: {
        async setSelectedEventId (id) {
            this.selectedEventId = id;
            if (this.timeOut !== null) return;
            this.timeOut = await setTimeout(async () => {
                const data = await API.get('/api/admin/' + id);
                this.currentEvent = data.data;
            }, 1000);
        },
        setPage (id) {
            this.page = id;
        },
        updateDisplay (display, type) {
            this.displayType = type;
            this.display = display;
            this.setPage(1);
        },
        async setModalState (state) {
            this[state] = !this[state];
            await this.forceUpdate();
        },
        async forceUpdate () {
            const _this = this;
            await setTimeout(async () => {
                const data = await API.get('/api/admin');
                const events = data.data;
                for (let i = 0; i < events.length; i++) {
                    events[i].open = false;
                }
                if (_this.selectedEventId) await _this.setSelectedEventId(_this.selectedEventId);
                _this.events = events;
            }, 1000);
        },
        async deleteEvent (block) {
            block.open = !block.open;
            if (!confirm('Weet u zeker dat u dit event wilt verwiederen')) return;
            this.selectedEventId = null;
            this.currentEvent = null;

            API.delete('/api/event/' + block.id);
            await this.forceUpdate();
        },
        async updateProgram (id) {
            this.updateProgramId = id;
            await this.setModalState('updateProgramModal');
        },
        async deleteProgram (id) {
            API.delete('/api/program/' + id);
            await this.forceUpdate();
        },
        async deleteCongress (id) {
            API.delete('/api/congress/' + id);
            await this.forceUpdate();
        },
        async updateCongress (id) {
            this.updateCongressId = id;
            await this.setModalState('updateCongressModal');
        },
        async updateEvent (block) {
            block.open = !block.open;
            this.updateEventId = block.id;
            await this.setModalState('updateEventModal');
        },
        openEventSettings (event) {
            event.open = !event.open;
            if (event.settings === null) {
                this.settingsId = event.id;
                this.setModalState('createEventSettingsModal');
            } else {
                this.updateSettingsId = event.settings.id;
                this.setModalState('updateEventSettingsModal');
            }
        },
        goToMap (id) {
            window.location.href = window.location.origin + '/form/map/' + id;
        },
        checkEmails () {
            API.get('/api/notify');
        }
    },
    async mounted () {
        await this.forceUpdate();
    }
};
</script>
