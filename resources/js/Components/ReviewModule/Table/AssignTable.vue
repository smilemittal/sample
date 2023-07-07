<template>
    <div>
        
    </div>
</template>
<script>
import Update from '@/Components/ReviewModule/Update.vue'
import Review from '@/Components/ReviewModule/Review.vue'

import Pagination from "@/Components/Pagination.vue";
import MoveLibrary from '@/Components/ReviewModule/MoveLibrary.vue'
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";


import {
    BarsArrowUpIcon, ClipboardDocumentCheckIcon, EllipsisVerticalIcon,
    ArrowPathIcon, ArchiveBoxArrowDownIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'Assign Module',
    props: ['userRole'],
    components: {
         
        Update,
        Review,
        Pagination,
        MoveLibrary,
        BarsArrowUpIcon,
        ClipboardDocumentCheckIcon,
        EllipsisVerticalIcon, ArrowPathIcon,
        ArchiveBoxArrowDownIcon
    },
    data() {
        return {
            forms: [],
            isUpdateReview: false,
            isReviewDate: false,
            isMove: false,
            loggedUserRole: 'superadmin',
            type: 'Type',
            status: 'Module Status',
            modalData: {
                formId: '',
                reviewDate: '',
            },
            searchTimeout: null,
            search: '',

            queryData: {
                search: '',
                isDeleted: 0,
                page: 1,
                type: null,
                status: null,
                sortField: 'forms.id',
                orderDir: 'ASC'

            },
            formId: '',
            pagination: {
                currentPage: 1,
                lastPage: 1,
                total: 0,
            },
        }
    },
    watch: {
        search: function (value) {
            let that = this;
            clearTimeout(that.searchTimeout);
            that.searchTimeout = setTimeout(function () {
                that.queryData.search = that.search;
                that.assignTable();
            }, 700);
        },
        isUpdateReview: function () {
            document.body.style.overflow = this.isUpdateReview ? 'hidden' : ''
        },
        isReviewDate: function () {
            document.body.style.overflow = this.isReviewDate ? 'hidden' : ''
        },
        isMove: function () {
            document.body.style.overflow = this.isMove ? 'hidden' : ''
        }
    },
    methods: {
        setPagination(response) {
            this.pagination.total = response.data.meta.total;
            this.pagination.lastPage = response.data.meta.last_page;
            this.pagination.currentPage = response.data.meta.current_page;
        },
        assignTable(page) {
            this.queryData.page = page;
            //for table data is loading after fetch ==>
            axios.get('/api/new-assigned-modules', { params: this.queryData })
                .then((response) => {
                    if (response.status == 200) {
                        this.forms = response.data.data;
                        this.setPagination(response)
                    }
                })
                .catch((error) => {

                })
                .finally(() => {
                });
        },
        updateModule(id) {
            this.modalData.formId = id;
            this.isUpdateReview = true;
        },
        nextReviewDate(id, reviewDate) {
            this.modalData.formId = id;
            this.isReviewDate = true;
            this.modalData.reviewDate = reviewDate;
        },
        isCloseReview() {
            this.isReviewDate = false;
        },

        isCloseUpdate() {
            this.isUpdateReview = false;
        },
        changeType(event) {
            let that = this;
            if (event.target.value != 'Type') {
                that.queryData.type = event.target.value;
            } else {
                that.queryData.type = null;
            }
            that.assignTable();
        },
        changeStatus(event) {
            let that = this;
            if (event.target.value != 'Module Status') {
                that.queryData.status = event.target.value;
            } else {
                that.queryData.status = null;
            }
            that.assignTable();
        },
        isCloseMove() {
            this.isMove = false;
        },
        moveToLibraray(id) {
            this.isMove = true;
            this.formId = id;

        },
        library() {
            axios.put('/api/move-to-library/' + this.formId)
                .then((res) => {
                    if (res.status == 200) {
                        this.isCloseMove();
                        let notification = {
                            heading: 'success',
                            subHeading: res.data.message,
                            type: "success",
                        };
                        this.appStore.setNotification(notification);
                        this.assignTable();

                    }
                })
                .catch((error) => {
                    // error.response.status Check status code
                    let that = this;
                    that.errors = error.response.data.errors;
                }).finally(() => {
                    //Perform action in always
                });

        },
        refreshTable() {
            this.$emit('refreshTable');
        }
    },
    created: function () {
        this.assignTable(1);
    },
    unmounted() {
        clearTimeout(this.searchTimeout);
    },
    computed: {
        ...mapStores(useAppStore),
    },
}
</script>
