<template>
    <!-- Main modal -->
    <div id="createCompany" class="modal-open" data-open="modal">
        <div class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto inset-0
                                 h-full h-full">
            <div class="relative w-full h-auto max-w-xl md:h-auto">
                <div class="relative rounded-lg shadow bg-body">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 rounded-t">
                        <h3 v-if="data.actionType == 'backburner'" class="text-xl font-semibold text-gray-300 breakWord">
                            {{ data.heading }}
                        </h3>

                        <h3 v-else class="text-xl font-semibold text-gray-300 breakWord">
                            {{ data.heading }}
                            <h5 class="text-base text-gray-400 mt-2 breakWord">{{ data.subHeading }}</h5>
                        </h3>
                        <button type="button" @click="isClose()" class="">
                            <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="px-3" v-if="data.is_reason == true">
                        <h5 class="text-gray-400 text-lg mb-2">{{ trans('lang.labels.enter_reason_for_this_action') }}</h5>
                        <input class="bg-sidebar px-3 py-2.5 rounded-lg outline-0 text-gray-400 w-full"
                            v-model="form.reason" :readonly="readonly">
                        <span v-if="(data.actionType == 'develop' || data.actionType == 'draft')
                            && data.item.status == 'backburner'" class="flex text-red-600 mt-2">
                            <ExclamationTriangleIcon class="h-6 w-6 text-red-600 mr-3" aria-hidden="true" />
                            {{ trans('lang.labels.note_previous_reason_will_be_discarded') }}
                        </span>

                    </div>
                    <div class="mt-4 px-4" v-if="data.actionType == 'reOpen'">
                        <div class="theme-input">
                            <div class="relative">
                                <ckeditor class="identify_desc" :editor="editor" v-model="form.description"
                                    :config="editorConfig" tag-name="textarea"
                                    placeholder="Description"></ckeditor>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="isClose()" type="button"
                            class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{ trans('lang.modal.cancel') }}
                        </button>
                        <button :disabled="formProcess" @click="submit()"
                            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ data.buttonHeading }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {
    ExclamationTriangleIcon,XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: "CreateCompany",
    props: ['data'],
    data() {
        return {
            form: {
                description: this.data.item.reopen_description
            },
            errors:{
                description: ''
            },
            editor: ClassicEditor,
            editorData: '<p>Content of the editor.</p>',
            editorConfig: {
                toolbar: ['heading', 'bold', 'italic', '|', 'NumberedList', 'BulletedList']
            },
            formProcess: false,
            readonly: false,
        };
    },
    components: {
        ExclamationTriangleIcon,
        XCircleIcon
    },
    methods: {
        isClose() {
            this.$emit("closeDevelop");
        },
        submit() {
            this.formProcess = true;
            this.$emit('submit', this.form);
        },
        processingFunc() {
            this.formProcess = false;
        }
    },
    created() {
        if ((this.data.actionType == 'develop' || this.data.actionType == 'draft')
            && this.data.item.status == 'backburner') {
            this.form.reason = this.data.item.reason;
            this.data.is_reason = true;
            this.readonly = true;
        }
    }
};
</script>
