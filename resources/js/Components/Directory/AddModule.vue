<template>
  <!-- Main modal -->
  <div id="duplicatePath"
    class="bg-bgModal fixed top-0 flex items-center justify-center left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-auto max-w-2xl md:h-auto">
      <!-- Modal content -->
      <div class="relative rounded-lg shadow bg-body">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 rounded-t">
          <div>
            <h3 class="text-xl font-semibold text-gray-300">
              {{ trans("lang.site.add_module_folder") }}
              {{ directoryId }}
            </h3>
          </div>
          <button type="button" @click="$emit('closeModule')" class="">
            <XCircleIcon class="h-9 w-9 text-white" aria-hidden="true" />
          </button>
        </div>

        <div class="p-6 h-fit theme-modal-body">
            <!-- Form start here -->

            <div class="mb-3 bg-sidebar rounded-t-2xl p-4">
              <input type="text" id="title_path"
                class="w-full px-2.5 pb-2.5 pt-2.5 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-gray-600 appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder="Search" v-model="search" />
              <div class="flex flex-wrap mt-4">
                <button type="button" @click="library()" :class="librayClass">
                  Library
                </button>
                <button type="button" @click="xmeArea()" :class="xmeClass">
                  Xme Area
                </button>
              </div>
            </div>
            <div class="theme-tableOverflow">
              <div class="min-h-fit max-h-96 overflow-y-auto">
                <table class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                  <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                    <tr>
                      <th scope="col" class="p-4"></th>
                      <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                        {{ trans("lang.site.image") }}
                      </th>
                      <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                        {{ trans("lang.site.title") }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="modulesItem in modules" :key="modulesItem.id"
                      class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                      <td class="w-4 p-4">
                        <button @click="
                          addModule(modulesItem.id, modulesItem.display_title)
                        " :disabled="processing"
                          class="bg-btnSubmitBg text-btnSubmitText hover:text-white hover:bg-amber-500 rounded-lg px-4 py-2">
                          {{ trans("lang.modal.add") }}
                        </button>
                      </td>
                      <td class="p-4">
                        <img src="./../../../img/placeholder-logo.jpeg" class="w-54 h-20 rounded-lg object-cover" />
                      </td>
                      <td class="p-4 w-96">
                        <span>{{ modulesItem.display_title }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-6 space-x-2 rounded-b dark:border-gray-600">
              <button @click="$emit('closeModule')"
                class="text-btnCancelText bg-btnCancelBg rounded-lg text-sm font-medium px-5 py-2.5">
                {{ trans("lang.modal.cancel") }}
              </button>
              <button :disabled="processing" @click="$emit('closeModule')"
                class="text-btnSubmitText bg-btnSubmitBg rounded-lg text-sm font-medium px-5 py-2.5">
                {{ trans("lang.modal.done") }}
              </button>
            </div>
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
  name: "AddModule",
  props: ["id", "directoryId"],
  components: {
    XCircleIcon,
  },
  data() {
    return {
      searchTimeout: null,
      modules: [],
      search: "",
      folder: "",
      queryData: {
        search: "",
        type: "",
      },
      processing: false,
      xmeClass: "bg-btnSubmitBg px-4 py-3  text-btnSubmitText rounded-lg ml-3",
      librayClass: "bg-amber-500 px-4 py-3  rounded-lg text-white ml-3",
    };
  },
  watch: {
    search: function (value) {
      let self = this;
      clearTimeout(self.searchTimeout);
      self.searchTimeout = setTimeout(function () {
        self.queryData.search = self.search;
        self.createTable();
      }, 700);
    },
  },
  methods: {
    createTable() {
      axios
        .get("/api/pending-directory-modules/" + this.id, {
          params: this.queryData,
        })
        .then((res) => {
          this.modules = res.data.data;
        })
        .catch((error) => {
          // error.response.status Check status code
        })
        .finally(() => {
          //Perform action in always
        });
    },
    addModule(moduleId, title) {
      this.processing = true;
      let formData = new FormData();
      formData.append("form_id", moduleId);
      formData.append("title", title);
      axios
        .post("/api/add-directory-module/" + this.id, formData)
        .then((res) => {
          if (res.status == 200) {
            this.createTable();
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            this.appStore.setNotification(notification);
          }
          this.$emit('closeModule');
          // this.modules = res.data.data;
        })
        .catch((error) => {
          // error.response.status Check status code
        })
        .finally(() => {
          this.processing = false;
          //Perform action in always
          document.body.style.overflow = '';
        });
    },
    library() {
      this.queryData.type = "library";
      this.createTable();
      this.librayClass = "bg-amber-500 px-4 py-3  rounded-lg text-white ml-3";
      this.xmeClass =
        "bg-btnSubmitBg px-4 py-3  text-btnSubmitText rounded-lg ml-3";
    },
    xmeArea() {
      this.queryData.type = "xme-area";
      this.createTable();
      this.xmeClass = "bg-amber-500 px-4 py-3  rounded-lg text-white ml-3";
      this.librayClass =
        "bg-btnSubmitBg px-4 py-3  text-btnSubmitText rounded-lg ml-3";
    },
  },
  created() {
    this.createTable();
  },
  unmounted() {
    clearTimeout(this.searchTimeout);
  },
  computed: {
        ...mapStores(useAppStore),
    },
};
</script>
