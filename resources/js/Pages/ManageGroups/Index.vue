<template>
  <div>
    <InertiaHead title="Groups" />
    <AuthenticatedLayout>
      <div class="mt-2">
        <inertia-link :href="route('admin.manage-groups')" v-if="is_archived ==0"
          class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{ trans("lang.labels.manage_groups")
          }}</inertia-link>
        <inertia-link :href="route('admin.manage-groups')" v-if="is_archived !=0"
          class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{ trans("lang.labels.group_archived_heading")
          }}</inertia-link>
        <h5 class="text-base text-neutral-400" v-if="is_archived ==0">
          {{ trans("lang.labels.add_new_groups_or_manage_existing") }}
        </h5>
        <h5 class="text-base text-neutral-400" v-if="is_archived !=0">
          {{ trans("lang.labels.group_archived_sub_heading") }}
        </h5>
      </div>
      <div class="h-full drop-shadow-md mt-5">
        <div class="relative shadow-md sm:rounded-lg mt-5">
          <div class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <h4 class="text-xl text-neutral-300 font-semibold">{{ trans("lang.labels.groups") }}</h4>
            <div class="filterBtn">
              <button type="button" @click="openFilter()"
                class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
              </button>
            </div>
            <div class="flex justify-start items-center flex-wrap gap-3">
              <div class="relative desktopFilters">
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
                  placeholder="Search by group name" />
              </div>
              <button @click="isAdd = true" v-if="is_archived == 0 && is_restore == 0"
                class="desktopFilters h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                {{ trans("lang.modal.add_new") }}
              </button>
              <inertia-link v-if="is_archived == 0 && is_restore == 0"
                class="desktopFilters h-10 text-sm w-32 flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                :href="route('admin.manage-groups.archive')">{{ trans("lang.modal.archived") }}</inertia-link>
              <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                {{ trans("lang.modal.archive_selected") }}
              </button>
              <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                {{ trans("lang.modal.unarchive_selected") }}
              </button>
              <inertia-link v-if="is_archived == 1"
                class="desktopFilters h-10 text-sm w-32 flex items-center justify-center text-neutral-400 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                :href="route('admin.manage-groups')">{{ trans("lang.labels.manage_groups") }}</inertia-link>
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
                <button @click="isAdd = true" v-if="is_archived == 0 && is_restore == 0"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                  {{ trans("lang.modal.add_new") }}
                </button>
                <inertia-link v-if="is_archived == 0 && is_restore == 0"
                  class="h-10 text-sm flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                  :href="route('admin.manage-groups.archive')">{{ trans("lang.modal.archived") }}</inertia-link>
                <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.archive_selected") }}
                </button>
                <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.unarchive_selected") }}
                </button>
                <inertia-link v-if="is_archived == 1"
                  class="h-10 text-sm flex items-center justify-center text-neutral-400 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                  :href="route('admin.manage-groups')">{{ trans("lang.labels.manage_groups") }}</inertia-link>
              </div>
            </div>
          </div>
          <div class="themeOverflowCustom themeOverflowTable">
            <table
              class="groupsDesktop theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableGroups">
              <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                <tr>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                  <th scope="col" class="p-4 tableMultiActions">
                    <div class="flex items-center">
                      <input id="checkbox-all-search" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableSubject">
                    {{ trans("lang.labels.name") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.members") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.module") }}
                  </th>
                </tr>
              </thead>
              <tr v-if="groups.length == 0" class="themeNoFound">
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
              <draggable :list="groups" tag="tbody" item-key="name" ghost-class="ghost" @start="dragging = true"
                @end="updateGroupOrder">
                <template #item="{ element: item }">
                  <tr :data-id="item.id"
                    class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white class">
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
                            <li class="" v-if="item.deleted_at == null && is_archived == 0">
                              <inertia-link :href="route('edit-group', item.encryptId)"
                                class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                                <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                                {{ trans("lang.labels.edit_group") }}
                              </inertia-link>
                            </li>
                            <li class="" v-if="is_archived != 1">
                              <a @click="openArchivedModal(item.id)"
                                class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                                <ArrowPathIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                                {{ trans("lang.modal.archived") }}
                              </a>
                            </li>
                            <li class="" v-if="is_archived == 1 && is_restore != 1">
                              <a @click="openUnArcheivedModal(item.id)"
                                class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                                <ArrowPathIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                                {{ trans("lang.modal.unarchived") }}
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td class="p-4">
                      <div class="flex items-center">
                        <input id="checkbox-table-search-1" type="checkbox" :value="item.id" v-model="multipleGroup"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                        <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                      </div>
                    </td>
                    <td class="p-4 breakWord">
                      <span class="themeManageTitle">{{ item.name }}</span>
                    </td>
                    <td class="p-4">
                      <span class="bg-btnCancelBg text-btnCancelText text-xs w-10 h-10 rounded-full px-2 py-1"
                        v-if="item.members == 0">{{ item.members }}</span>
                      <span v-else class="bg-btnSubmitBg text-btnSubmitText text-xs w-10 h-10 rounded-full px-2 py-1">{{
                        item.members }}</span>
                    </td>
                    <td class="p-4">
                      <span class="bg-btnCancelBg text-btnCancelText text-xs w-10 h-10 rounded-full px-2 py-1"
                        v-if="item.forms == 0">{{ item.forms }}</span>
                      <span v-else class="bg-bgAmberTag text-amber-500 text-xs w-10 h-10 rounded-full px-2 py-1">{{
                        item.forms }}</span>
                    </td>
                  </tr>
                </template>
              </draggable>
            </table>
            <!----------------Disabled drag drop on mobile table--------------------------->
            <table
              class="groupsMobile theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableGroups">
              <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                <tr>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                  <th scope="col" class="p-4 tableMultiActions">
                    <div class="flex items-center">
                      <input id="checkbox-all-search" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableSubject">
                    {{ trans("lang.labels.name") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.members") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.module") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.business") }}
                  </th>
                </tr>
              </thead>
              <tr v-if="groups.length == 0" class="themeNoFound">
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
              <tbody>
                <tr v-for="item in groups" :key="item"
                  class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white class">
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
                          <li class="" v-if="item.deleted_at == null && is_archived == 0">
                            <inertia-link :href="route('edit-group', item.encryptId)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.edit_group") }}
                            </inertia-link>
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <a @click="openArchivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArrowPathIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.modal.archived") }}
                            </a>
                          </li>
                          <li class="" v-if="is_archived == 1 && is_restore != 1">
                            <a @click="openUnArcheivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArrowPathIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.modal.unarchived") }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center">
                      <input id="checkbox-table-search-1" type="checkbox" :value="item.id" v-model="multipleGroup"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                  </td>
                  <td class="p-4 breakWord">
                    <span class="themeManageTitle">{{ item.name }}</span>
                  </td>
                  <td class="p-4">
                    <span class="bg-btnCancelBg text-btnCancelText text-xs w-10 h-10 rounded-full px-2 py-1"
                      v-if="item.members == 0">{{ item.members }}</span>
                    <span v-else class="bg-btnSubmitBg text-btnSubmitText text-xs w-10 h-10 rounded-full px-2 py-1">{{
                      item.members }}</span>
                  </td>
                  <td class="p-4">
                    <span class="bg-btnCancelBg text-btnCancelText text-xs w-10 h-10 rounded-full px-2 py-1"
                      v-if="item.forms == 0">{{ item.forms }}</span>
                    <span v-else class="bg-bgAmberTag text-amber-500 text-xs w-10 h-10 rounded-full px-2 py-1">{{
                      item.forms }}</span>
                  </td>
                  <td class="p-4">
                    <span>{{ item.companyName }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="bg-sidebar px-4 py-5 rounded-b-3xl mb-6">
        <Pagination v-if="pagination.lastPage != 1" @refreshTable="groupTable" :currentPage="pagination.currentPage"
          :lastPage="pagination.lastPage" :total="pagination.total" />
      </div>
      <add @closeAdd="isCloseAdd()" v-if="isAdd"></add>
      <Delete ref="deletefunc" :data="modalData" @deleteSubmitAction="submitDeleteModelAction()"
        @closeDelete="isCloseDelete()" v-if="isDelete"></Delete>
      <multiple ref="deletefunc" v-if="isMultipleModal" :data="modalData" @cancelMultipleArchive="cancelMultiple()"
        @multipleDelAction="submitMultipleDeleteAction()">
      </multiple>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import draggable from "vuedraggable";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Add from "@/Components/ManageGroups/Add.vue";

import Pagination from "@/Components/Pagination.vue";
import Multiple from "@/Components/ManageGroups/MultipleArchive.vue";
import Delete from "@/Components/ManageGroups/Delete.vue";
import { mapStores } from "pinia";
import { useAppStore } from "@/store";
import {
  BarsArrowUpIcon,
  ClipboardDocumentCheckIcon,
  EllipsisVerticalIcon,
  ArrowPathIcon,
  ArchiveBoxArrowDownIcon,
  TrashIcon,
  ArrowPathRoundedSquareIcon,
  PencilIcon,
  ArrowRightOnRectangleIcon, FunnelIcon
} from "@heroicons/vue/24/solid";
export default {
  components: {
    Add,
    AuthenticatedLayout,

    Pagination,
    Multiple,
    Delete,
    BarsArrowUpIcon,
    ClipboardDocumentCheckIcon,
    EllipsisVerticalIcon,
    ArrowPathIcon,
    ArchiveBoxArrowDownIcon,
    TrashIcon,
    ArrowPathRoundedSquareIcon,
    PencilIcon,
    ArrowRightOnRectangleIcon, FunnelIcon,
    draggable,
  },
  props: ["is_archived", "is_restore"],
  data() {
    return {
      mobileFilters: false,
      drag: false,
      groups: [],
      pendingInvites: [],
      searchTimeout: null,
      search: "",
      is_deleted: 0,
      isMultipleModal: false,
      multipleGroup: [],
      modalData: {
        memberId: "",
        modalText: "",
        buttonText: "",
        actionType: "",
      },
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
      },
      queryData: {
        search: "",
        isArchived: this.is_archived,
        page: 1,
        sortField: "display_order",
        orderDir: "ASC",
        isDeleted: this.is_restore,
      },
      company: {},
      isAdd: false,
      isDelete: false,
    };
  },
  watch: {
    search: function (value) {
      let self = this;
      clearTimeout(self.searchTimeout);
      self.searchTimeout = setTimeout(function () {
        self.queryData.search = self.search;
        self.groupTable();
      }, 700);
    },
    isAdd: function () {
      document.body.style.overflow = this.isAdd ? "hidden" : "";
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
    submitDeleteModelAction() {
      let that = this;
      let formData = new FormData();
      formData.append("group_id", that.modalData.memberId);
      formData.append("actionType", that.modalData.actionType);
      axios
        .post("/api/group-action", formData, {
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
            that.groupTable(1);
            that.$refs.deletefunc.processingFunc();
            that.isDelete = false;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;
          console.log(that.errors);
        })
        .finally(() => {
          //Perform action in always
        });
    },
    submitMultipleDeleteAction() {
      let that = this;
      let formData = new FormData();
      formData.append("actionType", that.modalData.actionType);
      formData.append("multipleGroup", that.multipleGroup);
      axios
        .post("/api/multiple-group-action", formData, {
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
            that.groupTable(1);
            that.$refs.deletefunc.processingFunc();
            that.isMultipleModal = false;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;
          console.log(that.errors);
        })
        .finally(() => {
          //Perform action in always
        });
    },
    setPagination(response) {
      this.pagination.total = response.data.meta.total;
      this.pagination.lastPage = response.data.meta.last_page;
      this.pagination.currentPage = response.data.meta.current_page;
    },
    groupTable(page) {
      this.queryData.page = page;
      //for table data is loading after fetch ==>
      axios
        .get("/api/groups", { params: this.queryData })
        .then((response) => {
          this.groups = response.data.data;
          console.log(this.groups);
          this.setPagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    openArchivedModal: function (id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_group"
      );
      this.modalData.memberId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
    },
    openUnArcheivedModal: function (id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_group"
      );
      this.modalData.memberId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },
    openSoftDeleteModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_delete_this_group"
      );
      this.modalData.memberId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_delete_it");
      this.modalData.actionType = "softDelete";
    },
    openRestoreModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_restore_this_group"
      );
      this.modalData.memberId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_restore_it");
      this.modalData.actionType = "Restore";
    },
    multipleArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.title = this.trans("lang.labels.manage_groups");
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_groups"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
    },
    multipleUnArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_groups"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },
    isCloseAdd() {
      this.isAdd = false;
      this.groupTable(1);
    },
    isCloseDelete() {
      this.isDelete = false;
    },
    edit(id) {
      let that = this;
      that.isEdit = true;
      that.modalData.memberId = id;
    },
    isCloseEdit() {
      this.isEdit = false;
    },
    cancelMultiple() {
      this.isMultipleModal = false;
    },
    assignModule(id) {
      let that = this;
      that.isAssign = true;
      that.modalData.memberId = id;
    },
    isCloseAssign() {
      this.isAssign = false;
    },
    currentCompany() {
      let that = this;
      axios
        .get("/api/companies/" + this.$page.props.auth.user.company_id)
        .then((res) => {
          that.company = res.data.data;
        })
        .catch((error) => { })
        .finally(() => {
          //Perform action in always
        });
    },
    updateGroupOrder: function () {
      let that = this;
      let updatedData = [];
      that.groups.map((group) => {
        updatedData.push(group.id);
      });
      axios
        .post("/api/update-group-order-action", updatedData)
        .then((response) => {
          if (response.status == 200) {
            that.groupTable(1);
            let notification = {
              heading: "success",
              subHeading: response.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);
          }
        });
    },
  },
  created: function () {
    this.groupTable(1);
  },
  unmounted() {
    clearTimeout(this.searchTimeout);
  },
  computed: {
    ...mapStores(useAppStore),
  },
};
</script>
