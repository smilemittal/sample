<template>
<div class=" mt-6 flex-wrap lg:flex-nowrap" :class="{'flex justify-between':!hide_bool_column}">
    <div :class="{'w-6/12':!hide_bool_column}" class=" bg-grayCust-50 rounded-md px-4 pb-4 statics-box">
    <div v-for="(feature,index) in showTotalStatistics?flatNumeric.filter((feature) => allow[feature.key] && !feature.hide && can.hasOwnProperty(feature.key)):flatNumeric.filter((feature) => allow[feature.key] && !feature.hide && can.hasOwnProperty(feature.key)).slice(0, showLessStatistics)" :key="index">
        <usage-element
            :name="
                trans(
                    'lang.labels.' +
                        feature.key
                )
            "
            :decimal-point="
                feature.decimal_point ??
                0
            "
            :used="
                used[feature.key] ??
                0
            "
            :used-percentage="
                usedPercentage(
                    feature.key,used,allow
                )
            "
            :total="
                allow[feature.key]
            "
            :progressColorClass="
                usedPercentage(
                    feature.key,used,allow
                ) <= 80
                    ? 'bg-insta-primary'
                    : 'bg-red-900'
            "
            :addons="
                hasAddons(feature.key)
                    .enabled
            "
            :helpText="'+ '+
                hasAddons(feature.key)
                    .count+' '+feature.name +' '+ trans('lang.labels.add_on')
            "
        />
    </div>
    </div>
    <div v-if="!hide_bool_column" class="w-6/12 bg-grayCust-50 rounded-md ml-4 px-4 pb-4 statics-box">
    <div v-for="feature in showTotalStatistics?flatFeatures.filter((feature) => !feature.hide):flatFeatures.filter((feature) => !feature.hide).slice(0, showLessStatistics)" :key="feature.key">
        <boolean-usage-element
            :name="feature.name"
            :allow="allow[feature.key]"
            :addons=" hasAddons(feature.key).enabled"
            :helpText=" trans('lang.labels.enable') +' '+ feature.name +' '+ trans('lang.labels.add_on')"
        />
    </div>
    </div>
</div>
<div class="w-full flex justify-center mt-3 focus-visible:outline-none" v-if="!showTotalStatistics">
    <button @click="ViewMoreStatistics" class="text-sm text-amber-500 flex items-center focus-visible:outline-none"> {{ trans("lang.labels.view_more") }}
    <statistics-arrow-icon class="ml-2 text-amber-500" />
    </button>
</div>

<div class="w-full flex justify-center mt-3 focus-visible:outline-none" v-else>
    <button @click="ViewLessStatistics" class="text-sm text-amber-500 flex items-center focus-visible:outline-none"> {{ trans("lang.labels.view_less") }}
    <statistics-arrow-icon class="ml-2 transform rotate-180" />
    </button>
</div>
</template>
<script>
import StatisticsArrowIcon from "@/ImageComponents/Profile/StatisticsArrowIcon.vue";
import BooleanUsageElement from "@/Components/Statistics/BooleanUsageElement.vue";
import UsageElement from "@/Components/Statistics/UsageElement.vue";
import { featuresBoolean, featuresNumeric } from "@/features";
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
import GeneralMixin from "@/Mixins/GeneralMixin";
export default {
  name: "Statistics",
  mixins: [GeneralMixin],
  components: {
    StatisticsArrowIcon,
    BooleanUsageElement,
    UsageElement
  },
  props: {
    allow:{},
    used:{},
    can:{},
    addons_description:{},
    hide_bool_column:{
        default:false
    }
  },
  computed: {
        ...mapStores(useAppStore),
    },
  methods: {
      ViewMoreStatistics(){
        this.showTotalStatistics= true
      },
      ViewLessStatistics(){
        this.showTotalStatistics= false
      },
      hasAddons(key) {
            if (this.addons_description == null) {
                return false;
            }
            let addons = JSON.parse(
                this.addons_description
            );
            let attributes = {
                enabled: !isNaN(addons[key]),
                count: addons[key],
            };
            return attributes;
        },
  },
    data() {
        return {
            showTotalStatistics:false,
            showLessStatistics:3,
            flatFeatures: featuresBoolean,
            flatNumeric: featuresNumeric,
        };
    },
    mounted(){
        if (this.hide_bool_column) {
            this.showTotalStatistics = true
        }
    },
}
</script>
<style scoped>

@media screen and (max-width: 991px) {
    .statics-box{
        width: 100%;
        margin-bottom: 15px;
        margin-left: 0;
    }
    
}

</style>