<template>
  <div>

    <Head title="Notifications" />
    <AuthenticatedLayout>
      <div class="mt-2">
        <h4 class="text-4xl text-neutral-300 font-bold">{{ trans("lang.labels.notifications") }}</h4>
      </div>
      <div class="themeNotificationAction relative shadow-md sm:rounded-lg mt-5">
        <div class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
          <h4 class="text-xl text-neutral-300 font-semibold">{{ trans("lang.labels.notifications") }}</h4>
          <div class="flex justify-between items-center flex-wrap">
            <button @click="opencleanNotification()"
              class="themeNotifyBtn relative flex items-center justify-center h-10 text-sm bg-amber-500 text-neutral-400 p-2 rounded-lg hover:bg-amber-500 hover:text-white">
              <TrashIcon class="h-6 w-6 text-white" aria-hidden="true" />
              <span class="themeNotifyToolTip absolute -top-11 -right-4 bg-body rounded-sm text-xs px-2 py-2">{{ trans("lang.modal.clear_all") }}
                </span>
            </button>
            <button @click="openMarkedAllNotification()"
              class="themeNotifyBtn relative flex items-center justify-center h-10 text-sm bg-amber-500 text-neutral-400 p-2 rounded-lg hover:bg-amber-500 hover:text-white ml-2">
              <EyeIcon class="h-6 w-6 text-white" aria-hidden="true" />
              <span class="themeNotifyToolTip absolute -top-11 -right-4 bg-body rounded-sm text-xs px-2 py-2">{{ trans("lang.modal.marked_all") }}
                </span>
            </button>
            <button @click="openUnMarkedAllNotification()"
              class="themeNotifyBtn relative flex items-center justify-center h-10 text-sm bg-amber-500 text-neutral-400 p-2 rounded-lg hover:bg-amber-500 hover:text-white ml-2">
              <EyeSlashIcon class="h-6 w-6 text-white" aria-hidden="true" />
              <span class="themeNotifyToolTip absolute -top-11 -right-4 bg-body rounded-sm text-xs px-2 py-2">{{ trans("lang.modal.unmarked_all") }}
                </span>
            </button>
          </div>
        </div>
        <div class="themeNotifyOverflow">
          <div v-if="notifications.length == 0" class="themeNoFound">
              <div class="px-4 py-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="m-auto mb-3 h-8 w-8" viewBox="0 0 576 512">
                  <path
                    d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm59.3 107.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L432 345.4l-36.7-36.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L409.4 368l-36.7 36.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L432 390.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L454.6 368l36.7-36.7z" />
                </svg>
                <p class="text-gray-400 text-base noFound">{{ trans("lang.modal.no_result_found") }}</p>
              </div>
            </div>
          <div class="flex justify-between items-center pr-4 themeNotificationBox" v-for="notification in notifications"
            :key="notification" :class="{ 'bg-neutral-800': notification.read_at != null }">
            <inertia-link :href="getNotificationLink(notification, userRole)" class="w-full">
              <component :is="notification.component" :notification="notification" :user="user" :data="notification.data">
              </component>
            </inertia-link>
            <div class="flex items-end">
              <div class="ml-5 flex items-center">
                <button @click="openDeleteNotification(notification.id)"
                  class="themeNotifyBtn relative w-9 h-9 rounded-lg flex items-center justify-center bg-amber-500 hover:text-white">
                  <TrashIcon class="h-5 w-5 text-white" aria-hidden="true" />
                  <span class="themeNotifyToolTip absolute -top-7 -right-4 bg-sidebar rounded-sm text-xs px-1 py-1">{{ trans("lang.modal.delete_notification") }}</span>
                </button>
                <button v-if="notification.read_at == null" @click="openMarkedNotification(notification.id)"
                  class="themeNotifyBtn relative w-9 h-9 rounded-lg flex items-center justify-center bg-amber-500 ml-3 hover:text-white">
                  <EyeIcon class="h-5 w-5 text-white" aria-hidden="true" />
                  <span class="themeNotifyToolTip absolute -top-7 -right-4 bg-sidebar rounded-sm text-xs px-1 py-1">{{ trans("lang.modal.marked_notification") }}</span>
                </button>
                <button v-if="notification.read_at != null" @click="openUnMarkedNotification(notification.id)"
                  class="themeNotifyBtn relative w-9 h-9 rounded-lg flex items-center justify-center bg-amber-500 ml-3 hover:text-white">
                  <EyeSlashIcon class="h-6 w-6 text-white" aria-hidden="true" />
                  <span
                    class="themeNotifyToolTip absolute -top-7 -right-4 bg-sidebar rounded-sm text-xs px-1 py-1">{{ trans("lang.modal.unmarked_notification") }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
        <Pagination v-if="pagination.lastPage != 1" @refreshTable="notificationTable"
          :currentPage="pagination.currentPage" :lastPage="pagination.lastPage" :total="pagination.total" />
      </div>
      <delete-notification :data="modalData" @closeDeleteNotification="isCloseDelete()"
        @deleteSubmitNotification="submitAction()" v-if="isDelete"></delete-notification>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import GeneralMixin from "@/Mixins/GeneralMixin";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { mapStores,mapState } from "pinia";
import { useAppStore } from '@/store'
import DeleteNotification from "@/Components/Notification/DeleteNotification.vue";
import {
  TrashIcon,
  DocumentArrowUpIcon,
  EyeIcon,
  EyeSlashIcon,
} from "@heroicons/vue/24/solid";
import AssestAddedToGroup from "@/Components/Notification/AssestAddedToGroup.vue";
import AddedGroup from "@/Components/Notification/AddedGroup.vue";
import ArchiveFormLibrary from "@/Components/Notification/ArchiveFormLibrary.vue";
import UnarchiveFormLibrary from "@/Components/Notification/UnarchiveFormLibrary.vue";
import DeleteMember from "@/Components/Notification/DeleteMember.vue";
import DeleteSubject from "@/Components/Notification/DeleteSubject.vue";
import SubjectStatus from "@/Components/Notification/SubjectStatus.vue";
import UserTrainingComplete from "@/Components/Notification/UserTrainingComplete.vue";
import AdminArchive from "@/Components/Notification/AdminArchive.vue";
import AdminUnarchive from "@/Components/Notification/AdminUnarchive.vue";
import FinaliseSubmit from "@/Components/Notification/FinaliseSubmit.vue";
import BackBurner from "@/Components/Notification/BackBurner.vue";
import AssignUserPrevileges from "@/Components/Notification/AssignUserPrevileges.vue";
import UserAssignForm from "@/Components/Notification/UserAssignForm.vue";
import UserAddedToGroup from "@/Components/Notification/UserAddedToGroup.vue";
import RevokeUserPrevileges from "@/Components/Notification/RevokeUserPrevileges.vue";
import AddSubject from "@/Components/Notification/AddSubject.vue";
import AdminAssignFormToCompany from "@/Components/Notification/AdminAssignFormToCompany.vue";
import ThankYouFinalSubmit from "@/Components/Notification/ThankYouFinalSubmit.vue";
import UpdateDirectory from "@/Components/Notification/UpdateDirectory.vue";
import AddModuleToDirectory from "@/Components/Notification/AddModuleToDirectory.vue";
import DeleteDirectoryModule from "@/Components/Notification/DeleteDirectoryModule.vue";
import SubjectArchive from "@/Components/Notification/SubjectArchive.vue";
import FormArchive from "@/Components/Notification/FormArchive.vue";
import AddDirectory from "@/Components/Notification/AddDirectory.vue";
import AddLearningPath from "@/Components/Notification/AddLearningPath.vue";
import UpdateLearningPath from "@/Components/Notification/UpdateLearningPath.vue";
import LockLearningPath from "@/Components/Notification/LockLearningPath.vue";
import DeleteLearningPath from "@/Components/Notification/DeleteLearningPath.vue";
import AddModuleToLearningPath from "@/Components/Notification/AddModuleToLearningPath.vue";
import RemoveLearningPathModule from "@/Components/Notification/RemoveLearningPathModule.vue";
import AddMemberToLearningPath from "@/Components/Notification/AddMemberToLearningPath.vue";
import RemoveLearningPathMember from "@/Components/Notification/RemoveLearningPathMember.vue";
import CompletedLearningPathModule from "@/Components/Notification/CompletedLearningPathModule.vue";
import ArchiveLearningPath from "@/Components/Notification/ArchiveLearningPath.vue";
import UnarchiveLearningPath from "@/Components/Notification/UnarchiveLearningPath.vue";
import RestoreLearningPath from "@/Components/Notification/RestoreLearningPath.vue";
import DuplicateLearningPath from "@/Components/Notification/DuplicateLearningPath.vue";
import UpdateReviewModule from "@/Components/Notification/UpdateReviewModule.vue";
import UpdateReviewModuleDate from "@/Components/Notification/UpdateReviewModuleDate.vue";
import ReviewReminder from "@/Components/Notification/ReviewReminder.vue";
import UpdateCompanyModule from "@/Components/Notification/UpdateCompanyModule.vue";
import RequestToUpdateModule from "@/Components/Notification/RequestToUpdateModule.vue";
import MoveToLibrary from "@/Components/Notification/MoveToLibrary.vue";
import DeleteDirectory from "@/Components/Notification/DeleteDirectory.vue";
import ModuleExpiry from "@/Components/Notification/ModuleExpiry.vue";
import NewAssignedFormReminder from "@/Components/Notification/NewAssignedFormReminder.vue";
import Pagination from "@/Components/Pagination.vue";

export default {
  mixins: [GeneralMixin],
  components: {
    Pagination,
    AuthenticatedLayout,
    Head,
    TrashIcon,
    DocumentArrowUpIcon,
    EyeIcon,
    EyeSlashIcon,
    DeleteNotification,
    AssestAddedToGroup,
    AddedGroup,
    ArchiveFormLibrary,
    UnarchiveFormLibrary,
    DeleteMember,
    DeleteSubject,
    SubjectStatus,
    UserTrainingComplete,
    AdminArchive,
    AdminUnarchive,
    FinaliseSubmit,
    BackBurner,
    AssignUserPrevileges,
    UserAssignForm,
    UserAddedToGroup,
    RevokeUserPrevileges,
    AddSubject,
    AdminAssignFormToCompany,
    ThankYouFinalSubmit,
    UpdateDirectory,
    AddModuleToDirectory,
    DeleteDirectoryModule,
    SubjectArchive,
    FormArchive,
    AddDirectory,
    AddLearningPath,
    UpdateLearningPath,
    LockLearningPath,
    DeleteLearningPath,
    AddModuleToLearningPath,
    RemoveLearningPathModule,
    AddMemberToLearningPath,
    RemoveLearningPathMember,
    CompletedLearningPathModule,
    ArchiveLearningPath,
    UnarchiveLearningPath,
    RestoreLearningPath,
    DuplicateLearningPath,
    UpdateReviewModule,
    UpdateReviewModuleDate,
    ReviewReminder,
    UpdateCompanyModule,
    RequestToUpdateModule,
    MoveToLibrary,
    DeleteDirectory,
    ModuleExpiry,
    NewAssignedFormReminder,
  },
  props: {
    user: {
      type: Object,
    },
  },
  data() {
    return {
      notifications: [],
      isDelete: false,
      marked: null,
      queryData: {
        isDeleted: false,
      },
      modalData: {
        actionId: "",
        modalText: "",
        buttonText: "",
        actionType: "",
        actionUrl: "",
        methodType: "",
      },
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
      },
    };
  },
  watch: {
    isDelete: function () {
      document.body.style.overflow = this.isDelete ? "hidden" : "";
    },
  },
  methods: {
    setPagination(response) {
      this.pagination.total = response.data.meta.total;
      this.pagination.lastPage = response.data.meta.last_page;
      this.pagination.currentPage = response.data.meta.current_page;
    },
    notificationTable(page) {
      this.queryData.page = page ?? 1;
      axios
        .get("/api/notifications-list", { params: this.queryData })
        .then((response) => {
          this.notifications = response.data.data;
          this.setPagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    openDeleteNotification(id) {
      this.isDelete = true;
      this.modalData.modalText =
        "Are you sure you want to Delete this Notification?";
      this.modalData.buttonText = "Yes Delete it";
      this.modalData.methodType = "delete";
      this.modalData.actionUrl = "/api/delete-notification/" + id;
    },
    opencleanNotification() {
      this.isDelete = true;
      this.modalData.modalText =
        "Are you sure you want to clear this Notification?";
      this.modalData.buttonText = "Yes clear it";
      this.modalData.methodType = "delete";
      this.modalData.actionUrl = "/api/clear-notification";
    },
    openMarkedNotification(id) {
      this.modalData.methodType = "get";
      this.modalData.actionUrl = "/api/marked-notification/" + id;
      this.submitAction();
    },
    openUnMarkedNotification(id) {
      this.modalData.methodType = "get";
      this.modalData.actionUrl = "/api/unread-notification/" + id;
      this.submitAction();
    },
    openUnMarkedAllNotification() {
      this.modalData.methodType = "get";
      this.modalData.actionUrl = "/api/unmarked-all-notification";
      this.submitAction();
    },
    openMarkedAllNotification() {
      this.modalData.methodType = "get";
      this.modalData.actionUrl = "/api/marked-all-notification";
      this.submitAction();
    },
    submitAction() {
      let that = this;
      const config = {
        method: that.modalData.methodType,
        url: that.modalData.actionUrl,
      };
      axios(config)
        .then((res) => {
          console.log(res.data.status);
          //Perform Success Action
          if (res.data.status == true) {
            that.isDelete = false;
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);
            that.notificationTable();
            that.appStore.fetchNotifications();
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;
          
          document.body.style.overflow =  "";
        })
        .finally(() => {
          //Perform action in always
        });
    },
    isCloseDelete() {
      this.isDelete = false;
    },
  },
  created() {
    this.notificationTable(1);
  },
  computed: {
        ...mapStores(useAppStore),
        ...mapState(useAppStore,['userRole'
        ]),
    },
};
</script>
