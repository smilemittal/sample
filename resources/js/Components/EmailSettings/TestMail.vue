<template>
  <!-- Main modal -->
  <div id="testMail"
    class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-auto max-w-4xl md:h-auto">
      <!-- Modal content -->
      <div class="relative rounded-lg shadow bg-body">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 rounded-t">
          <div>
            <h3 class="text-xl font-semibold text-gray-300">
              {{ trans("lang.labels.test_mail") }}
            </h3>
          </div>
          <button type="button" class="" @click="closeTestMail()">
            <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
          </button>
        </div>
        <!-- Form start here -->
        <div class="p-6 space-y-6 h-96 sm:h-96 lg:h-96 overflow-y-auto theme-modal-body">
          <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-3 mb-3">
            <div class="relative">
              <input type="text" id="module_data"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="form.host" />
              <label for="module_id"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.host") }}
              </label>
            </div>
            <div class="relative">
              <input type="text" id="module_data"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="form.port" />
              <label for="module_data"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.port") }}
              </label>
            </div>
            <div class="relative">
              <input type="text" id="module_data"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="form.encryption" />
              <label for="module_data"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.encryption") }}
              </label>
            </div>
            <div class="relative">
              <input type="text" id="module_data"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="form.username" />
              <label for="module_data"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.username") }}
              </label>
            </div>
            <div class="relative">
              <input type="text" id="module_data"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="form.password" />
              <label for="module_data"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.password") }}
              </label>
            </div>
            <div class="relative">
              <input type="text" id="module_data"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="form.from_address" />
              <label for="module_data"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.from_address") }}
              </label>
            </div>
            <div class="relative">
              <input type="text" id="module_data"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="form.from_name" />
              <label for="module_data"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.from_name") }}
              </label>
            </div>
            <div class="relative">
              <input type="text" id="module_data"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="form.email" />
              <label for="module_data"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.email_address") }}
              </label>
            </div>
          </div>
          <div class="grid grid-cols-1 gap-3 mt-5 mb-3">
            <div class="relative">
              <input type="text" id="module_data"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="form.subject" />
              <label for="module_data"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.subject") }}
              </label>
            </div>
          </div>
          <div class="mb-3 mt-4">
            <textarea v-model="form.desc" rows="4"
              class="block p-2.5 w-full text-sm text-gray-900 bg-sidebar rounded-lg border-1 border-transparent dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500"
              placeholder="Message"></textarea>
          </div>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
          <button @click="closeTestMail()" type="button"
            class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center">
            {{ trans("lang.modal.close") }}
          </button>
          <button type="button" @click="submitForm()" :disabled="formProcess"
            class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
            {{ trans("lang.labels.test") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import { XCircleIcon } from "@heroicons/vue/24/solid";
export default {
  name: "TestMail",
  props: ['form'],
  components: {
    XCircleIcon,
  },
  data(){
    return{
      formProcess:false
    }
  },
  methods: {
    submitForm() {
      let that = this;
      that.formProcess = true;
      axios
        .post(route("email-settings.test"), that.form)
        .then((res) => {
          if (res.data.status == true) {
            that.isCloseEditModal();
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;

        })
        .finally(() => {
          //Perform action in always
          that.formProcess = false;
        });
    },
    closeTestMail() {
      this.$emit("closeTest");
    },
  },
};
</script>
