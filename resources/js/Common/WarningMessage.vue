<template>
    <TransitionRoot as="template" :show="show">
        <Dialog
            as="div"
            static
            class="fixed z-10 inset-0 overflow-y-auto bg-transparent"
            @close="closeMessage"
            :open="show"
        >
            <div
                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
                v-if="show"
            >
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <DialogOverlay
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    />
                </TransitionChild>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span
                    class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true"
                    >&#8203;</span
                >
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to="opacity-100 translate-y-0 sm:scale-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100 translate-y-0 sm:scale-100"
                    leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                    >
                        <div>
                            <div
                                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100"
                            >
                                <ExclamationCircleIcon
                                    class="h-6 w-6 text-red-600"
                                    aria-hidden="true"
                                />
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <DialogTitle
                                    as="h3"
                                    class="text-lg leading-6 font-medium text-gray-900"
                                >
                                    {{ warning.heading }}
                                </DialogTitle>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        <span v-html="warning.subHeading"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense"
                        >
                            <button
                                v-if="!user.is_team_owner"
                                type="button"
                                @click="() => { closeMessage(); openMailClient()}"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm"
                            >
                                {{ trans('lang.labels.contact_team_owner') }}
                            </button>
                            <button
                                v-else
                                type="button"
                                @click="() => { closeMessage(); this.$inertia.visit('/subscription/plans');}"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm"
                            >
                                {{ trans('lang.labels.upgrade') }}
                            </button>
                            <button
                                type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 sm:mt-0 sm:col-start-1 sm:text-sm"
                                @click="closeMessage"
                                ref="cancelButtonRef"
                            >
                                {{ trans('lang.labels.cancel') }}
                            </button>
                        </div>
                    </div>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import { ExclamationCircleIcon } from "@heroicons/vue/24/outline";
import { mapState,mapStores } from 'pinia'
import { useAppStore } from '@/store/index'

export default {
    components: {
        ExclamationCircleIcon,
    },
    computed: {
        show() {
            let obj = this.appStore.warning;
            return obj && Object.keys(obj).length !== 0 && obj.constructor === Object;
        },
        ...mapStores(useAppStore),
        ...mapState(useAppStore, {
            "warning": "warning"
        }),
        ...mapState(useAppStore, ["teamOwner", "user"]),
    },
    methods: {
        openMailClient() {
            location.href = `mailto:${this.teamOwner.email}`;
        },
        closeMessage() {
            this.appStore.togglePopupInsideModal(false);
            this.appStore.setWarning({});
        }
    },
}
</script>