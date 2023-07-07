<template>
  <div>
    <InertiaHead title="Learning Path" />
    <AuthenticatedLayout>
      <div class="mb-4">
        <div class="flex items-center justify-between flex-wrap">
          <div class="page-header">
            <inertia-link :href="route('app.learning-path')"
              class="text-neutral-300 font-bold text-2xl sm:text-2xl md:text-4xl">{{
                trans("lang.labels.create_deleiver_learning_paths") }}</inertia-link>
            <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base">
              {{
                trans(
                  "lang.labels.all_learning_path_activities_is_managed_from_here"
                )
              }}
            </h5>
          </div>
        </div>
      </div>
      <div class="h-full drop-shadow-md rounded-3xl mt-5">
        <!----Your learning path table------->
        <div class="relative shadow-md sm:rounded-lg mt-5">
          <div class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <h4 class="text-xl text-neutral-300 font-semibold">
              {{ trans("lang.labels.learning_path") }}
            </h4>
            <div class="filterBtn">
              <button type="button" @click="openFilter()"
                class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
              </button>
            </div>
            <div class="flex justify-start items-center flex-wrap gap-3 mt-3 sm:mt-0 md:mt-0 multiplyMeTableActions">
              <div class="desktopFilters relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input type="text" id="simple-search" v-model="search"
                  class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 focus:border-amber-500 block w-full pl-10 p-2.5"
                  placeholder="Search by title" />
              </div>

              <button @click="openAdd()" v-if="is_archived == 0 && is_restore == 0"
                class="desktopFilters flex items-center justify-center h-10 w-max sm:w-fit md:w-fit text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                {{ trans("lang.labels.add_new_learning_path") }}
              </button>
              <inertia-link v-if="is_archived == 0 && is_restore == 0"
                class="desktopFilters h-10 text-sm w-32 flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                :href="route('app.learning-path.archive')">{{ trans("lang.modal.archived") }}</inertia-link>
              <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                {{ trans("lang.modal.archive_selected") }}
              </button>
              <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                {{ trans("lang.modal.unarchive_selected") }}
              </button>
              <inertia-link v-if="is_archived == 1"
                class="desktopFilters h-10 text-sm flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                :href="route('app.learning-path')">{{ trans("lang.labels.learning_path") }}</inertia-link>
            </div>
            <div v-if="mobileFilters" class="mobileFilters mt-4">
              <div class="relative inputMobileSpan">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input type="text" id="simple-search" v-model="search"
                  class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 focus:border-amber-500 block w-full pl-10 p-2.5"
                  placeholder="Search by title" />
              </div>
              <div class="mobileFilterIndentify mt-4">
                <button @click="openAdd()" v-if="is_archived == 0 && is_restore == 0"
                  class="flex items-center justify-center h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                  {{ trans("lang.labels.add_new_learning_path") }}
                </button>
                <inertia-link v-if="is_archived == 0 && is_restore == 0"
                  class="h-10 text-sm flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                  :href="route('app.learning-path.archive')">{{ trans("lang.modal.archived") }}</inertia-link>
                <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.archive_selected") }}
                </button>
                <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.unarchive_selected") }}
                </button>
                <inertia-link v-if="is_archived == 1"
                  class="h-10 text-sm flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                  :href="route('app.learning-path')">{{ trans("lang.labels.learning_path") }}</inertia-link>
              </div>
            </div>
          </div>
          <div class="themeOverflowCustom themeOverflowTable">
            <table class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableLearningPath">
              <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                <tr>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions">
                  <td class="w-4 p-4">
                    <div class="flex items-center">
                      <input id="checkbox-table-search-1" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                  </td>
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableIdentifySubject">
                    {{ trans("lang.labels.title") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableStatus">
                    {{ trans("lang.labels.members") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableStatus">
                    {{ trans("lang.labels.module") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.created_at") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="learningPath.length == 0" class="themeNoFound">
                  <td colspan="6">
                    <div class="px-4 py-8 text-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8" viewBox="0 0 576 512">
                        <path
                          d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                      </svg>
                      <p class="text-gray-400 text-base noFound">
                        {{ trans("lang.modal.no_result_found") }}
                      </p>
                    </div>
                  </td>
                </tr>
                <tr v-for="learningPaths in learningPath" :key="learningPaths.id"
                  class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                  <td class="p-4">
                    <div class="btn-group dropstart">
                      <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                        data-bs-toggle="dropdown" aria-expanded="false" data-dropdown-trigger="hover"
                        data-boundary="scrollParent">
                        <EllipsisVerticalIcon class="h-8 w-8 text-white m-auto" aria-hidden="true" />
                      </button>

                      <div ref="menus"
                        class="dropdown-menu theme-dropdown-menu pos-fixed dropdown-menu-end bg-card rounded-2xl p-0.5">
                        <ul class="py-0">
                          <li class="px-4 py-3 bg-card text-white rounded-t-2xl">
                            {{ trans("lang.labels.options") }}
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <inertia-link :href="route('edit-learning', learningPaths.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.edit_path") }}
                            </inertia-link>
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <a @click="Duplicate(learningPaths.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                              <ClipboardDocumentIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.duplicate") }}
                            </a>
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <a @click="openArchivedModal(learningPaths.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArchiveBoxIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.modal.archived") }}
                            </a>
                          </li>
                          <li class="" v-if="is_archived == 1 && is_restore != 1">
                            <a @click="openUnArcheivedModal(learningPaths.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArchiveBoxIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.modal.unarchived") }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="flex items-center justify-center">
                      <input id="checkbox-table-search-1" type="checkbox" :value="learningPaths.id"
                        v-model="multipleLearningPath"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                  </td>
                  <td class="p-4 breakWord">
                    <span class="themeManageTitle">{{
                      learningPaths.title
                    }}</span>
                  </td>
                  <td class="p-4">
                    <div v-if="learningPaths.countMember == 0"
                      class="bg-btnCancelBg w-7 h-7 flex items-center justify-center rounded-full">
                      <span class="text-sm text-btnCancelText">{{
                        learningPaths.countMember
                      }}</span>
                    </div>
                    <div v-else class="bg-bgAmberTag w-7 h-7 rounded-full flex items-center justify-center">
                      <span class="text-sm text-amber-500">{{
                        learningPaths.countMember
                      }}</span>
                    </div>
                  </td>
                  <td class="p-4">
                    <div v-if="learningPaths.countModule == 0"
                      class="bg-btnCancelBg w-7 h-7 flex items-center justify-center rounded-full">
                      <span class="text-sm text-btnCancelText">{{
                        learningPaths.countModule
                      }}</span>
                    </div>
                    <div v-else class="bg-bgAmberTag w-7 h-7 rounded-full flex items-center justify-center">
                      <span class="text-sm text-amber-500">{{
                        learningPaths.countModule
                      }}</span>
                    </div>
                  </td>
                  <td class="p-4">
                    <span>{{ learningPaths.created_at }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
        <Pagination v-if="pagination.lastPage != 1" @refreshTable="createTable" :currentPage="pagination.currentPage"
          :lastPage="pagination.lastPage" :total="pagination.total" />
      </div>
      <duplicate-path @closeDuplicate="isCloseDuplicate()" v-if="isDuplicate" :id="learningPathId"></duplicate-path>
      <delete-path ref="deletefunc" :data="modalData" @closeDelete="isCloseDelete()"
        @deleteSubmitAction="submitDeleteModelAction()" v-if="isDelete"></delete-path>
      <multiple ref="deletefunc" v-if="isMultipleModal" :data="modalData" @cancelMultipleArchive="cancelMultiple()"
        @multipleDelAction="submitMultipleDeleteAction()">
      </multiple>
      <add-path @closeAdd="isCloseAdd()" v-if="isAdd"></add-path>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import { ref } from 'vue'
import { detectOverflow } from '@popperjs/core';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DuplicatePath from "@/Components/LearningPath/DuplicateLearning.vue";
import DeletePath from "@/Components/LearningPath/DeleteLearning.vue";
import Multiple from "@/Components/LearningPath/MultipleArchive.vue";
import AddPath from "@/Components/LearningPath/AddLearning.vue";
import Pagination from "@/Components/Pagination.vue";
import {
  EllipsisVerticalIcon,
  PencilIcon,
  EyeIcon,
  ClipboardDocumentIcon,
  TrashIcon,
  ArchiveBoxIcon, FunnelIcon
} from "@heroicons/vue/24/solid";
import { mapStores } from "pinia";
import { useAppStore } from "@/store";
export default {
  components: {
    AuthenticatedLayout,
    DuplicatePath,
    DeletePath,
    Multiple,
    AddPath,
    Pagination,
    EllipsisVerticalIcon,
    PencilIcon,
    ArchiveBoxIcon,
    EyeIcon,
    ClipboardDocumentIcon,
    TrashIcon, FunnelIcon
  },
  props: ["is_archived", "is_restore"],
  data() {
    return {
      mobileFilters: false,
      learningPath: [],
      search: "",
      isDuplicate: false,
      isDelete: false,
      isMultipleModal: false,
      isAdd: false,
      searchTimeout: null,
      learningPathId: "",
      multipleLearningPath: [],
      queryData: {
        isDeleted: this.is_restore,
        isArchived: this.is_archived,
        sortField: "learning_paths.id",
        orderDir: "DESC",
        search: "",
      },
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
      },
      modalData: {
        memberId: "",
        modalText: "",
        buttonText: "",
        actionType: "",
      },
      menus: ref('menus'),
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
    openFilter() {
      this.mobileFilters = !this.mobileFilters;
    },
    submitDeleteModelAction() {
      let that = this;
      let formData = new FormData();
      formData.append("learningpath_id", that.modalData.learningPathId);
      formData.append("actionType", that.modalData.actionType);
      axios
        .post("/api/learning-path-action", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          //Perform Success Action
          if (res.data.status == true) {
            that.isDelete = false;
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);
            that.createTable(1);
            that.$refs.deletefunc.processingFunc();
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;
        })
        .finally(() => {
          //Perform action in always
          document.body.style.overflow = "";
        });
    },
    submitMultipleDeleteAction() {
      let that = this;
      let formData = new FormData();
      formData.append("actionType", that.modalData.actionType);
      formData.append("multipleLearningPath", that.multipleLearningPath);
      axios
        .post("/api/multiple-learning-action", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          //Perform Success Action
          if (res.data.status == true) {
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);
            that.createTable(1);
            that.$refs.deletefunc.processingFunc();
          }
          that.isMultipleModal = false;
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;
        })
        .finally(() => {
          //Perform action in always
          document.body.style.overflow = "";
        });
    },
    setPagination(response) {
      this.pagination.total = response.data.meta.total;
      this.pagination.lastPage = response.data.meta.last_page;
      this.pagination.currentPage = response.data.meta.current_page;
    },
    createTable(page) {
      this.queryData.page = page;
      //for table data is loading after fetch ==>
      axios
        .get("/api/learning-paths", { params: this.queryData })
        .then((response) => {
          this.learningPath = response.data.data;
          this.setPagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    openArchivedModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_learning_path"
      );
      this.modalData.learningPathId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
      document.body.style.overflow = "hidden";
    },
    openUnArcheivedModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_learning_path"
      );
      this.modalData.learningPathId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
      document.body.style.overflow = "hidden";
    },
    openSoftDeleteModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_delete_this_learning_path"
      );
      this.modalData.learningPathId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_delete_it");
      this.modalData.actionType = "softDelete";
      document.body.style.overflow = "hidden";
    },
    openRestoreModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_restore_this_learning_path"
      );
      this.modalData.learningPathId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_restore_it");
      this.modalData.actionType = "Restore";
      document.body.style.overflow = "hidden";
    },
    multipleArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_learning_paths"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
      document.body.style.overflow = "hidden";
    },
    multipleUnArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_learning_paths"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
      document.body.style.overflow = "hidden";
    },
    isCloseDuplicate() {
      this.isDuplicate = false;
      this.createTable(1);
      document.body.style.overflow = "";
    },
    isCloseDelete() {
      this.isDelete = false;
      document.body.style.overflow = "";
    },
    isCloseAdd() {
      this.isAdd = false;
      this.createTable(1);
      document.body.style.overflow = "";
    },
    cancelMultiple() {
      this.isMultipleModal = false;
      document.body.style.overflow = "";
    },
    Duplicate(id) {
      this.isDuplicate = true;
      this.learningPathId = id;
      document.body.style.overflow = "hidden";
    },
    openAdd() {
      this.isAdd = true;
      document.body.style.overflow = "hidden";
    },
  },
  created: function () {
    this.createTable(1);
  },
  unmounted() {
    clearTimeout(this.searchTimeout);
  },
  computed: {
    ...mapStores(useAppStore),
  },
};
</script>
