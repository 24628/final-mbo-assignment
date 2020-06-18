<template>
    <transition name="modal-fade">
        <div class="admin-modal-backdrop">
            <div
                class="admin-modal"
                role="dialog"
                aria-labelledby="modalTitle"
                aria-describedby="modalDescription"
            >
                <header
                    id="modalTitle"
                    class="admin-modal-header"
                >
                    <slot name="header">
                        <p
                            class="admin-modal-title"
                        >
                            Upload Excel bestand
                        </p>

                        <button
                            type="button"
                            class="admin-modal-btn-close"
                            aria-label="Close modal"
                            @click="close"
                        >
                            x
                        </button>
                    </slot>
                </header>
                <section
                    id="modalDescription"
                    class="admin-modal-body"
                >
                    <slot name="body">
                        <input
                            id="my_file_input"
                            ref="excell"
                            type="file"
                        >
                        <button
                            class="button-create-item map-settings-container-items"
                            @click="parseFileInPut"
                        >
                            Upload
                        </button>
                    </slot>
                </section>
                <footer class="admin-modal-footer">
                    <slot name="footer">
                        <button
                            type="button"
                            class="admin-modal-btn-green"
                            aria-label="Close modal"
                            @click="close"
                        >
                            Sluiten
                        </button>
                    </slot>
                </footer>
            </div>
        </div>
    </transition>
</template>

<script>
/* global XLSX */
import API from '../../../../Api';

export default {
    data () {
        return {};
    },
    name: 'UploadExcelUserModal',
    methods: {
        close () {
            this.$emit('close');
        },
        parseFileInPut () {
            const parseExcel = function (file) {
                const reader = new FileReader();
                let array = [];

                reader.onload = function (e) {
                    const data = e.target.result;
                    const workbook = XLSX.read(data, {
                        type: 'binary'
                    });

                    workbook.SheetNames.forEach(function (sheetName) {
                        array = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                    });

                    const postData = JSON.stringify(array);
                    API.post(postData, '/api/admin/excel');
                };

                reader.onerror = function (ex) {
                    console.log(ex);
                };

                reader.readAsBinaryString(file);
            };

            let file = this.$refs.excell;
            file = file.files[0];
            parseExcel(file);
        }
    }
};
</script>
