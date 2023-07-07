<template>
    <div>
        <InertiaHead title="Manage Forms" />
        <AuthenticatedLayout>
            <div class="mt-2">
                <h4 class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{ heading_text }}</h4>
                <h5 v-if="!updation"
                    class="text-neutral-400 text-sm sm:text-sm md:text-base">Please follow to the prompts and complete the modules.
                </h5>
                <h5 v-if="updation"
                    class="text-neutral-400 text-sm sm:text-sm md:text-base">View Modules on the left and write notes on the right.
                </h5>
            </div>
            <div class="block sm:flex md:flex">
                <div class="h-full drop-shadow-md rounded-3xl mt-5 col-span-2 w-full">
                    <div class="relative shadow-md sm:rounded-lg mt-5">
                        <div id="tf-form" style="width: 100%; height: calc(100vh - 80px);"></div>
                    </div>
                </div>
                <div v-if="updation" class="themeAssetUpdate">
                    <div v-if="reviewComments.length > 0">
                        <div class="bg-sidebar rounded-t-xl text-gray-300 text-base p-3">Comments</div>
                        <div class="bg-cardtop px-6 py-8 rounded-b-xl grid grid-cols-1 gap-6 mb-4 max-h-96 overflow-y-auto">
                            <div class="commentAdmin" v-for="reviewComment in reviewComments" :key="reviewComment">
                                <div class="commentUser bg-sidebar p-3 rounded-lg w-96 min-h-28">
                                    <div class="text-end">
                                        <span class="bg-amber-500 text-xs text-white px-2 p-0.5 rounded-sm">{{
                                            reviewComment.userName }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-400" v-html="reviewComment.comments"></span>
                                    </div>
                                    <div class="commentImages mt-4">
                                        <div class="commentImgs">
                                            <img alt="image" :src="mediaPath + '/' + file" class="object-fit"
                                                v-for="file in reviewComment.files" :key="file">
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-gray-300 text-xs">{{ reviewComment.createdAt }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <update :id="id" :form_id="form_id" :company_id="company_id" :updateActionType="this.updateActionType">
                    </update>
                </div>
            </div>

        </AuthenticatedLayout>
    </div>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import Update from '@/Components/DigitalAsset/Update.vue';
import { createWidget } from '@typeform/embed'
import '@typeform/embed/build/css/widget.css';
import { mapStores, mapState } from 'pinia'
import { useAppStore } from "@/store";

export default {
    components: {
        AuthenticatedLayout,
        Update, createWidget
    },
    props: ['id', 'updation', 'company_id', 'form_id', 'tId', 'learningPathId', 'updateActionType', 'redirectUrl','heading_text'],
    data() {
        return {
            typeformId: '',
            queryData: {
                form_id: this.form_id,
                company_id: this.company_id
            },
            reviewComments: [],
        }
    },
    methods: {
        async openTypeform(id) {
            await this.loadTypeform();
            window.tf.createWidget(this.typeformId, {
                container: this.$el.querySelector('#tf-form'),
                hidden: { name: this.appStore.user.name, email: this.appStore.user.email },
                onReady: ({ formId }) => {
                    console.log(`Form ${formId} is ready`)
                },
                onQuestionChanged: ({ formId, ref }) => {
                    console.log(`Question in form ${formId} changed to ${ref}`)
                },
                onHeightChanged: ({ formId, ref, height }) => {
                    console.log(`Question ${ref} in form ${formId} has height ${height}px now`)
                },
                onSubmit: ({ formId, responseId }) => {
                    this.queryData.fid = formId;
                    let formdata = new FormData();
                    formdata.append('tid', this.tId);
                    formdata.append('fid', formId);
                    formdata.append('xme_form_id', '');
                    formdata.append('learning_path', this.learningPathId);
                    formdata.append('form_id', this.form_id);
                    axios.post('/api/completed-training', formdata)
                        .then((res) => {
                            if (res.data.status == true) {
                                let notification = {
                                    heading: 'success',
                                    subHeading: res.data.message,
                                    type: "success",
                                };
                                this.appStore.setNotification(notification);
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            this.errors = error.response.data.errors;
                        }).finally(() => {
                            if (this.redirectUrl) {
                                this.$inertia.visit(this.redirectUrl);
                            }
                        });
                },
                onClose: ({ formId }) => {
                    console.log(`Modal window with form ${formId} was closed`)
                },
                onEndingButtonClick: ({ formId }) => {
                    console.log(`Ending button clicked in form ${formId}`)
                }
            }).open();
        },
        reviewFormComments() {
            //for table data is loading after fetch ==>
            axios.get("/api/review-form-comments/" + this.form_id + '?company_id=' + this.company_id)
                .then((response) => {
                    this.reviewComments = response.data.data;
                })
        }
    },
    created: function () {
        this.typeformId = this.id;
        this.openTypeform();
        if (this.updation && this.updateActionType == 'reviewCompanyModule') {
            this.reviewFormComments();
        }
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, [
            'user'
        ])
    },

}
</script>
