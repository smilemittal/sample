<template>
  <div>
    <InertiaHead title="Manage Modules" />
    <AuthenticatedLayout>
      <div class="mt-2">
        <inertia-link :href="route('admin.manage-forms')"
          class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{ trans("lang.labels.module_manager")
          }}</inertia-link>
        <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base">
          {{
            trans("lang.labels.add_new_module_and_connect_module_to_clients")
          }}
        </h5>
      </div>
      <div class="h-full mt-5">
        <div class="relative shadow-md sm:rounded-lg mt-5">
          <div class="p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <div class="flex justify-between items-center flex-wrap">
              <h4 class="text-xl text-neutral-300 font-semibold">
                {{ trans("lang.labels.module") }}
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
                    placeholder="Search by title" />
                </div>
                <div class="desktopFilters w-full sm:w-fit md:w-fit">
                  <span class="text-base text-neutral-400 block w-full sm:w-fit md:w-fit">{{
                    trans("lang.labels.filter_by_status") }}</span>
                </div>
                <select id="form_status" @change="changeStatus($event)"
                  class="desktopFilters bg-sidebar border border-gray-300 text-neutral-400 text-sm rounded-lg p-2.5 focus:border-amber-500 outline-0"
                  v-model="status">
                  <option>{{ trans("lang.labels.status") }}</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
                <select id="form_type" @change="changeType($event)"
                  class="desktopFilters bg-sidebar border border-gray-300 text-neutral-400 text-sm rounded-lg p-2.5 focus:border-amber-500 outline-0"
                  v-model="type">
                  <option>{{ trans("lang.labels.type") }}</option>
                  <option value="0">Library</option>
                  <option value="1">Xme Area</option>
                </select>
                <button @click="isAdd = true" v-if="is_archived == 0 && is_restore == 0"
                  class="desktopFilters h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                  {{ trans("lang.modal.add_new") }}
                </button>
                <inertia-link v-if="is_archived == 0 && is_restore == 0" :href="route('admin.manage-forms.archive')"
                  class="desktopFilters flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">{{
                    trans("lang.modal.archived") }}</inertia-link>
                <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                  class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.archive_selected") }}
                </button>

                <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                  class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.unarchive_selected") }}
                </button>
                <inertia-link v-if="is_archived == 1" :href="route('admin.manage-forms')"
                  class="desktopFilters flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">{{
                    trans("lang.labels.module") }}</inertia-link>
              </div>
            </div>
            <div v-if="mobileFilters" class="mobileFilters mt-4">
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
                  placeholder="Search by title" />
              </div>
              <div class="mt-4 w-full sm:w-fit md:w-fit">
                <span class="text-base text-neutral-400 block w-full sm:w-fit md:w-fit">{{
                  trans("lang.labels.filter_by_status") }}</span>
              </div>
              <div class="mt-2 mobileFilterIndentify">
                <select id="form_status" @change="changeStatus($event)"
                  class="bg-sidebar border border-gray-300 text-neutral-400 text-sm rounded-lg p-2.5 focus:border-amber-500 outline-0"
                  v-model="status">
                  <option>{{ trans("lang.labels.status") }}</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
                <select id="form_type" @change="changeType($event)"
                  class="bg-sidebar border border-gray-300 text-neutral-400 text-sm rounded-lg p-2.5 focus:border-amber-500 outline-0"
                  v-model="type">
                  <option>{{ trans("lang.labels.type") }}</option>
                  <option value="0">Library</option>
                  <option value="1">Xme Area</option>
                </select>
                <button @click="isAdd = true" v-if="is_archived == 0 && is_restore == 0"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                  {{ trans("lang.modal.add_new") }}
                </button>
                <inertia-link v-if="is_archived == 0 && is_restore == 0" :href="route('admin.manage-forms.archive')"
                  class="flex items-center justify-center h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">{{
                    trans("lang.modal.archived") }}</inertia-link>
                <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.archive_selected") }}
                </button>

                <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.unarchive_selected") }}
                </button>
                <inertia-link v-if="is_archived == 1" :href="route('admin.manage-forms')"
                  class="flex items-center justify-center h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">{{
                    trans("lang.labels.module") }}</inertia-link>
              </div>
            </div>
          </div>
          <div class="themeOverflowCustom themeOverflowTable">
            <div v-if="forms.length == 0" class="themeNoFound bg-body">
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
            <table v-else class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableManageForms">
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
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableTfId">
                    {{ trans("lang.labels.tf_id") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableTitle">
                    {{ trans("lang.labels.title") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableType">
                    {{ trans("lang.labels.type") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableStatus">
                    {{ trans("lang.labels.status") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableResponse">
                    {{ trans("lang.labels.response") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableCreatedAt">
                    {{ trans("lang.labels.created_at") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="form in forms" :key="form.id"
                  class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                  <td class="p-4 text-end">
                    <!-- Default dropstart button -->
                    <div class="btn-group dropstart">
                      <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                        data-bs-toggle="dropdown" aria-expanded="false" data-dropdown-trigger="hover">
                        <EllipsisVerticalIcon class="h-8 w-8 text-white" aria-hidden="true" />
                      </button>
                      <div class="dropdown-menu theme-dropdown-menu bg-card rounded-2xl p-0.5">
                        <ul class="py-0">
                          <li class="px-4 py-3 bg-card text-white rounded-t-2xl">
                            {{ trans("lang.labels.options") }}
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <a class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white"
                              @click="editForm(form.id)">
                              <PencilIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.edit_details") }}
                            </a>
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <a class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white"
                              @click="assignForm(form)">
                              <BuildingOfficeIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.assign_company") }}
                            </a>
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <a @click="reloadData()"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <CircleStackIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />

                              {{ trans("lang.modal.reload_data") }}
                            </a>
                          </li>

                          <li class="" v-if="form.status == 'Inactive' && is_archived != 1">
                            <a class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white"
                              @click="updateStatus(form.id)">
                              <RectangleGroupIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.set_active") }}
                            </a>
                          </li>
                          <li v-if="form.status == 'Active' && is_archived != 1">
                            <a class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white"
                              @click="updateStatus(form.id)">
                              <RectangleGroupIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.set_inactive") }}
                            </a>
                          </li>
                          <li v-if="is_archived != 1">
                            <a @click="openArchivedModal(form.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArchiveBoxIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.archived") }}
                            </a>
                          </li>
                          <li v-if="is_archived == 1 && is_restore != 1">
                            <a @click="openUnArcheivedModal(form.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArchiveBoxXMarkIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.unarchived") }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td class="p-4 text-center">
                    <div class="flex items-center justify-center">
                      <input id="checkbox-table-search-1" type="checkbox" :value="form.id" v-model="multipleForms"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                  </td>
                  <td class="p-4 themeImageSmall">
                    <img :src="mediaPath + form.image" alt="form" class="w-24 h-14 rounded-lg object-cover" />
                  </td>
                  <td class="p-4">
                    <label>{{ form.typeform_id }}</label>
                  </td>
                  <td class="p-4 breakWord">
                    <label>{{ form.display_title }}</label>
                  </td>
                  <td class="p-4">
                    <div class="text-xs w-fit" :class="form.moduleClass">
                      {{ form.moduleType }}
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="text-xs w-fit" :class="form.statusClass">
                      {{ form.status }}
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="text-xs w-fit" :class="form.tagClass">
                      {{ form.responsesCount }}
                    </div>
                  </td>
                  <td class="p-4">
                    <span class="">{{ form.created_at }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
        <Pagination v-if="pagination.lastPage != 1" @refreshTable="FormTable" :currentPage="pagination.currentPage"
          :lastPage="pagination.lastPage" :total="pagination.total" />
      </div>
      <add @closeAdd="isCloseAdd()" v-if="isAdd"></add>
      <edit @closeEdit="isCloseEdit()" v-if="isEdit" :id="editId"></edit>
      <assign @closeAssign="isCloseAssign()" v-if="isAssign" :item="item"></assign>
      <delete ref="deletefunc" :data="modalData" @closeArchive="isCloseArchive()"
        @deleteSubmitAction="submitDeleteModelAction()" v-if="isDelete"></delete>
      <multiple ref="deletefunc" v-if="isMultipleModal" :data="modalData" @cancelMultipleArchive="cancelMultiple()"
        @multipleDelAction="submitMultipleDeleteAction()"></multiple>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Add from "@/Components/Module/Add.vue";
import Edit from "@/Components/Module/Edit.vue";
import Assign from "@/Components/Module/Assign.vue";
import Delete from "@/Components/Module/Delete.vue";
import Multiple from "@/Components/Module/MultipleArchive.vue";
import Pagination from "@/Components/Pagination.vue";

import { mapStores } from "pinia";
import { useAppStore } from "@/store";
import {
  EllipsisVerticalIcon,
  RectangleGroupIcon,
  PencilIcon,
  ArchiveBoxIcon,
  TrashIcon,
  ArrowPathIcon,
  ArchiveBoxArrowDownIcon,
  ArchiveBoxXMarkIcon,
  ArrowUpOnSquareStackIcon,
  BuildingOfficeIcon,
  CircleStackIcon,
  FunnelIcon
} from "@heroicons/vue/24/solid";
export default {
  components: {
    Add,
    Assign,
    Delete,
    Multiple,
    AuthenticatedLayout,

    Pagination,
    Edit,
    EllipsisVerticalIcon,
    RectangleGroupIcon,
    PencilIcon,
    ArchiveBoxIcon,
    ArrowPathIcon,
    TrashIcon,
    ArchiveBoxArrowDownIcon,
    ArchiveBoxXMarkIcon,
    ArrowUpOnSquareStackIcon,
    BuildingOfficeIcon,
    CircleStackIcon,
    FunnelIcon
  },
  props: ["is_archived", "is_restore"],
  data() {
    return {
      mobileFilters: false,
      forms: [],
      searchCompany: "",
      search: "",
      type: "Type",
      status: "Status",
      isAdd: false,
      isEdit: false,
      isAssign: false,
      isDelete: false,
      isMultipleModal: false,
      searchTimeout: null,
      editId: "",
      item: "",
      multipleForms: [],
      queryData: {
        search: "",
        isDeleted: this.is_restore,
        is_deleted: this.is_restore,
        page: 1,
        isArchived: this.is_archived,
        status: "",
        type: "",
        sortField: "forms.id",
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
    };
  },
  watch: {
    search: function (value) {
      let that = this;
      clearTimeout(that.searchTimeout);
      that.searchTimeout = setTimeout(function () {
        that.queryData.search = that.search;
        that.FormTable();
      }, 700);
    },
    isAdd: function () {
      document.body.style.overflow = this.isAdd ? "hidden" : "";
    },
    isEdit: function () {
      document.body.style.overflow = this.isEdit ? "hidden" : "";
    },
    isAssign: function () {
      document.body.style.overflow = this.isAssign ? "hidden" : "";
    },
    isDelete: function () {
      document.body.style.overflow = this.isDelete ? "hidden" : "";
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
    editForm(id) {
      this.isEdit = true;
      this.editId = id;
    },
    submitDeleteModelAction() {
      let that = this;
      let formData = new FormData();
      formData.append("form_id", that.modalData.editId);
      formData.append("actionType", that.modalData.actionType);
      axios
        .post("/api/form-action", formData, {
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
            that.FormTable(1);
            that.$refs.deletefunc.processingFunc();
            that.isDelete = false;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;

          that.isDelete = false;
        })
        .finally(() => {
          //Perform action in always
        });
    },
    submitMultipleDeleteAction() {
      let that = this;
      let formData = new FormData();
      formData.append("actionType", that.modalData.actionType);
      formData.append("multipleForms", that.multipleForms);
      axios
        .post("/api/multiple-form-action", formData, {
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
            that.FormTable(1);
            that.$refs.deletefunc.processingFunc();
            that.isMultipleModal = false;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;

          that.isMultipleModal = false;
        })
        .finally(() => {
          //Perform action in always
        });
    },

    FormTable(page) {
      this.queryData.page = page;
      //for table data is loading after fetch ==>
      axios
        .get("/api/forms", { params: this.queryData })
        .then((response) => {
          this.forms = response.data.data;
          this.setPagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    isCloseEdit() {
      this.isEdit = false;
      this.FormTable(1);
    },
    isCloseAdd() {
      this.isAdd = false;
      this.FormTable(1);
    },
    openArchivedModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_module"
      );
      this.modalData.editId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
    },
    openUnArcheivedModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_module"
      );
      this.modalData.editId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },
    openSoftDeleteModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_delete_this_module"
      );
      this.modalData.editId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_delete_it");
      this.modalData.actionType = "Delete";
    },
    openRestoreModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_restore_this_module"
      );
      this.modalData.editId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_restore_it");
      this.modalData.actionType = "Restore";
    },
    multipleArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_module"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
    },
    multipleUnArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_module"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },

    isCloseAssign() {
      this.isAssign = false;
    },
    isCloseArchive() {
      this.isDelete = false;
    },
    cancelMultiple() {
      this.isMultipleModal = false;
    },

    updateStatus(id) {
      let that = this;
      axios
        .put("/api/update-form-status/" + id)
        .then((res) => {
          if (res.status == 200) {
            that.FormTable(1);
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);
          }
        })
        .catch((error) => {
          // error.response.status Check status code
        })
        .finally(() => {
          //Perform action in always
        });
    },
    changeStatus(event) {
      let that = this;
      that.queryData.status = event.target.value;
      this.FormTable(1);
    },
    changeType(event) {
      let that = this;
      that.queryData.type = event.target.value;
      this.FormTable(1);
    },
    assignForm(form) {
      this.isAssign = true;
      this.item = form;
    },
    reloadData() {
      this.FormTable(1);
    },
  },
  created: function () {
    this.FormTable(1);
  },
  unmounted() {
    clearTimeout(this.searchTimeout);
  },
  computed: {
    ...mapStores(useAppStore),
  },
};
</script>
