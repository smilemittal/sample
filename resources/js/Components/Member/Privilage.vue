<template>
  <!-- Main modal -->
  <div
    id="createMembers"
    class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full md:h-full"
  >
    <div class="relative w-full h-auto max-w-2xl md:h-auto">
      <!-- Modal content -->
      <div class="relative rounded-lg shadow bg-body">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 rounded-t">
          <div>
            <h3 class="text-xl font-semibold text-gray-300">
              {{ data.modalTitle }}
            </h3>
            <h3 class="text-base text-gray-400 mt-3">
              {{ data.modalSubTitle }}
            </h3>
          </div>
          <button type="button" @click="isCloseAdd()" class="">
            <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
          </button>
        </div>
        <!-- Form start here -->
          <!-- Modal footer -->
          <div
            class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600"
          >
            <button
              type="button"
              @click="isCloseAdd()"
              class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            >
            {{ trans('lang.modal.cancel') }}
            </button>
            <button
              :disabled="formProcess"
              @click="submitClick()"
              class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5"
            >
            {{ trans('lang.modal.confirm') }}
            </button>
          </div>
      </div>
    </div>
  </div>
</template>

<script>

import { mapStores } from 'pinia'
import { useAppStore } from "@/store";
import { XCircleIcon } from "@heroicons/vue/24/solid";
export default {
  name: "Privilage",
  props: ["data"],
  components: {
    XCircleIcon,
  },
  data() {
    return {
      email: "",
      company_id: "",
      formProcess: false,
    };
  },
  methods: {
    closeAddNew() {
      this.$emit("cancel");
    },
    isCloseAdmin() {
      this.$emit("closeAdd");
      this.$emit("refreshTable");
    },
    isCloseAdd() {
      this.$emit("cancelPrivilage");
    },
    submitClick() {
      this.formProcess = true;
      this.$emit("privilageSubmitAction");
    },
    processingFunc() {
      this.formProcess = false;
    },
  },
  created() {},
  computed: {
        ...mapStores(useAppStore),
    },
};
</script>
