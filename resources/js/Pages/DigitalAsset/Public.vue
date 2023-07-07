<template>
    <div>
        <InertiaHead title="Manage Forms" />
        <GuestLayout>
            <div class="block sm:flex md:flex">
            <div class="h-full drop-shadow-md rounded-3xl mt-5 col-span-2 w-full">
                <div class="relative shadow-md sm:rounded-lg mt-5">
                    <div id="tf-form" style="width: 100%; height: calc(100vh - 80px);"></div>
               </div>
            </div>
        </div>
        </GuestLayout>
    </div>
</template>
<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { createWidget } from '@typeform/embed'
import '@typeform/embed/build/css/widget.css';
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";

export default {
    components: {
      GuestLayout, createWidget
    },
    props : ['id', 'name', 'email'],
    data() {
        return {
            typeformId: '',
        }
    },
    methods: {
        async openTypeform() {
            await this.loadTypeform();
            window.tf.createWidget(this.typeformId, {
                container: this.$el.querySelector('#tf-form'),
                hidden: { name: this.name, email: this.email },
                onReady: ({ formId }) => {
                    console.log(`Form ${formId} is ready`)
                },
                onQuestionChanged: ({ formId, ref }) => {
                    console.log(`Question in form ${formId} changed to ${ref}`)
                },
                onHeightChanged: ({ formId, ref, height }) => {
                    console.log(`Question ${ref} in form ${formId} has height ${height}px now`)
                },
                // onSubmit: ({ formId, responseId }) => {
                //     this.queryData.fid = formId;
                //     let formdata = new FormData();
                //     formdata.append('tid',this.tId);
                //     formdata.append('fid',formId);
                //     formdata.append('xme_form_id','');
                //     formdata.append('learning_path',  this.learningPathId);
                //     axios.post('/api/completed-training',formdata)
                //     .then((res) => {
                //         if(res.data.status == true) {
                //             let notification = {
                //             heading: 'success',
                //             subHeading: res.data.message,
                //             type: "success",
                //             };
                //             this.appStore.setNotification(notification);
                //         }
                //         this.$inertia.visit(this.redirectUrl);
                //     })
                //     .catch((error) => {
                //         this.errors = error.response.data.errors;
                //         // error.response.status Check status code
                //     }).finally(() => {
                //         //Perform action in always
                //     });
                // },
                onClose: ({ formId }) => {
                    console.log(`Modal window with form ${formId} was closed`)
                },
                onEndingButtonClick: ({ formId }) => {
                    console.log(`Ending button clicked in form ${formId}`)
                }
            }).open();
        }
    },    
    created: function () {
        this.typeformId = this.id;
        this.openTypeform();
    },
    computed: {
        ...mapStores(useAppStore),
    },
   
}
</script>
