<template>
  <div>

    <InertiaHead title="Email Template" />
    <AuthenticatedLayout>
      <div class="mb-4">
        <div class="page-header">
          <h4 class="text-3xl text-neutral-300 font-bold">{{ trans("lang.labels.email_template") }}</h4>
        </div>
      </div>
      <div class="h-full mt-5">
        <div class="relative shadow-md sm:rounded-lg mt-5">
          <div
            class="flex justify-between items-center flex-wrap gap-3 p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <div class="flex items-center justify-start">
              <div class="mr-2">
                <span class="text-base text-neutral-400">{{ trans("lang.labels.show") }} {{ pagination.perpage }} {{
                  trans("lang.labels.entries") }}:</span>
              </div>
              <select id="form_status" @change="pageChange($event)"
                class="bg-sidebar border border-gray-300 text-neutral-400 text-sm rounded-lg p-2.5 ml-2"
                v-model="pagevalue">
                <option selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
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
              <input type="text" id="simple-search" v-model="search" class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 
                                                    focus:border-amber-500 block w-full pl-10 p-2.5"
                placeholder="Search">
            </div>
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
          <table v-else class="theme-table w-full text-sm text-left themeTableTemplates">
            <thead class="text-xs text-gray-400 uppercase bg-sidebar">
              <tr>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                  {{ trans("lang.labels.subject") }}
                </th>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                  {{ trans("lang.labels.status") }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in items" :key="item.id"
                class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                <td class="p-4 text-end">
                  <!-- Default dropstart button -->
                  <div class="btn-group dropstart">
                    <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn" data-bs-toggle="dropdown"
                      aria-expanded="false" data-dropdown-trigger="hover">
                      <EllipsisVerticalIcon class="h-8 w-8 text-white" aria-hidden="true" />
                    </button>
                    <div class="dropdown-menu theme-dropdown-menu bg-card rounded-2xl py-0 p-0.5">
                      <ul class="py-0">
                        <li class="px-4 py-3 bg-card text-white rounded-t-2xl">
                          {{ trans("lang.labels.options") }}
                        </li>
                        <li class="">
                          <inertia-link :href="route('email-setting.edit', item.encryptId)"
                            class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                            <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" /> {{
                              trans("lang.modal.edit_details") }}
                          </inertia-link>
                        </li>
                      </ul>
                    </div>
                  </div>
                </td>
                <td class="p-4 breakWord">
                  <span>{{ item.subject }}</span>
                </td>
                <td class="p-4">
                  <span class="bg-btnSubmitBg text-xs text-btnSubmitText py-1 px-2.5 rounded-full">
                    {{ item.enable ? 'Active' : 'Disable' }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
          <Pagination v-if="pagination.lastPage != 1" @refreshTable="templates" :currentPage="pagination.currentPage"
            :lastPage="pagination.lastPage" :total="pagination.total" :firstPage="pagination.first_page" />
        </div>
      </div>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TestMail from "@/Components/Design/EmailSettings/TestMail.vue";
import Pagination from "@/Components/Pagination.vue";
import {
  EllipsisVerticalIcon, RectangleGroupIcon, PencilIcon,
  ArchiveBoxIcon, TrashIcon, ArrowPathIcon, ArchiveBoxArrowDownIcon
} from '@heroicons/vue/24/solid'
export default {
  name: "EmailTemplates",
  components: {
    TestMail,
    AuthenticatedLayout,
    Pagination,
    EllipsisVerticalIcon, RectangleGroupIcon, PencilIcon,
    ArchiveBoxIcon, TrashIcon, ArrowPathIcon, ArchiveBoxArrowDownIcon
  },
  data() {
    return {
      items: [],
      searchTimeout: null,
      search: '',
      queryData: {
        search: '',
        sortField: "id",
        orderDir: "DESC",
        page: 1,
        perpage: 10
      },
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
        first_page: ''
      },
      pagevalue: '10'
    };
  },
  watch: {
    search: function (value) {
      let self = this;
      clearTimeout(self.searchTimeout);
      self.searchTimeout = setTimeout(function () {
        self.queryData.search = value;
        self.templates();
      }, 700);
    },
  },
  methods: {
    setPagination(response) {
      this.pagination.total = response.data.meta.total;
      this.pagination.lastPage = response.data.meta.last_page;
      this.pagination.currentPage = response.data.meta.current_page;
      this.pagination.first_page = response.data.links.first;
    },
    templates(page) {
      this.queryData.page = page;
      axios
        .get("/api/email-templates", { params: this.queryData })
        .then((res) => {
          if (res.status == 200) {
            this.items = res.data.data;
            this.setPagination(res);
          }
        })
        .catch((error) => {
          // error.response.status Check status code
        })
        .finally(() => {
          //Perform action in always
        });
    },
    pageChange(event) {
      this.queryData.perpage = event.target.value;
      this.templates(1);
    }
  },
  created: function () {
    this.templates(1);
  },
  unmounted() {
    clearTimeout(this.searchTimeout);
  },
};
</script>
