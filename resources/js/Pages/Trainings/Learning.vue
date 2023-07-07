<template>
  <div>

    <InertiaHead title="My Trainings" />
    <AuthenticatedLayout>
      <div class="mb-4">
        <div class="flex items-center justify-between flex-wrap">
          <div class="page-header">
            <inertia-link :href="route('app.my-trainings')" class="text-4xl text-neutral-300 font-bold">{{
              trans("lang.labels.learning_path") }}</inertia-link>
            <h5 class="text-base text-neutral-400">
              {{ trans("lang.labels.a_series_of_modules") }}
            </h5>
            <button  @click="openLearningPath()" type="button" class="bg-btnSubmitBg px-2 py-1 rounded-lg text-btnSubmitText breakWord mt-1"><span
                class="themeManageButton">{{ learningpath.title }}</span></button>
            <button type="button" style="color:white"></button>
          </div>
        </div>
      </div>
      <div class="h-full drop-shadow-md rounded-3xl mt-5">
        <!----Upcoming trainings------->
        <div class="relative shadow-md sm:rounded-lg mt-5">
          <div class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <h4 class="text-xl text-neutral-300 font-semibold">
              {{ trans("lang.labels.Modules") }}
            </h4>
            <div class="filterBtn">
              <button type="button" @click="openFilter()"
                class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
              </button>
            </div>
            <div class="desktopFilters flex justify-between items-center flex-wrap gap-3">
              <div class="flex flex-wrap items-center mt-3 sm:mt-0 w-full sm:w-fit">
                <label for="review_status" class="block w-full sm:w-fit text-sm font-medium dark:text-gray-400">
                  {{ trans("lang.labels.filter_by_status") }}
                </label>
                <select id="review_status" @change="changeStatus($event)"
                  class="bg-sidebar w-full sm:w-fit sm:ml-2 mt-3 sm:mt-0  border-1 border-zinc-300 text-gray-400 text-sm rounded-lg focus:ring-0 focus:border-amber-500 block p-2.5">
                  <option>Select</option>
                  <option value="1">Active</option>
                  <option value="0">In-Active</option>
                </select>
              </div>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input type="text" id="simple-search" v-model="searchAssign"
                  class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                                          focus:border-amber-500 block w-full pl-10 p-2.5" placeholder="Search">
              </div>
            </div>
            <div v-if="mobileFilters" class="mobileFilters flex flex-wrap gap-1 mt-4">
              <label for="review_status" class="block w-full sm:w-fit text-sm font-medium dark:text-gray-400">
                {{ trans("lang.labels.filter_by_status") }}
              </label>
              <select id="review_status" @change="changeStatus($event)"
                class="bg-sidebar w-full sm:w-fit sm:ml-2 mt-3 sm:mt-0  border-1 border-zinc-300 text-gray-400 text-sm rounded-lg focus:ring-0 focus:border-amber-500 block p-2.5">
                <option>Select</option>
                <option value="1">Active</option>
                <option value="0">In-Active</option>
              </select>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input type="text" id="simple-search" v-model="searchAssign" class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                                      focus:border-amber-500 block w-full pl-10 p-2.5"
                  placeholder="Search">
              </div>
            </div>
          </div>
          <div class="themeOverflowCustom themeOverflowTable">
            <div v-if="assignedModule.length == 0" class="themeNoFound bg-body">
              <div>
                <div class="px-4 py-8 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8" viewBox="0 0 576 512">
                    <path
                      d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                  </svg>
                  <p class="text-gray-400 text-base noFound">
                    {{ trans("lang.modal.no_result_found") }}
                  </p>
                </div>
              </div>
            </div>
            <table v-else class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableDevelop">
              <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                <tr>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.image") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.title") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.status") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(form, index) in assignedModule" :key="form.id"
                  class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                  <td class="p-4">
                    <!-- Default dropstart button -->
                    <div class="btn-group dropstart" v-if="index === 0">
                      <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                        data-bs-toggle="dropdown" aria-expanded="false" data-dropdown-trigger="hover">
                        <EllipsisVerticalIcon class="h-8 w-8 text-white m-auto" aria-hidden="true" />
                      </button>
                      <div class="dropdown-menu theme-dropdown-menu bg-card rounded-2xl p-0.5">
                        <ul class="py-0">
                          <li class="px-4 py-3 bg-card text-white rounded-t-2xl">
                            {{ trans("lang.labels.options") }}
                          </li>
                          <li v-if="!(learningpath.archived_at != null
                            || form.archived_at != null || form.company_archived_at != null)">
                            <inertia-link
                              :href="route('digital-asset', { 'id': form.typeform_id, 'form_id': form.encryptId, 'learning_path': form.encrypt_learning_group_id, 'redirect_url': route('complete-learning', form.encrypt_learning_group_id) })"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                              <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.open_module") }}
                            </inertia-link>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td class="p-4">
                    <img :src="mediaPath + form.image" alt="form" class="w-14 h-14 rounded-lg object-cover" />
                  </td>
                  <td class="p-4 breakWord">
                    <span v-if="form.archived_at != null || form.company_archived_at != null">Archive</span>
                    <span>{{ form.display_title }}</span>
                  </td>
                  <td class="p-4">
                    <span :class="form.statusClass">{{ form.status }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
          <Pagination v-if="pagination.lastPage != 1" @refreshTable="assignTable" :currentPage="pagination.currentPage"
            :lastPage="pagination.lastPage" :total="pagination.total" />
        </div>

        <!-----Completed Training------->
        <div class="relative shadow-md sm:rounded-lg mt-16">
          <div class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <h4 class="text-xl text-neutral-300 font-semibold">
              {{ trans("lang.labels.completed_modules_from_above_learning_path") }}
            </h4>
            <div class="filterBtn">
              <button type="button" @click="openCompletedFilter()"
                class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
              </button>
            </div>
            <div class="desktopFilters relative w-full sm:w-fit mt-3 sm:mt-0">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                  viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"></path>
                </svg>
              </div>
              <input type="text" id="simple-search" v-model="searchCompleted"
                class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                                          focus:border-amber-500 block w-full pl-10 p-2.5" placeholder="Search">
            </div>
            <div v-if="completedFilters" class="mobileFilters flex flex-wrap gap-1 mt-4">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input type="text" id="simple-search" v-model="searchCompleted" class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                                      focus:border-amber-500 block w-full pl-10 p-2.5"
                  placeholder="Search">
              </div>
            </div>
          </div>
          <div class="themeOverflowCustom themeOverflowTable">
            <div v-if="completedModules.length == 0" class="themeNoFound bg-body">
              <div>
                <div class="px-4 py-8 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8" viewBox="0 0 576 512">
                    <path
                      d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                  </svg>
                  <p class="text-gray-400 text-base noFound">
                    {{ trans("lang.modal.no_result_found") }}
                  </p>
                </div>
              </div>
            </div>
            <table v-else class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400">
              <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                <tr>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.image") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.title") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.count") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.status") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.latest_completedat") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="form in completedModules" :key="form.id"
                  class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                  <td class="p-4">
                    <!-- Default dropstart button -->
                    <div class="btn-group dropstart">
                      <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                        data-bs-toggle="dropdown" aria-expanded="false" data-dropdown-trigger="hover">
                        <EllipsisVerticalIcon class="h-8 w-8 text-white m-auto" aria-hidden="true" />
                      </button>
                      <div class="dropdown-menu theme-dropdown-menu bg-card rounded-2xl p-0.5">
                        <ul class="py-0">
                          <li class="px-4 py-3 bg-card text-white rounded-t-2xl">
                            {{ trans("lang.labels.options") }}
                          </li>
                          <li v-if="!(learningpath.archived_at != null
                            || form.archived_at != null || form.company_archived_at != null)">
                            <inertia-link
                              :href="route('digital-asset', { 'id': form.typeform_id, 'form_id': form.encryptId, 'learning_path': form.encrypt_learning_group_id, 'redirect_url': route('complete-learning', form.encrypt_learning_group_id) })"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                              <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.open_module") }}
                            </inertia-link>
                          </li>
                          <li class="">
                            <a @click="openHistory(form.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArrowPathIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.view_history") }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td class="p-4">
                    <span v-if="(form.archived_at != null || form.company_archived_at != null)">Archive</span>
                    <img :src="mediaPath + form.image" alt="form" class="w-14 h-14 rounded-lg object-cover" />
                  </td>
                  <td class="p-4 breakWord">
                    <span>{{ form.display_title }}</span>
                  </td>
                  <td class="p-4">
                    <span class="" :class="form.counterClass">{{
                      form.learningformCompletedCounter
                    }}</span>
                  </td>
                  <td class="p-4">
                    <span :class="form.statusClass">{{ form.status }}</span>
                  </td>
                  <td class="p-4">
                    <span>{{ form.moduleCompletedAt }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
        <Pagination v-if="pagination.lastPage != 1" @refreshTable="completedTable" :currentPage="pagination.currentPage"
          :lastPage="pagination.lastPage" :total="pagination.total" />
      </div>
      <complete-learning-history @closeHistory="closeModuleHistory()" v-if="isLearningHistory" :learningPathID="this.id"
        :formId="this.formId"></complete-learning-history>
      <edit-path @closeEdit="isCloseEdit()" v-if="isEdit" :learningPathId="id" :userRole="loggedUserRole"
        :heading="operation"></edit-path>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CompleteLearningHistory from "@/Components/LearningPath/CompletePathHistory.vue";
import Pagination from "@/Components/Pagination.vue";
import EditPath from '@/Components/LearningPath/EditLearning.vue';
import {
  EllipsisVerticalIcon,
  PencilIcon,
  TrashIcon,
  ArrowPathIcon, FunnelIcon
} from "@heroicons/vue/24/solid";
export default {
  props: ["id", 'user'],
  components: {
    AuthenticatedLayout,

    CompleteLearningHistory,
    Pagination,

    EllipsisVerticalIcon,
    PencilIcon,
    TrashIcon,
    ArrowPathIcon, FunnelIcon,
    EditPath
  },
  data() {
    return {
      mobileFilters: false,
      completedFilters: false,
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
      },
      status: "",
      assignedModule: [],
      completedModules: [],
      searchAssign: "",
      searchCompleted: "",
      searchLearning: "",
      searchTimeout: null,
      assignedQueryData: {
        search: "",
        page: 1,
        sortField: "learning_path_forms.display_order",
        orderDir: "ASC",
        assignStatus: null,
      },
      completedQueryData: {
        search: "",
        page: 1,
        sortField: "learningpath.display_order",
        orderDir: "DESC",
      },
      isLearningHistory: false,
      formId: "",
      isEdit: false,
      learningpath: {},
      loggedUserRole: '',
      operation: 'view'
    };
  },
  watch: {
    searchAssign: function (value) {
      let self = this;
      clearTimeout(self.searchTimeout);
      self.searchTimeout = setTimeout(function () {
        self.assignedQueryData.search = self.searchAssign;
        self.assignTable();
      }, 700);
    },
    searchCompleted: function (value) {
      let self = this;
      clearTimeout(self.searchTimeout);
      self.searchTimeout = setTimeout(function () {
        self.completedQueryData.search = self.searchCompleted;
        self.completedTable();
      }, 700);
    },
    isLearningHistory: function () {
      document.body.style.overflow = this.isLearningHistory ? "hidden" : "";
    },
    isEdit: function () {
      document.body.style.overflow = this.isEdit ? "hidden" : "";
    },
  },
  methods: {
    openFilter() {
      this.mobileFilters = !this.mobileFilters;
    },
    openCompletedFilter() {
      this.completedFilters = !this.completedFilters;
    },
    isCloseTraining() {
      this.isTraining = false;
    },
    openHistory(id) {
      this.isLearningHistory = true;
      this.formId = id;
    },
    closeModuleHistory() {
      this.isLearningHistory = false;
      this.formId = "";
    },
    openLearningPath() {
      this.isEdit = true;
    },
    isCloseEdit() {
      this.isEdit = false;
    },
    setPagination(response) {
      this.pagination.total = response.data.meta.total;
      this.pagination.lastPage = response.data.meta.last_page;
      this.pagination.currentPage = response.data.meta.current_page;
    },
    assignTable(page) {
      this.assignedQueryData.page = page;
      //for table data is loading after fetch ==>
      axios
        .get("/api/assign-learning-modules/" + this.id, {
          params: this.assignedQueryData,
        })
        .then((response) => {
          this.assignedModule = response.data.data;
          this.setPagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    completedTable(page) {
      this.completedQueryData.page = page;
      axios
        .get("/api/completed-learning-modules/" + this.id, {
          params: this.completedQueryData,
        })
        .then((response) => {
          this.completedModules = response.data.data;
          this.setPagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    changeStatus(event) {
      let that = this;
      if (event.target.value != 'Select') {
        that.assignedQueryData.assignStatus = event.target.value;
      } else {
        that.assignedQueryData.assignStatus = null;
      }

      that.assignTable(1);
    },
    viewLearningPath() {
      axios.get('/api/edit-learning-path/' + this.id)
        .then((response) => {
          if (response.status == 200) {
            this.learningpath = response.data.data;
          }
        })
    },
  },
  created: function () {
    this.assignTable(1);
    this.completedTable(1);
    this.viewLearningPath();
    this.loggedUserRole = this.user.role.name;
  },
};
</script>
