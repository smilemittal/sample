<template>
    <!-- Main modal -->
    <div id="assignModule" class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-hidden md:inset-0 
                            h-full md:h-full">
        <div class="relative w-full h-auto max-w-4xl md:h-auto">
            <!-- Modal content -->
            <div class="relative rounded-lg shadow bg-body">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-300">
                            {{ trans('lang.labels.description') }}
                        </h3>
                    </div>
                    <button type="button" @click="closeDesc()" class="">
                        <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
                    </button>
                </div>
                    <!-- Form start here -->
                    <div class="p-6 space-y-6 min-h-fit max-h-96 overflow-y-auto theme-modal-body">
                        <div class="overflow-x-auto">
                            <table class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{ trans('lang.labels.start_time') }}
                                        </th>
                                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{ trans('lang.labels.end_time') }}
                                        </th>
                                        <th scope="col" class="text-neutral-300 font-semibold text-base p-4">{{ trans('lang.labels.description') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="timeDetail.length == 0" class="themeNoFound">
                                        <td colspan="7">
                                            <div class="px-4 py-8 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8"
                                                    viewBox="0 0 576 512">
                                                    <path
                                                        d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                                                </svg>
                                                <p class="text-gray-400 text-base noFound">{{ trans('lang.labels.no_description_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-for="detail in timeDetail" :key="detail.id"
                                        class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                                        <td class="p-4">
                                            <span>{{ detail.start_time }}</span>
                                        </td>
                                        <td class="p-4">
                                            <span>{{ detail.end_time }}</span>
                                        </td>
                                        <td class="p-4">
                                            <span>{{ detail.description }} </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-5">
                                <p class="text-gray-400">Description:</p>
                                <p class="text-gray-400" v-html="form.description"></p>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
                        <button @click="closeDesc()"
                            class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                            {{ trans('lang.modal.cancel') }}
                        </button>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    XCircleIcon
} from '@heroicons/vue/24/solid'
export default {
    name: 'AssignMember',
    props: ['id'],
    components: {
        XCircleIcon
    },
    data() {
        return {
            timeDetail: [],
            form: {}

        }
    },
    methods: {
        closeDesc() {
            this.$emit("closeDesc");
        },
        timeDetails() {
            axios
                .get("/api/forms/" + this.id)
                .then((res) => {
                    this.timeDetail = res.data.data.time_detail;
                    this.form = res.data.data.form;

                })
                .catch((error) => {

                    // error.response.status Check status code
                })
                .finally(() => {
                    //Perform action in always
                });
        }

    },
    created() {
        this.timeDetails();

    },
}
</script>
