<template>
    <div class="form_xme_desc">
        <div class="form_xme_title">
            <label class="text-base font-bold  text-gray-400">Enter update details here:</label>
            <div class="text-base text-gray-400" v-if="updateActionType == 'reviewUpdateModule'">When you press ‘Send to Identify’ this module will be in your IDOPS list.</div>
        </div>
        <div class="container mt-4">
            <div>
                <ckeditor class="identify_desc" :editor="editor" v-model="form.description" :config="editorConfig"
                    tag-name="textarea" placeholder="Describe point by point what you would like to update."></ckeditor>
                <span v-if="errors.description" class="mt-2 text-sm text-red-600 theme-error-message">{{
                    errors.description[0]
                }}</span>
            </div>
            <div v-if="updateActionType == 'reviewCompanyModule'">
                <div class="mt-6">
                    <span class="text-gray-500 text-sm">Drop or click to upload files.</span>
                </div>
                <div
                    class="themeDropZone mt-2 flex flex-col items-center justify-center 
                                        w-full h-64 border-2 border-gray-500 border-dashed rounded-lg cursor-pointer bg-sidebar overflow-y-auto">
                    <DropZone @uploaded="uploaded" :maxFiles="Number(10)" :uploadOnDrop="false" :multipleUpload="true"
                        :parallelUpload="3" @addedFile="onFileAdd" @removedFile="onFileRemove" :chunking="true" />
                </div>
            </div>
            <div class="text-end">
                <button type="button" @click="updateForm()" class="rounded-lg bg-amber-500 px-4 py-2 mt-3"
                    v-if="updateActionType == 'reviewCompanyModule'" style="color:white" :disabled="processing"
                    value="submit"><i class="fas fa-file-import"></i>Submit</button>
                <button type="button" @click="updateForm()" class="rounded-lg bg-amber-500 px-4 py-2 mt-3"
                    v-else style="color:white" :disabled="processing"
                    value="submit"><i class="fas fa-file-import"></i>Send to Identify</button>
            </div>
        </div>
    </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { mapStores, mapState } from 'pinia'
import { useAppStore } from "@/store";
import { DropZone } from 'dropzone-vue';
import { isAdmin, isCompanyAdmin } from "@/helpers";

export default {
    components: {
        DropZone,
    },
    props: ['id', 'updateActionType', 'company_id', 'form_id'],
    data() {
        return {
            forms: [],
            editor: ClassicEditor,
            editorData: '<p>Content of the editor.</p>',
            editorConfig: {
                toolbar: ['heading', 'bold', 'italic', '|', 'NumberedList', 'BulletedList']
            },
            form: {

            },
            errors: {
                description: '',
            },
            processing: false,
            images: []
        }
    },
    methods: {
        onFileAdd(file) {
            this.images.push(file);

        },
        onFileRemove(file) {
            this.images.splice(this.images.indexOf(file), 1);
        },
        updateForm() {
            let url = '';
            this.processing = true;
            if (this.checkAdmin) {
                url = 'app.review.company';
            } else if (this.updateActionType == 'reviewCompanyModule' && this.checkCompanyAdmin) {
                url = 'app.in-production-modules';
            } else if (this.updateActionType == 'reviewUpdateModule' && this.checkCompanyAdmin) {
                url = 'admin.review-forms';
            }
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let formData = new FormData();
            formData.append('description', this.form.description);
            formData.append('company_id', this.company_id);
            for (let i = 0; i < this.images.length; i++) {
                let file = this.images[i]['file'];
                formData.append('files[' + i + ']', file);
            }
            formData.append('updateActionType', this.updateActionType);
            formData.append('company_form_id', this.company_form_id);
            axios.post('/api/update-module/' + this.form_id, formData, config)
                .then((res) => {
                    if (res.status == 200) {
                        let notification = {
                            heading: 'success',
                            subHeading: 'Module Sent To admin For Update',
                            type: "success",
                        };
                        this.appStore.setNotification(notification);
                        this.$inertia.visit(route(url));
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    console.log(this.errors);
                    // error.response.status Check status code
                }).finally(() => {
                    this.processing = false;
                    //Perform action in always
                });
        },
        uploaded() {
            alert("gggg");
        }
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, [
            "unreadNotificationCount",
            "recentNotifications",
            "user", 'userRole'
        ]),
        checkAdmin() {
            let role = this.appStore.userRole;
            let result = isAdmin(role);
            return result;
        },
        checkCompanyAdmin() {
            let role = this.appStore.userRole;
            return isCompanyAdmin(role);
        },

    },
}
</script>

