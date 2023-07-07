<template>
<TransitionRoot as="template" :show="show">
    <Dialog as="div" class="relative z-50" @close="closeMessage" :open="show">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>

        <div class="fixed inset-0 z-10 overflow-y-auto" v-if="show">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white mx-2 md:mx-0 px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                        <div>
                            <div class="text-center">
                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                    <span style="font-size: 22px;" class="font-semibold text-primary-900">{{trans('lang.labels.upgrade')}}&nbsp;</span>
                                    <span style="font-size: 22px;" class="font-semibold text-warning-1300">{{trans('lang.labels.to_unlock')}}</span>
                                </DialogTitle> 
                                <div class="mt-2">
                                    <p class="text-base text-grayCust-500">{{upgrade_warning.subHeading}}</p>
                                </div>
                                </div>
                            <div class="mt-4 flex flex-wrap items-center justify-center" v-if="upgrade_warning.planMessage">
                                <button class="flex items-center justify-center border border-grayCust-570 rounded-full py-1 px-4">
                                    <img :src="cdn('images/Command/InformationCommand.svg')" alt="" srcset="">
                                    <span class="ml-1 text-sm font-medium text-grayCust-1600">{{upgrade_warning.planMessage}}</span>
                                    <!-- This feature is available in Pro plan and above. -->
                                </button>
                            </div>
                            <div class="mt-6 flex justify-center relative bg-shadow">
                                <div class="rounded-b-lg" style="box-shadow: 0px 33.7781px 48.7905px rgba(0, 0, 0, 0.08);">
                                    <div class="relative">
                                        <img :src="cdn('images/Command/TopBgUpgrade.svg')" alt="" srcset="" />
                                        <div class="absolute top-2.5 left-3">
                                            <img :src="cdn('images/Command/DotsTopUpgrade.svg')" alt="" srcset="" />
                                        </div>
                                    </div>
                                    <div class="bg-white border border-grayCust-900 border-t-0 rounded-b-lg flex items-center">
                                        <div class="w-1/2 relative">
                                            <img :src="cdn('images/Command/LeftSideDesign.svg')" class="mx-auto" alt="" srcset="" />

                                            <div class="absolute bg-white w-24 h-24 rounded-full flex justify-center items-center  border border-grayCust-570 left-4 top-24">
                                            <img :src="cdn('images/Command/UpgradeUnlock.gif')" class="mx-auto w-20 h-20" alt="" srcset="" />
                                            </div>
                                        </div>
                                        <div class="w-1/2 relative">
                                            <img :src="cdn('images/Command/RightSideDesign.svg')" class="mx-auto" alt="" srcset="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center justify-center" v-if="upgrade_warning.planMessage">
                                <button class="flex items-center justify-center border border-yellow-700 rounded-full py-1 px-4 bg-yellow-50">
                                    <span class="ml-1 text-sm font-medium text-yellow-700">{{trans('lang.message.you_need_to_be_on_a_paid_plan_to_buy_an_addon')}}</span>
                                </button>
                            </div>
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                            <button type="button" class="inline-flex w-full justify-center rounded-md border border-transparent bg-primary-900 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-900 focus:ring-offset-2 sm:col-start-2 sm:text-sm" @click="navigateToPlansPage">{{trans('lang.labels.upgrade')}}</button>
                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-900 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm" @click="closeMessage" ref="cancelButtonRef">{{trans('lang.labels.cancel')}}</button>
                        </div>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </div>
    </Dialog>
</TransitionRoot>
</template>
<script>
import GeneralMixin from "@/Mixins/GeneralMixin";
import {features} from '@/features.js';
import { mapStores, mapState } from 'pinia'
import { useAppStore } from '@/store/index'

export default {
    mixins: [GeneralMixin],
    computed: {
        show() {
            let obj = this.appStore.upgrade_warning;
            return obj && Object.keys(obj).length !== 0 && obj.constructor === Object;
        },
        ...mapStores(useAppStore),
        ...mapState(useAppStore, {
            "upgrade_warning": "upgrade_warning"
        }),
        ...mapState(useAppStore, [
            "userBaseActivePlan"
        ]),
        showAddOnPlanNote() {
            console.log(this.show, this.isAddonOnly)
            if (this.show && this.isAddonOnly) {
                return true
            }
            return false
        },
        isAddonOnly() {
            let feature = features.find(i => i.key == this.appStore.upgrade_warning.feature)
            if (feature && feature.addon_only) {
                return true
            }
            return false
        }
    },
    methods: {
        closeMessage() {
            this.appStore.togglePopupInsideModal(false);
            this.appStore.setUpgradeWarning({});
        },
        navigateToPlansPage() {
            if (this.isAddonOnly) {
                if (this.userBaseActivePlan.name == 'Free') {
                    this.$inertia.visit('/subscription/plans')
                } else {
                    this.$inertia.visit('/subscription/plan/customize?feature=' + this.appStore.upgrade_warning.feature)
                }
            } else {
                this.$inertia.visit('/subscription/plans?feature=' + this.appStore.upgrade_warning.feature)
            }
            this.closeMessage();
        }
    }
}
</script>