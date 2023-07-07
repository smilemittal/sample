<template>
    <div class="absolute upgrade-plan bg-white rounded-lg shadow-lg p-3 w-80 z-50 " style="z-index: 999999;" :style="{ 'left': left, 'top': formatedTop }">
        <h4 class="text-xs font-semibold text-grayCust-700 text-center">{{trans('lang.labels.upgrade_unlock')}}!</h4>
        <p class="mt-1 text-xs text-grayCust-470 text-center px-4">{{trans('lang.message.you_are_not_allowed_perform')}}</p>
        <div class="mt-3 flex flex-wrap items-center justify-center">
            <button class="flex items-center justify-center border border-grayCust-570 rounded-full py-1 px-3" type="button" v-if="this.featureAvailableFrom[feature]">
                <img :src="cdn('images/Command/InformationCommand.svg')" alt="" srcset="">
                <span style="font-size: 10px;" class="ml-1 font-medium text-grayCust-1600">{{this.featureAvailableFrom[feature] ? this.trans('lang.message.this_feature_is_available_in_plan_and_above', {planName: this.featureAvailableFrom[feature]}) : null}}</span>
            </button>
        </div>

        <div class="mt-6 flex justify-center relative bg-shadow">
            <div class="rounded-b-lg" style="box-shadow: 0px 33.7781px 48.7905px rgba(0, 0, 0, 0.08); width: 160px">
                <div class="relative">
                    <img :src="cdn('images/Command/TopBgUpgrade.svg')" alt="" srcset="" />
                    <div class="absolute top-2.5 left-3">
                        <img :src="cdn('images/Command/DotsTopUpgrade.svg')" alt="" srcset="" />
                    </div>
                </div>
                <div class="bg-white border border-grayCust-900 border-t-0 rounded-b-lg flex items-center">
                    <div class="w-1/2 relative">
                        <img :src="cdn('images/Command/LeftSideDesign.svg')" class="mx-auto" alt="" srcset="" />

                        <div class="absolute bg-white w-11 h-11 rounded-full flex justify-center items-center  border border-grayCust-570 left-2 top-12">
                            <img :src="cdn('images/Command/UpgradeUnlock.gif')" class="mx-auto w-7 h-7" alt="" srcset="" />
                        </div>
                    </div>
                    <div class="w-1/2 relative">
                        <img :src="cdn('images/Command/RightSideDesign.svg')" class="mx-auto" alt="" srcset="" />
                    </div>
                </div>
            </div>
        </div>

        <a :href="route('pricing', {'feature': feature})" class="mt-4 inline-flex w-full justify-center items-center rounded-full border border-transparent bg-primary-900 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-900 focus:ring-offset-2 sm:col-start-2 sm:text-xs">
            <img :src="cdn('images/SiteEdit/graph-icon.svg')" alt="" srcset="" />    
            <span class="ml-2">{{trans('lang.labels.upgrade_plan')}}</span>
        </a>
    </div>
</template>
<script>
import GeneralMixin from "@/Mixins/GeneralMixin";
import { mapState,mapStores } from 'pinia';
import { useAppStore } from '@/store/index';

export default {
    name: 'upgrade-warning-pop-over',
    mixins: [GeneralMixin],
    props: {
        feature: {
            type: String,
            required: true,
        },
        left: {
            type: String,
            default: '-56%'
        },
        top: {
            type: String,
        }
    },
    computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore, ["featureAvailableFrom"]),
        formatedTop() {
            if (this.top) {
                return this.top
            }
            let value = -315
            let featureMessageHeight = 12;
            if (!this.featureAvailableFrom[this.feature]) {
                value =  value + featureMessageHeight + 'px'
            } else {
                value = value + 'px'
            }
            return value
        }
    },
}
</script>
<style scoped>
.upgrade-plan{
    display: none;
}
.hover-upgrade:hover .upgrade-plan{
    display: block !important;
}
</style>