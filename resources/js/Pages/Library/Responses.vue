<template>
    <div>

        <InertiaHead title="Responses" />
        <AuthenticatedLayout>
            <div class="themeTabsResponse">
                <div class="themeResponseTabs mb-4 border-b border-gray-200 dark:border-gray-700 bg-sidebar rounded-xl">
                    <div class="bg-cardtop p-3 rounded-t-xl text-center">
                        <h5 class="text-gray-500">RESPONSES</h5>
                    </div>
                    <ul class="p-3 text-sm font-medium text-center themeResponses" id="myTab"
                        data-tabs-toggle="#myTabContent" role="tablist">
                        <li class="" role="presentation" v-for="response in responses" :key="response">
                            <button class="inline-block p-4 mb-4 rounded-lg w-100 bg-cardtop text-start" type="button"
                                @click="selectResponse(response)">
                                <h5 :class="response.id == currentId ? ' text-amber-500' : 'text-gray-300'">{{
                                    response.email }} - {{ response.name }}</h5>
                                <h6 class="text-gray-500">{{ response.submitted_at }}</h6>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="bg-sidebar rounded-xl themeTabsContent">
                    <div class="bg-cardtop rounded-t-xl p-3 text-center">
                        <h5 class="text-gray-500">CONTENT</h5>
                    </div>
                    <div id="myTabContent">
                        <div class="p-4 rounded-lg themeResponseAnswers">

                            <response :questions="questions" :answers="answers" :hidden="hidden"></response>

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import Response from "@/Components/Module/Response.vue";

export default {
    props: {
        formId: {
            require: true,
            type: String
        },
        response: {
            require: true,
            type: String
        }
    },
    components: {
        AuthenticatedLayout,
        Response
    },
    data() {
        return {
            responses: [],
            questions: [],
            answers: [],
            hidden: null,
            currentId: null,
        }
    },
    methods: {
        fetchResponses() {
            //for table data is loading after fetch ==>
            axios.post('/api/form/responses', { 'form_id': this.formId, 'response': this.response })
                .then((response) => {
                    this.responses = response.data.data;
                    let res = this.responses.filter((item) => {
                        return item.id == this.response;
                    });
                    this.selectResponse(res[0]);
                })
                .catch((error) => {

                })
                .finally(() => {

                });
        },
        selectResponse(response) {
            this.currentId = response.id;
            this.questions = response.questions;
            this.answers = response.answers;
            this.hidden = response.hidden;
        }
    },
    mounted() {
        this.fetchResponses();
    }
}
</script>
