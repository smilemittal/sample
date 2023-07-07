<template>
  <div>
    <InertiaHead title="Email Settings" />
    <AuthenticatedLayout>
      <div class="mb-4">
        <div class="page-header">
          <h4 class="text-3xl text-neutral-300 font-bold">Activity Logs</h4>
        </div>
      </div>
      <div>
        <div class="drop-shadow-lg h-full drop-shadow-md rounded-3xl mt-5">
          <div class="relative shadow-md sm:rounded-lg mt-5">
            <div
              class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200"
            >
              <h4 class="text-xl text-neutral-300 font-semibold">
                Activity Logs
              </h4>
            </div>
          </div>
          <div class="themeOverflowTable">
            <div
              class="flex p-3 bg-cardtop"
              v-for="activityLog in activityLogs"
              :key="activityLog"
            >
              <div>
                <StopCircleIcon
                  class="h-8 w-8 text-amber-500"
                  aria-hidden="true"
                />
              </div>
              <div class="ml-4">
                <h5 class="text-gray-400 text-lg">
                  {{ activityLog.subject }}
                </h5>
                <h5 class="text-gray-600 text-sm">
                  {{ activityLog.createdAt }}
                </h5>
              </div>
            </div>
          </div>
        </div>
        <Pagination
          v-if="pagination.lastPage != 1"
          @refreshTable="createTable"
          :currentPage="pagination.currentPage"
          :lastPage="pagination.lastPage"
          :total="pagination.total"
        />
      </div>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import { StopCircleIcon } from "@heroicons/vue/24/solid";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import Pagination from "@/Components/Pagination.vue";
export default {
  name: "ActivityLog",
  components: {
    AuthenticatedLayout,
    
    Pagination,
    StopCircleIcon,
  },
  data() {
    return {
      updateEmailForm: {
        name: "",
        phone_nr: "",
        logo: "",
        status: "",
      },
      form: {},
      activityLogs: [],
      queryData: {
        page: 1,
      },
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
      },
    };
  },
  methods: {
    setPagination(response) {
      this.pagination.total = response.data.meta.total;
      this.pagination.lastPage = response.data.meta.last_page;
      this.pagination.currentPage = response.data.meta.current_page;
    },
    createTable(page) {
      //for table data is loading after fetch ==>
      this.queryData.page = page;

      axios
        .get(route("app.activity-logs"), {
          params: this.queryData,
        })
        .then((response) => {
          this.activityLogs = response.data.data;
          this.setPagination(response);
        })
        .catch((error) => {})
        .finally(() => {});
    },
  },
  created() {
    this.createTable(1);
  },
};
</script>
