<template>
    <b-container class="code-management-page">
        <!-- begin::page-header -->
        <div class="page-header d-flex justify-content-between align-items-baseline">
            <h4>Code Management</h4>
            <b-button @click="showAddEditModal" variant="primary" size="md">Create</b-button>
        </div>
        <!-- end::page-header -->

        <b-row class="mt-3">
            <b-col cols="12">
                <b-card no-body>
                    <b-card-header>
                        <!-- Search -->
                        <b-input-group class="mt-3">
                            <b-form-input placeholder="search in codes, names, descriptions ..." v-model="codes.search_key" @keyup.enter="getAllCodes()"></b-form-input>
                            <b-input-group-append>
                                <b-button variant="success" @click="getAllCodes">
                                    search
                                </b-button>
                            </b-input-group-append>
                        </b-input-group>
                    </b-card-header>
                    <b-card-body>
                        <b-table
                            :responsive="true"
                            striped
                            hover
                            :show-empty="true"
                            empty-text="No data"
                            :fields="codes.fields"
                            :items="codes.data">
                            <template v-slot:cell(id)="data">
                                {{data.index + 1}}
                            </template>
                            <template v-slot:cell(enable)="data">
                                {{data.item.enable ? 'Yes' : 'No'}}
                            </template>
                            <template v-slot:cell(created_at)="data">
                                {{data.item.created_at | moment('from','now')}}
                            </template>

                            <template v-slot:cell(updated_at)="data">
                                <span
                                    v-if="data.item.updated_at ">{{data.item.updated_at | moment('from','now')}}</span>
                            </template>
                            <template v-slot:cell(actions)="data">
                                <b-button size="sm"
                                          variant="warning"
                                          @click="showAddEditModal('edit', data.item)">
                                    Edit
                                </b-button>
                                <b-button size="sm"
                                          variant="danger"
                                          @click="deleteCode(data.item)">
                                    Delete
                                </b-button>
                                <b-button size="sm"
                                          variant="primary"
                                          @click="getAllCustomers(data.item)">
                                    Customers
                                </b-button>
                            </template>
                        </b-table>
                        <b-pagination class="d-flex justify-content-center"
                                      v-model="codes.meta.current_page"
                                      :total-rows="codes.meta.total"
                                      :per-page="codes.meta.per_page"/>
                        <b-overlay :show="loading" rounded no-wrap>
                        </b-overlay>
                    </b-card-body>
                </b-card>
            </b-col>
        </b-row>
        <!-- Add Modal -->
        <b-modal v-model="codeModal.visible"
                 centered
                 hide-footer
                 hide-header-close
                 :title="codeModal.type === 'edit' ? 'Edit Code' : 'Create Code'">
            <b-form-input v-model="codeModal.data.name"
                          placeholder="name :"/>
            <b-form-input class="mt-3"
                          v-model="codeModal.data.code"
                          placeholder="Code :"/>
            <b-form-input class="mt-3"
                          v-model="codeModal.data.capacity"
                          placeholder="Capacity :"/>
            <b-form-checkbox class="mt-3"
                             type="checkbox"
                             v-model="codeModal.data.enable">
                Enable
            </b-form-checkbox>
            <b-form-textarea class="mt-3"
                             v-model="codeModal.data.description"
                             placeholder="Description :"/>

            <b-row class="mt-5">
                <b-col cols="6">
                    <b-button variant="primary" @click="submitCode" block>
                        <span v-if="codeModal.type==='edit'">Edit</span>
                        <span v-else>Create</span>
                    </b-button>
                </b-col>
                <b-col cols="6">
                    <b-button variant="secondary" @click="hideAddEditModal" block>
                        Cancel
                    </b-button>
                </b-col>
            </b-row>
        </b-modal>
        <!-- END :: Add Modal -->

        <!-- Customer Modal -->
        <b-modal v-model="customerModal.visible"
                 centered
                 hide-footer
                 size="xl"
                 title="Customers">
            <!-- Search -->
            <b-input-group class="mt-3">
                <b-form-input placeholder="search in phones ..." v-model="customerModal.search_key" @keyup.enter="getAllCustomers()"></b-form-input>
                <b-input-group-append>
                    <b-button variant="success" @click="getAllCustomers()">
                        search
                    </b-button>
                </b-input-group-append>
            </b-input-group>
            <b-table
                :responsive="true"
                striped
                hover
                :show-empty="true"
                empty-text="No data"
                :fields="customerModal.fields"
                :items="customerModal.data">
                <template v-slot:cell(index)="data">
                    {{data.index + 1}}
                </template>
                <template v-slot:cell(created_at)="data">
                    {{data.item.created_at | moment('from','now')}}
                </template>

                <template v-slot:cell(updated_at)="data">
                                <span
                                    v-if="data.item.updated_at ">{{data.item.updated_at | moment('from','now')}}</span>
                </template>
            </b-table>
            <b-pagination class="d-flex justify-content-center"
                          v-model="customerModal.meta.current_page"
                          :total-rows="customerModal.meta.total"
                          :per-page="customerModal.meta.per_page"/>

        </b-modal>
        <!-- END :: Customer Modal -->

    </b-container>
</template>

<script>
    export default {
        name: "CodeManagement",
        mounted() {
            this.getAllCodes();
        },
        data() {
            return {
                loading: false,
                codes: {
                    fields: [
                        {
                            key: 'id',
                            label: 'id',
                        },
                        {
                            key: 'name',
                            label: 'name',
                        },
                        {
                            key: 'code',
                            label: 'code',
                        },
                        {
                            key: 'capacity',
                            label: 'capacity',
                        },
                        {
                            key: 'enable',
                            label: 'enable',
                        },
                        {
                            key: 'description',
                            label: 'description',
                        },
                        {
                            key: 'created_at',
                            label: 'created_at',
                        },
                        {
                            key: 'updated_at',
                            label: 'updated_at',
                        },
                        {
                            key: 'actions',
                            label: 'actions'
                        }
                    ],
                    data: [],
                    meta: {
                        current_page: 1,
                        last_page: 1,
                        per_page: 15,
                        total: 15
                    },
                    search_key: ''
                },
                codeModal: {
                    visible: false,
                    type: 'add',
                    data: {
                        id: 0,
                        title: '',
                        code: '',
                        description: '',
                        capacity: 0,
                        enable: false,
                    }
                },
                customerModal: {
                    visible: false,
                    data: [],
                    fields: [
                        {
                            key: 'index',
                            label: '#',
                        },
                        {
                            key: 'phone',
                            label: 'phone',
                        },
                        {
                            key: 'created_at',
                            label: 'created_at',
                        },
                        {
                            key: 'updated_at',
                            label: 'updated_at',
                        },
                    ],
                    meta: {
                        current_page: 1,
                        last_page: 1,
                        per_page: 15,
                        total: 15
                    },
                    search_key: '',
                    code_id: 0
                }
            }
        },
        methods: {
            /**
             * get all codes from backend
             */
            getAllCodes() {
                this.loading = true;
                this.$axios.get('/codes', {
                    params: {
                        search_key: this.codes.search_key != '' ? this.codes.search_key : null ,
                        per_page: this.codes.meta.per_page,
                        page: this.codes.meta.current_page,
                    }
                })
                    .then(res => {
                        this.codes.data = res.data.data;
                        this.codes.meta = res.data.meta;

                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;
                    });
            },


            /**
             * get all customers from backend
             */
            getAllCustomers(code = 0) {
                if (code){
                    this.customerModal.search_key = '';
                    this.customerModal.code_id = code.id;
                }

                this.$axios.get('/codes/' + this.customerModal.code_id + '/customers', {
                    params: {
                        search_key: this.customerModal.search_key != '' ? this.customerModal.search_key : null ,
                        per_page: this.customerModal.meta.per_page,
                        page: this.customerModal.meta.current_page,
                    }
                })
                    .then(res => {
                        this.customerModal.data = res.data.data;
                        this.customerModal.meta = res.data.meta;
                        this.customerModal.visible = true;
                    })

            },

            /**
             * submit Add/Edit modal
             */
            submitCode() {
                this.$swal({
                    icon: 'question',
                    showCancelButton: true,
                    title: this.codeModal.type === 'edit' ? 'Edit' : 'Create',
                    text: this.codeModal.type === 'edit' ? 'Edit Code ?' : 'Create Code ?'
                })
                    .then(response => {
                        if (response.value) {
                            let sendData = JSON.parse(JSON.stringify(this.codeModal.data));
                            sendData.enable = sendData.enable ? 1 : 0;

                            if (this.codeModal.type === 'edit') {
                                this.$axios.patch('/codes/' + this.codeModal.data.id, sendData)
                                    .then(response => {
                                        this.$toasted.success('Code Updated Successfully');

                                        this.hideAddEditModal();
                                        this.clearAddEditModal();
                                        this.getAllCodes();
                                    })
                                    .catch(error => {
                                        if (error.response.status == 422)
                                            this.showValidationErrors(error.response.data.errors)
                                    })
                            } else {
                                this.$axios.post('/codes', sendData)
                                    .then(response => {
                                        this.$toasted.success('Code Created Successfully');

                                        this.hideAddEditModal();
                                        this.clearAddEditModal();
                                        this.getAllCodes();
                                    })
                                    .catch(error => {
                                        if (error.response.status == 422)
                                            this.showValidationErrors(error.response.data.errors)
                                    })
                            }
                        }
                    });
            },
            /**
             * delete code
             */
            deleteCode(code) {
                this.$swal({
                    icon: 'question',
                    showCancelButton: true,
                    title: 'Delete',
                    text: `Delete Code ${code.name} ?`
                })
                    .then(result => {
                        if (result.value) {
                            this.loading = true;

                            this.$axios.delete('/codes/' + code.id)
                                .then(response => {
                                    this.$toasted.success('Code deleted successfully', {
                                        duration: 5000
                                    });

                                    this.getAllCodes();
                                })
                                .catch(err => {
                                    this.loading = false;
                                });
                        }
                    })
            },
            /**
             * show add/edit modal
             *
             * @param type {'add'|'edit'}
             * @param code
             */
            showAddEditModal(type = 'add', code = null) {
                this.codeModal.type = type;

                if (type === 'edit' && code) {
                    this.codeModal.data = {
                        id: code.id,
                        name: code.name,
                        code: code.code,
                        capacity: code.capacity,
                        enable: code.enable ? true : false,
                        description: code.description,
                    };
                } else {
                    this.clearAddEditModal();
                }

                this.codeModal.visible = true;
            },

            /**
             * show validation errors
             * @param errors
             */
            showValidationErrors(errors) {
                let errorTxt = '';
                for (let error in errors) {
                    errorTxt += errors[error].toString() + '<br/>'
                }
                this.$swal({
                    icon: 'error',
                    title: 'Error',
                    html: errorTxt
                })
            },


            /**
             * clear Add/edit modal
             */
            clearAddEditModal() {
                this.codeModal.data = {
                    id: 0,
                    name: '',
                    email: '',
                    password: '',
                    password_confirm: ''
                };
            },
            /**
             * hide add/edit modal
             */
            hideAddEditModal() {
                this.codeModal.visible = false;
            },
        },
        watch: {
            'codes.meta.current_page'(val) {
                this.getAllCodes();
            }
        }
    }
</script>
