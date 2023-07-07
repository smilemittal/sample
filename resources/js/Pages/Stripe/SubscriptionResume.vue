<template>
<InertiaHead>
    <title>{{ trans('lang.labels.resume_subscription') }} - xme</title>
</InertiaHead>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-900 text-3xl leading-9 font-bold">{{ trans('lang.labels.resume_my_subscription') }}</h2>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto bg-white py-8 sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <h4 class="text-md leading-5 font-base text-gray-600 mt-3">
                        <strong>{{ trans('lang.labels.hurray') }}!!</strong>
                        {{ trans('lang.labels.gonna_come_back') }}
                    </h4>
                    <form
                        id="resume-subscription-form"
                        :action="app.domain + '/subscriptions/resume'"
                        @submit.prevent="onSubmit">
                        <div class="flex flex-row justify-start my-3">
                            <jet-primary-button :class="{ 'opacity-25': loading }" type="submit"
                                                :disabled="loading">
                                {{ trans('lang.labels.resume_my_subscription') }}
                            </jet-primary-button>
                        </div>
                        <p v-show="error.length > 0" class="ml-1 text-xs text-red-600">
                            {{ error }}
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>


import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import JetPrimaryButton from '@/Components/PrimaryButton.vue'

export default {
    props: {
        endsAt: {
            type: String,
            default: null
        }
    },
    components: {
        AuthenticatedLayout,
        JetPrimaryButton
    },
    inheritAttrs: false,
    data: () => ({
        app: {},
        loading: false,
        error: ''
    }),
    mounted() {
        this.app = window.APP;
        if (this.endsAt === null) {
            this.$inertia.visit(this.app.domain + '/subscription/plans');
        }
    },
    methods: {
        onSubmit() {
            this.error = '';
            this.loading = true;
            axios.put(document.getElementById('resume-subscription-form').action).then((response) => {
                this.loading = false;
                this.$inertia.visit(this.app.domain + '/subscription/plans');
            }).catch((error) => {
                this.loading = false;
                if (error.response.status === 404 && error.response.data && error.response.data.message) {
                    this.error = error.response.data.message;
                } else if (error.response.status === 400 && error.response.data) {
                    if (error.response.data.message) {
                        this.error = error.response.data.message;
                    }
                    if (error.response.data.resume) {
                        this.error = error.response.data.resume;
                    }
                } else {
                    console.log(error.response);
                }
            });
        }
    }
}
</script>
