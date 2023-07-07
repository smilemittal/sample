<template>
  <div>

    <InertiaHead title="Subscription Plans" />
    <AuthenticatedLayout>
      <div class="mb-4">
        <div class="page-header">
          <h4 class="text-3xl text-neutral-300 font-bold">
            {{ trans("lang.labels.subscription_plans") }}
          </h4>
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
            <div class="relative flex flex-wrap gap-3">
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
              <a :href="route('admin.plans.create')"
                class="flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                {{ trans("lang.modal.add_new") }}
              </a>
            </div>
          </div>
        </div>
        <div class="themeOverflowCustom themeOverflowTable">
          <div v-if="plansData.length == 0" class="themeNoFound bg-body">
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
          <table v-else class="theme-table w-full text-sm text-left themeTablePlans">
            <thead class="text-xs text-gray-400 uppercase bg-sidebar">
              <tr>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4 text-center tableMultiActions">
                  {{ trans("lang.labels.id") }}
                </th>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMemberName">
                  {{ trans("lang.labels.plan_name") }}
                </th>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMemberName">
                  {{ trans("lang.labels.valid_from") }}
                </th>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMemberName">
                  {{ trans("lang.labels.price") }}
                </th>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMemberName">
                  {{ trans("lang.labels.plan_type") }}
                </th>
                <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                  {{ trans("lang.labels.status") }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(plan, index) in plansData" :key="plan.id"
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
                          <inertia-link :href="route('app.plan-edit', plan.encryptId)"
                            class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                            <EyeIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                            {{ trans("lang.labels.view") }}
                          </inertia-link>
                        </li>

                        <li class="">
                          <inertia-link @click="openSoftDeleteModal(plan.id)"
                            class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                            <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                            {{ trans("lang.modal.archive") }}
                          </inertia-link>
                        </li>
                      </ul>
                    </div>
                  </div>
                </td>
                <td class="p-4 text-center">
                  <span>{{ (pagination.currentPage * queryData.perpage) - queryData.perpage + index + 1 }}</span>
                </td>
                <td class="p-4">
                  <span>{{ plan.name }}</span>
                </td>
                <td class="p-4">
                  <span class="bg-btnSubmitBg text-xs text-btnSubmitText py-1 px-2.5 rounded-full">
                    {{ plan.valid_from }}</span>
                </td>

                <td class="p-4">
                  <span class="text-sm rounded-full">
                    ${{ plan.price }}</span>
                </td>

                <td class="p-4">
                  <span class="text-sm rounded-full">
                    {{ plan.plan_type }}</span>
                </td>

                <td class="p-4">
                  <span class="bg-btnSubmitBg text-xs text-btnSubmitText py-1 px-2.5 rounded-full">
                    {{ plan.status }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
          <Pagination v-if="pagination.lastPage != 1" @refreshTable="plans" :currentPage="pagination.currentPage"
            :lastPage="pagination.lastPage" :total="pagination.total" />
        </div>
      </div>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";

import {
  EllipsisVerticalIcon,
  RectangleGroupIcon,
  PencilIcon,
  ArchiveBoxIcon,
  EyeIcon,
  TrashIcon,
  ArrowPathIcon,
  ArchiveBoxArrowDownIcon,
} from "@heroicons/vue/24/solid";
export default {
  name: "Subscription Plans",
  components: {
    AuthenticatedLayout,
    Pagination,
    EllipsisVerticalIcon,
    RectangleGroupIcon,
    PencilIcon,
    ArchiveBoxIcon,
    TrashIcon,
    ArrowPathIcon,
    ArchiveBoxArrowDownIcon,
    EyeIcon
  },
  data() {
    return {
      plansData: [],
      searchTimeout: null,
      search: "",
      queryData: {
        search: "",
        sortField: "id",
        orderDir: "DESC",
        page: 1,
        perpage: 1,
      },
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
        perpage: 10,
      },
      pagevalue: "10",

      modalData: {
        id: "",
        is_reason: false,
        buttonHeading: "",
        actionType: "",
        plan: "",
      },
    };
  },
  watch: {
    search: function (value) {
      let self = this;
      clearTimeout(self.searchTimeout);
      self.searchTimeout = setTimeout(function () {
        self.queryData.search = value;
        self.plans();
      }, 700);
    },
  },
  methods: {
    setPagination(response) {
      this.pagination.total = response.data.meta.total;
      this.pagination.lastPage = response.data.meta.last_page;
      this.pagination.currentPage = response.data.meta.current_page;
    },
    plans(page) {
      this.queryData.page = page;
      axios
        .get("/api/plans", { params: this.queryData })
        .then((res) => {
          if (res.status == 200) {
            this.plansData = res.data.data;
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
    editData() {
      axios
        .get(route("plans.show", this.id))
        .then((res) => {
          this.form = res.data.data;
        })
        .catch((error) => {
          // error.response.status Check status code
        })
        .finally(() => {
          //Perform action in always
        });
    },

    openSoftDeleteModal(id) {
      this.isDelete = true;
      this.modalData.modalText = "Are you sure you want to delete this Plan?";
      this.modalData.id = id;
      this.modalData.buttonText = "Yes delete it";
      this.modalData.actionType = "softDelete";
    },
  },
  created: function () {
    this.plans(1);
  },
  unmounted() {
    clearTimeout(this.searchTimeout);
  },
};
</script>
