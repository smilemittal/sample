<template>
  <div class="flex justify-between items-center mt-4">
    <h3 class="text-grayCust-800 text-sm font-medium flex items-center">
      {{ name }} 
      <span v-if="addons" class="ml-1" v-tooltip="helpText">
        <new-subscription-puzzle-icon />
      </span>
    </h3>
    <component
        v-bind:is="
            allow ? 'NewRightSecondaryIcon'
                : 'NewCloseRedIcon'
        "
    />
  </div>
</template>

<script>
import NewSubscriptionPuzzleIcon from "@/ImageComponents/Profile/NewSubscriptionPuzzleIcon.vue";
import NewCloseRedIcon from "@/ImageComponents/Profile/NewCloseRedIcon.vue";
import NewRightSecondaryIcon from "@/ImageComponents/Profile/NewRightSecondaryIcon.vue";

export default {
  name: "BooleanUsageElement",
  
  components: {
    NewSubscriptionPuzzleIcon,
    NewCloseRedIcon,
    NewRightSecondaryIcon
  },
  props: {
    name: {},
    allow:false,
    addons:false,
    helpText:null,
  }
}
</script>

<style scoped>

</style>