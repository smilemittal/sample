<template>
  <div>
    <InertiaHead title="Manage Companies" />
    <AuthenticatedLayout>
      <div class="mt-2">
        <inertia-link :href="route('admin.manage-companies')"
          class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">
          {{ trans("lang.labels.companies_manager") }}</inertia-link>
        <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base">
          {{ trans("lang.labels.add_new_companies_or_manage_existing") }}
        </h5>
      </div>
      <div class="dark:bg-gray-700 drop-shadow-lg h-full drop-shadow-md rounded-3xl mt-5">
        <div class="relative shadow-md sm:rounded-lg mt-5">
          <div class="p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <div class="flex justify-between items-center flex-wrap">
              <h4 v-if="is_archived == 1" class="text-xl text-neutral-300 font-semibold">
                {{ trans("lang.labels.companies") }}
              </h4>
              <h4 v-if="is_archived == 0" class="text-xl text-neutral-300 font-semibold">
                {{ trans("lang.labels.companies") }}
              </h4>
              <div class="filterBtn">
                <button type="button" @click="openFilter()"
                  class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                  <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
                </button>
              </div>
              <div class="flex justify-start items-center flex-wrap mt-3 sm:mt-0 md:mt-0 multiplyMeTableActions gap-2">
                <div class="desktopFilters relative searchTable">
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
                    placeholder="Search by company & contact" />
                </div>
                <button @click="isAddModal = true" v-if="is_archived == 0 && is_restore == 0"
                  class="desktopFilters h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.add_new") }}
                </button>
                <inertia-link v-if="is_archived == 0 && is_restore == 0" :href="route('admin.manage-companies.archive')"
                  class="desktopFilters flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">{{
                    trans("lang.modal.archived") }}</inertia-link>
                <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                  class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.unarchive_selected") }}
                </button>

                <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                  class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.archive_selected") }}
                </button>
                <inertia-link v-if="is_archived == 1" :href="route('admin.manage-companies')"
                  class="desktopFilters flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">{{
                    trans("lang.labels.companies") }}</inertia-link>
              </div>
            </div>
            <div v-if="mobileFilters" class="mobileFilters flex flex-wrap gap-1 mt-4">
              <div class="relative searchTable">
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
                  placeholder="Search by company & contact" />
              </div>
              <button @click="isAddModal = true" v-if="is_archived == 0 && is_restore == 0"
                class="h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                {{ trans("lang.modal.add_new") }}
              </button>
              <inertia-link v-if="is_archived == 0 && is_restore == 0" :href="route('admin.manage-companies.archive')"
                class="flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">{{
                  trans("lang.modal.archived") }}</inertia-link>
              <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                {{ trans("lang.modal.unarchive_selected") }}
              </button>

              <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                {{ trans("lang.modal.archive_selected") }}
              </button>
              <inertia-link v-if="is_archived == 1" :href="route('admin.manage-companies')"
                class="flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">{{
                  trans("lang.labels.companies") }}</inertia-link>
            </div>
          </div>
          <div class="themeOverflowCustom themeOverflowTable">
            <div v-if="items.length == 0" class="themeNoFound bg-body">
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
            <table v-else class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableCompany">
              <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                <tr>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                  <th scope="col" class="p-4 tableMultiActions">
                    <div class="flex items-center justify-center">
                      <input id="checkbox-all-search" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableImage">
                    {{ trans("lang.labels.image") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableCompanyName">
                    {{ trans("lang.labels.company_name") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableContact">
                    {{ trans("lang.labels.contact") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableCreatedAt">
                    {{ trans("lang.labels.created") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in items" :key="item.id"
                  class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                  <td class="p-4 text-end">
                    <!-- Default dropstart button -->
                    <div class="btn-group dropstart">
                      <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                        data-bs-toggle="dropdown" aria-expanded="false" data-dropdown-trigger="hover">
                        <EllipsisVerticalIcon class="h-8 w-8 text-white" aria-hidden="true" />
                      </button>
                      <div class="dropdown-menu theme-dropdown-menu bg-card rounded-2xl p-0.5">
                        <ul class="">
                          <li class="px-4 py-3 bg-card text-white rounded-t-2xl">
                            {{ trans("lang.labels.options") }}
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <a class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white"
                              @click="edit(item.id)">
                              <PencilIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.labels.edit_business") }}
                            </a>
                          </li>
                          <li class="rounded-b-2xl" v-if="is_archived != 1">
                            <a @click="openArchivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArchiveBoxArrowDownIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.archived") }}
                            </a>
                          </li>
                          <li class="rounded-b-2xl" v-if="is_archived == 1 && is_restore != 1">
                            <a @click="openUnArcheivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArchiveBoxXMarkIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.unarchived") }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td class="w-4 p-4">
                    <div class="flex items-center justify-center">
                      <input id="checkbox-table-search-1" :value="item.id" type="checkbox" v-model="multipleCompanies"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                  </td>
                  <td class="p-4">
                    <img :src="mediaPath + item.logo" alt="logo" class="w-26 h-14 rounded-lg object-cover" />
                  </td>
                  <td class="p-4 breakWord">
                    <span>{{ item.name }}</span>
                  </td>
                  <td class="p-4">
                    <span>{{ item.phone_nr }}</span>
                  </td>
                  <td class="p-4">
                    <span>{{ item.created_at }}</span>
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
      <create-modal @closeAdd="isCloseAddNew()" v-if="isAddModal"></create-modal>
      <edit-modal @cancel="isCloseEdit()" v-if="isEditModal" :data="modalData"></edit-modal>
      <delete-modal ref="deletefunc" @cancel="isCloseDelete()" v-if="isDeleteModal" :data="modalData"
        @deleteSubmitAction="submitDeleteModelAction()"></delete-modal>
      <multiple ref="deletefunc" v-if="isMultipleModal" :data="modalData" @cancelMultipleArchive="cancelMultiple()"
        @multipleDelAction="submitMultipleDeleteAction()"></multiple>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CreateModal from "@/Components/Company/Add.vue";
import EditModal from "@/Components/Company/Edit.vue";
import DeleteModal from "@/Components/Company/Delete.vue";
import Multiple from "@/Components/Company/MultipleArchive.vue";
import Pagination from "@/Components/Pagination.vue";
import {
  EllipsisVerticalIcon,
  PencilIcon,
  TrashIcon,
  ArrowPathIcon,
  ArchiveBoxArrowDownIcon,
  ArchiveBoxXMarkIcon,
  FunnelIcon
} from "@heroicons/vue/24/solid";
import { mapStores } from "pinia";
import { useAppStore } from "@/store";
export default {
  props: ["editItemId", "is_archived", "is_restore"],
  components: {
    CreateModal,
    EditModal,
    DeleteModal,
    AuthenticatedLayout,

    Pagination,
    Multiple,
    EllipsisVerticalIcon,
    PencilIcon,
    TrashIcon,
    ArrowPathIcon,
    ArchiveBoxArrowDownIcon,
    ArchiveBoxXMarkIcon,
    FunnelIcon
  },
  data() {
    return {
      mobileFilters: false,
      searchTimeout: null,
      items: [],
      search: "",
      isAddModal: false,
      isEditModal: false,
      isDeleteModal: false,
      isMultipleModal: false,
      modalData: {
        editItemId: "",
        actionType: "",
        modalText: "",
        buttonText: "",
        permanantTitle: "",
        permanantSubTitle: "",
      },
      queryData: {
        search: "",
        is_deleted: this.is_restore,
        page: 1,
        isArchived: this.is_archived,
      },
      multipleCompanies: [],
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
      },
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
    isAddModal: function () {
      document.body.style.overflow = this.isAddModal ? "hidden" : "";
    },
    isEditModal: function () {
      document.body.style.overflow = this.isEditModal ? "hidden" : "";
    },
    isDeleteModal: function () {
      document.body.style.overflow = this.isDeleteModal ? "hidden" : "";
    },
    isMultipleModal: function () {
      document.body.style.overflow = this.isMultipleModal ? "hidden" : "";
    },
  },
  methods: {
    openFilter() {
      this.mobileFilters = !this.mobileFilters;
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
        .get("/api/companies", { params: this.queryData })
        .then((response) => {
          this.items = response.data.data;
          this.setPagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    submitDeleteModelAction() {
      let that = this;
      let formData = new FormData();
      formData.append("company_id", that.modalData.editItemId);
      formData.append("actionType", that.modalData.actionType);
      axios
        .post("/api/company-action", formData, {
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
            that.isDeleteModal = false;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;

          //if any error happened remove modal and overflow itself
          that.isDeleteModal = false;
        })
        .finally(() => {
          //Perform action in always
        });
    },
    submitMultipleDeleteAction() {
      let that = this;
      let formData = new FormData();
      formData.append("actionType", that.modalData.actionType);
      formData.append("multipleCompanies", that.multipleCompanies);
      axios
        .post("/api/multiple-company-action", formData, {
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
            that.isMultipleModal = false;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;

          //if any error happened remove modal and overflow itself
          that.isMultipleModal = false;
        })
        .finally(() => {
          //Perform action in always
        });
    },
    edit(id) {
      let that = this;
      that.modalData.editItemId = id;
      that.isEditModal = true;
      console.log(that.modalData);
    },

    openRestoreModal(id) {
      this.isDeleteModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_restore_this_company"
      );
      this.modalData.editItemId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_restore_it");
      this.modalData.actionType = "Restore";
    },
    openHardDeleteModal(id, name) {
      this.isDeleteModal = true;
      this.modalData.modalText = "Deleting " + name + "Company";
      this.modalData.editItemId = id;
      this.modalData.buttonText = "Yes! Delete!!";
      this.modalData.actionType = "Hard Delete";
      this.modalData.permanantTitle = "Confirmation needed";
      this.modalData.permanantSubTitle =
        "Deleted Company details will not be recoverable";
    },
    openSoftDeleteModal(id) {
      this.isDeleteModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_delete_this_company"
      );
      this.modalData.editItemId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_delete_it");
      this.modalData.actionType = "softDelete";
    },
    openArchivedModal(id) {
      this.isDeleteModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_company"
      );
      this.modalData.editItemId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
    },
    openUnArcheivedModal(id) {
      this.isDeleteModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_company"
      );
      this.modalData.editItemId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },
    multipleArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_company"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
      console.log(this.modalData);
    },
    multipleUnArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_company"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },
    isCloseAddNew() {
      this.isAddModal = false;
      this.createTable(1);
    },
    isCloseEdit() {
      this.isEditModal = false;
      this.createTable(1);
    },
    isCloseDelete() {
      this.isDeleteModal = false;
    },
    cancelMultiple() {
      this.isMultipleModal = false;
    },
    trash() {
      this.is_deleted = 1;
      this.createTable(1);
    },
    companies() {
      this.is_deleted = 0;
      this.createTable(1);
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
