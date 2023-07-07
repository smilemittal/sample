<template>
    <!-- Main modal -->
    <div id="createCompany" class="modal-open" data-open="modal">
        <div class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto inset-0
                                     h-full h-full">
            <div class="relative w-full h-auto max-w-xl md:h-auto">
                <div class="relative rounded-lg shadow bg-body">
                    <div class="flex items-start justify-between p-4 rounded-t">
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.reopen_description') }}
                        </h3>
                        <button type="button" @click="isClose()" class="">
                            <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                        </button>
                    </div>
                    <!-- Modal header -->
                    <div class="mt-4 px-4">
                        <div class="theme-input">
                            <div class="relative">
                                <ckeditor class="identify_desc" :editor="editor" v-model="data.item.reopen_description"
                                    :config="editorConfig" tag-name="textarea" disabled placeholder="Description">
                                </ckeditor>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end px-4 py-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="isClose()" type="button"
                            class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{ trans('lang.modal.cancel') }}
                        </button>
                    </div>
                    <!-- Modal footer -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {
    ExclamationTriangleIcon, XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: "ReOpenDescription",
    props: ['data'],
    data() {
        return {
            errors: {
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
        ExclamationTriangleIcon, XCircleIcon
    },
    methods: {
        isClose() {
            this.$emit("closeReopenDescription");
        },
    },
};
</script>
