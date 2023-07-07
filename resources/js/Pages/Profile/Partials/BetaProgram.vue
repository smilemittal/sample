<template>
    <div>
            <div class="px-4 py-5 bg-sidebar w-full md:w-full lg:w-3/4 sm:p-6 shadow sm:rounded-md">

                <!-- title -->
                <div class="md:col-span-1 flex justify-between">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-200">
                            {{ trans('lang.labels.beta_program') }}
                        </h3>

                        <p class="mt-1 text-sm text-gray-400">
                            {{ trans('lang.labels.beta_program_sub_heading') }}
                        </p>
                    </div>
                </div>
                <!-- title -->

                <!-- Form -->
                <div class="mt-5 grid grid-cols-1">
                    <SwitchGroup as="div" class="flex items-start justify-between">
                        <span class="flex flex-grow flex-col">
                            <SwitchLabel as="span" class="text-sm font-medium text-gray-400" passive>
                                {{ trans('lang.labels.beta_program_join') }}
                            </SwitchLabel>
                        </span>
                        <div @click="updateNotification()">
                            <Switch v-model="is_enabled" :class="is_enabled ? 'bg-amber-700' : 'bg-neutral-500'"
                                class="relative inline-flex h-5 w-11 p-1 items-center flex-shrink-0 cursor-pointer rounded-full 
                                border-2 border-transparent transition-colors duration-200 
                                ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-900 focus:ring-offset-2">
                                <span aria-hidden="true" :class="is_enabled ? 'translate-x-5' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </Switch>
                        </div>
                    </SwitchGroup>
                </div>
                <!-- Form -->

            </div>
    </div>
</template>

<script>
import { mapStores, mapState, mapActions } from 'pinia'
import { useAppStore } from "@/store";
import {
    Switch,
    SwitchDescription,
    SwitchGroup,
    SwitchLabel,
} from "@headlessui/vue";

export default {
    components: {
        Switch,
        SwitchDescription,
        SwitchGroup,
        SwitchLabel,
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, ["user"]),
    },
    data() {
        return {
            is_enabled: false
        }
    },
    mounted() {
        let that = this;
        setTimeout(function () {
            console.log('user', that.user.beta_access);
            if (that.user.beta_access) {
                that.is_enabled = true;
            } else {
                that.is_enabled = false;
            }
        }, 1000);
    },
    methods: {
        updateNotification() {
            let that = this;
            axios
                .post("/api/beta-program/update", {
                    'beta_access': this.is_enabled,
                })
                .then((res) => {
                    let message = {
                        heading: that.trans("lang.labels.success") + "!",
                        subHeading: res.data.message,
                        type: "success",
                    };
                    that.appStore.setNotification(message);
                })
                .catch(function (error) {
                })
                .finally(() => {
                    that.processing = false;
                });

        },
    },
}
</script>
