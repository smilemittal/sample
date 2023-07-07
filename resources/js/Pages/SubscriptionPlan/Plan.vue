<template>
  <div>

    <InertiaHead title="Subscription Plan" />
    <AuthenticatedLayout>
      <div class="page-container">
        <div class="flex items-center justify-start flex-wrap">
          <div class="page-header">
            <h4 class="text-3xl text-neutral-300 font-bold">
              {{ trans("lang.labels.subscription_plan") }}
            </h4>
          </div>
        </div>
      </div>
      <div class="p-0 mt-5 w-full md:w-full lg:w-3/4">
        <form v-on:submit.prevent="createForm" class="mt-3">
          <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3">
            <div class="theme-input">
              <label for="name" class="text-gray-400 text-sm"> {{ trans("lang.labels.name") }} </label>
              <div class="relative mt-2">
                <input type="text" id="plan_name"
                  class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                  placeholder=" Enter the Plan Name " v-model="form.name" />
              </div>
              <span v-if="errors.name" class="mt-2 text-sm text-red-600 theme-error-message">{{ errors.name[0] }}</span>
            </div>
            <div class="theme-input">
              <label for="name" class="text-gray-400 text-sm"> {{ trans("lang.labels.price") }} </label>
              <div class="relative mt-2">
                <input type="text" id="price"
                  class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                  placeholder=" Enter the Price " v-model="form.price" />
              </div>
              <span v-if="errors.price" class="mt-2 text-sm text-red-600 theme-error-message">{{ errors.price[0] }}</span>
            </div>
            <template v-for="(section,index) in sections" :key="index">
              <div class="theme-input" v-for="(feature,index) in section.features" :key="'feature'+index" :ref="feature.key">
                <label :for="feature.key" class="text-gray-400 text-sm">
                  {{ feature.name }}
                </label>
                <div class="relative">
                  <input type="number" :id="feature.key"
                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer" v-model="form[feature.key]" />
                </div>
                <span v-if="errors[feature.key]" class="mt-2 text-sm text-red-600 theme-error-message">{{ errors[feature.key][0]
                }}</span>
              </div>
            </template>
            <div class="theme-input">
              <div class="relative mt-2">
                <label for="name" class="text-gray-400 text-sm">
                  {{ trans("lang.labels.valid_from") }}
                </label>
                <input type="date" id="city_name"
                  class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                  placeholder=" " v-model="form.valid_from" />
              </div>
              <span v-if="errors.valid_from" class="mt-2 text-sm text-red-600 theme-error-message">{{ errors.valid_from[0]
              }}</span>
            </div>
            <div class="theme-input">
              <label for="name" class="text-gray-400 text-sm"> {{ trans("lang.labels.valid_to") }} </label>
              <div class="relative mt-2">
                <input type="date" id="valid_to"
                  class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                  placeholder=" " v-model="form.valid_to" />
              </div>
            </div>
            <div class="theme-input">
              <label for="name" class="text-gray-400 text-sm">  {{ trans("lang.labels.interval") }} </label>
              <div class="relative mt-2">
                <select v-model="form.interval" id=""
                  class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500">
                  <option value="1">Monthly</option>
                  <option value="2">Yearly</option>
                </select>
              </div>
              <span v-if="errors.interval" class="mt-2 text-sm text-red-600 theme-error-message">{{ errors.interval[0]
              }}</span>
            </div>

            <div class="theme-input">
              <label for="name" class="text-gray-400 text-sm">  {{ trans("lang.labels.status") }} </label>
              <div class="relative mt-2">
                <select v-model="form.status" id=""
                  class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500">
                  <option value="1">Active</option>
                  <option value="2">In-Active</option>
                </select>
              </div>
              <span v-if="errors.status" class="mt-2 text-sm text-red-600 theme-error-message">{{ errors.status[0]
              }}</span>
            </div>
          </div>
          <div class="flex items-center justify-end mt-4 mb-6">
            <button class="text-sm bg-btnCancelBg text-btnCancelText px-6 py-2 rounded-lg">
              {{ trans("lang.modal.cancel") }}
            </button>
            <button class="text-sm bg-btnSubmitBg text-btnSubmitText px-6 py-2 rounded-lg ml-3">
              {{ trans("lang.modal.save") }}
            </button>
          </div>
        </form>
      </div>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useAppStore } from "@/store";
import { mapStores } from 'pinia'
import BetaProgram from "@/Pages/Profile/Partials/BetaProgram.vue";
import {sections, features} from '@/features.js';

export default {
  name: "Plan",
  props: ["id"],
  components: {
    
    AuthenticatedLayout,
    BetaProgram,
  },
  // props: ["sessions"],
  data() {
    return {
      form: {},
      sections: sections,
      flatFeatures: features,
      errors: {
        name: "",
        price: "",
        status: "",
        interval: "",
        valid_from: "",
        members: "",
      },
      formUrl: "",
      methodType: "",
    };
  },
  computed: {
    ...mapStores(useAppStore),
  },
  mounted() {
    if (this.id != null) {
      this.editData();
      this.formUrl = route("plans.update", this.id);
      this.methodType = "put";
    } else {
      this.formUrl = route("plans.store");
      this.methodType = "post";
    }
    // Do something useful with the data in the template
  },
  methods: {
    createForm() {
      this.processing = true;
      let that = this;
      const config = {
        method: that.methodType,
        url: that.formUrl,
        data: that.form,
      };
      axios(config)
        .then((res) => {
          if (res.data.status == true) {
            this.$inertia.visit(route("admin.plans.index"));
            // that.form = {};
            // that.isClose();
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);
          }
        })
        .catch((error) => {
          that.errors = error.response.data.errors;
          // error.response.status Check status code
        })
        .finally(() => {
          that.processing = false;
          //Perform action in always
        });
    },
    editData() {
      axios
        .get(route("plans.show", this.id))
        .then((res) => {
          this.form = res.data.data;
          this.sections.forEach(section => {
            section.features.forEach(feature => { 
              this.form[feature.key] = res.data.data.description[feature.key];
            });
          });
        })
        .catch((error) => {
          // error.response.status Check status code
        })
        .finally(() => {
          //Perform action in always
        });
    },
  },
  created: function () { },
};
</script>
