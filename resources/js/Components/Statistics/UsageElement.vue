<template>
  <div>
    <div class="flex justify-between items-center mt-4">
      <div class="flex items-center">
        <p class="text-grayCust-800 text-sm font-medium focus-visible:outline-none">
          {{ name }}
        </p>
        <span v-if="addons" class="ml-1" v-tooltip="helpText">
          <new-subscription-puzzle-icon />
        </span>
      </div>
      <span class="ml-2 text-grayCust-800 text-sm focus-visible:outline-none">{{ used.toFixed(decimalPoint) }}/{{ total }}</span>
    </div>
    <div class="bg-grayCust-250 rounded-xl mt-0.5 h-1.5 focus-visible:outline-none">
      <div class="h-1.5 rounded-xl w-4 focus-visible:outline-none" :style="{ width:Math.min(usedPercentage,100) + '%'}" :class="progressColorClass"></div>
    </div>
  </div>
</template>

<script>
import NewSubscriptionPuzzleIcon from "@/ImageComponents/Profile/NewSubscriptionPuzzleIcon.vue";

export default {
  name: "UsageElement",
  
  components: {
    NewSubscriptionPuzzleIcon
  },
  props: {
    name: {},
    total: {},
    used: {},
    decimalPoint:{},
    usedPercentage: {},
    progressColorClass: {
      default:'bg-insta-primary'
    },
    addons:false,
    helpText:null,
  }
}
</script>

<style scoped>

</style>