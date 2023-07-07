<template>
  <div>
    <InertiaHead title="Members" />
    <AuthenticatedLayout>
      <div class="mt-2">
        <inertia-link :href="route('admin.manage-users')" v-if="is_archived ==0"
          class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{ trans("lang.labels.manage_members")
          }}</inertia-link>
        <inertia-link :href="route('admin.manage-users')" v-if="is_archived !=0"
          class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{ trans("lang.labels.member_archived_heading")
          }}</inertia-link>
        <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base" v-if="is_archived ==0">
          {{ trans("lang.labels.add_new_members_or_manage_existing") }}
        </h5>
        <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base" v-else>
          {{ trans("lang.labels.member_archived_sub_heading") }}
        </h5>
      </div>
      <div class="drop-shadow-lg h-full drop-shadow-md rounded-3xl mt-5">
        <div class="relative shadow-md sm:rounded-lg mt-5">
          <div class="p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <div class="flex justify-between items-center flex-wrap">
              <h4 class="text-xl text-neutral-300 font-semibold">
                {{ trans("lang.labels.members") }}
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
                    placeholder="Search" />
                </div>
                <button @click="isAdd = true" v-if="is_archived == 0 && is_restore == 0"
                  class="desktopFilters h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                  {{ trans("lang.modal.add_new") }}
                </button>
                <inertia-link v-if="is_archived == 0 && is_restore == 0" :href="route('admin.manage-users.archive')"
                  class="desktopFilters flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">{{
                    trans("lang.modal.archived") }}</inertia-link>
                <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                  class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                  {{ trans("lang.modal.archive_selected") }}
                </button>
                <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                  class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                  {{ trans("lang.modal.unarchive_selected") }}
                </button>
                <inertia-link v-if="is_archived == 1" :href="route('admin.manage-users')"
                  class="desktopFilters flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">{{
                    trans("lang.labels.member") }}</inertia-link>
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
                  placeholder="Search" />
              </div>
              <button @click="isAdd = true" v-if="is_archived == 0 && is_restore == 0"
                class="h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                {{ trans("lang.modal.add_new") }}
              </button>
              <inertia-link v-if="is_archived == 0 && is_restore == 0" :href="route('admin.manage-users.archive')"
                class="flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">{{
                  trans("lang.modal.archived") }}</inertia-link>
              <button v-if="is_archived == 0 && is_restore == 0" @click="multipleArchiveModel()" type="button"
                class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                {{ trans("lang.modal.archive_selected") }}
              </button>
              <button v-if="is_archived == 1 && is_restore == 0" @click="multipleUnArchiveModel()" type="button"
                class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                {{ trans("lang.modal.unarchive_selected") }}
              </button>
              <inertia-link v-if="is_archived == 1" :href="route('admin.manage-users')"
                class="flex items-center justify-center h-10 text-sm w-32 text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">{{
                  trans("lang.labels.member") }}</inertia-link>
            </div>
          </div>
          <div class="themeOverflowCustom themeMemberOverflowTable">
            <div v-if="members.length == 0" class="themeNoFound bg-body">
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
            <table v-else class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableMember">
              <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                <tr>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                  <th scope="col" class="p-4 w-20 tableMultiActions">
                    <div class="flex items-center justify-center">
                      <input id="checkbox-all-search" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMemberImage">
                    {{ trans("lang.labels.image") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMemberName">
                    {{ trans("lang.labels.name") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMemberEmail">
                    {{ trans("lang.labels.email") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableCompanyName">
                    {{ trans("lang.labels.company") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableRoleName">
                    {{ trans("lang.labels.role") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in members" :key="item.id"
                  class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                  <td class="p-4 text-end">
                    <!-- Default dropstart button -->
                    <div class="btn-group dropstart">
                      <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                        data-bs-toggle="dropdown" aria-expanded="false" data-dropdown-trigger="hover">
                        <EllipsisVerticalIcon class="h-8 w-8 text-white" aria-hidden="true" />
                      </button>
                      <div class="dropdown-menu theme-dropdown-menu bg-card rounded-2xl py-1 p-0.5">
                        <ul class="py-0">
                          <li class="px-4 py-3 bg-card text-white rounded-t-2xl">
                            {{ trans("lang.labels.options") }}
                          </li>
                          <li class="" v-if="item.deleted_at == null && is_archived != 1">
                            <a class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white"
                              @click="edit(item.id)">
                              <PencilIcon class="h-5 w-5 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.modal.edit_details") }}
                            </a>
                          </li>
                          <li v-if="item.deleted_at == null &&
                            is_archived != 1 &&
                            !checkAdmin
                            ">
                            <a class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white"
                              @click="assignModule(item.id)">
                              <ArrowUpOnSquareStackIcon class="h-5 w-5 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.assign_module") }}
                            </a>
                          </li>
                          <li v-if="(checkAdmin) &&
                            item.id != this.user.id &&
                            is_archived != 1
                            ">
                            <a class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl"
                              @click="loginAsUser(item.id)">
                              <ArrowLeftOnRectangleIcon class="h-5 w-5 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.login_as_user") }}
                            </a>
                          </li>
                          <!-- Permission Menus -->
                          <li v-if="checkCompanyAdmin &&
                            is_archived != 1
                            ">
                            <a v-if="item.roleName == 'employee'" @click="
                              openPrivilage(item.id, 'Assign Priviliges')
                              "
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                              <AdjustmentsHorizontalIcon class="h-5 w-5 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.assign_admin_privileges") }}
                            </a>
                            <a v-else-if="item.roleName == 'businessadmin'" @click="
                              openPrivilage(item.id, 'Revoke Priviliges')
                              "
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                              <UserMinusIcon class="h-5 w-5 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.revoke_admin_privileges") }}
                            </a>
                          </li>
                          <!-- Archive menus -->
                          <li v-if="is_archived != 1 &&
                            checkCompanyAdmin &&
                            checkAdmin
                            ">
                            <a @click="openArchivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArchiveBoxArrowDownIcon class="h-5 w-5 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.modal.archived") }}
                            </a>
                          </li>
                          <li v-if="is_archived != 1 && item.archiveMenuVisible
                            ">
                            <a @click="openArchivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArchiveBoxArrowDownIcon class="h-5 w-5 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.modal.archived") }}
                            </a>
                          </li>
                          <li v-if="is_archived == 1 && is_restore != 1">
                            <a @click="openUnArcheivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArchiveBoxXMarkIcon class="h-5 w-5 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.modal.unarchived") }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td class="w-4 p-4">
                    <div class="flex items-center justify-center">
                      <input id="checkbox-table-search-1" type="checkbox" :value="item.id" v-model="multipleMembers"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                  </td>
                  <td class="p-4">
                    <img :src="mediaPath + item.avatar" class="w-14 h-14 rounded-lg object-cover" />
                  </td>
                  <td class="p-4 breakWord">
                    <span>{{ item.name }}</span>
                  </td>
                  <td class="p-4">
                    <span>{{ item.email }}</span>
                  </td>
                  <td class="p-4 breakWord">
                    <span>{{ item.company }}</span>
                  </td>
                  <td class="p-4">
                    <span>{{ item.role }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="bg-sidebar px-4 py-5 rounded-b-3xl mb-6">
        <Pagination v-if="pagination.lastPage != 1" @refreshTable="membersTable" :currentPage="pagination.currentPage"
          :lastPage="pagination.lastPage" :total="pagination.total" />
      </div>
      <div class="dark:bg-gray-700 drop-shadow-lg h-full drop-shadow-md rounded-3xl mt-10">
        <div class="relative shadow-md sm:rounded-lg mt-24">
          <div class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <h4 class="text-xl text-neutral-300 font-semibold">
              {{ trans("lang.labels.pending_invites") }}
            </h4>
            <div class="filterBtn">
              <button type="button" @click="openInviteFilter()"
                class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
              </button>
            </div>
            <div
              class="desktopFilters flex justify-between items-center flex-wrap mt-3 sm:mt-0 md:mt-0 multiplyMeTableActions gap-2 w-full sm:w-fit md:w-fit">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input type="text" id="simple-search" v-model="pendingSearch"
                  class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 focus:border-amber-500 block w-full pl-10 p-2.5"
                  placeholder="Search" />
              </div>
            </div>
            <div v-if="inviteFilters" class="mobileFilters flex flex-wrap gap-1 mt-4">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input type="text" id="simple-search" v-model="pendingSearch"
                  class="h-10 bg-sidebar border border-gray-300 text-sm text-neutral-400 rounded-lg focus:ring-0 focus:border-amber-500 block w-full pl-10 p-2.5"
                  placeholder="Search" />
              </div>
            </div>
          </div>
          <div class="themeOverflowCustom themeOverflowTable">
            <div v-if="pendingInvites.length == 0" class="themeNoFound bg-body">
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
            <table v-else
              class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableMemberInvite">
              <thead class="text-xs text-gray-700 uppercase bg-sidebar dark:text-gray-400">
                <tr>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMultiActions"></th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableMemberEmail">
                    {{ trans("lang.labels.email") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableCompanyName">
                    {{ trans("lang.labels.business") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in pendingInvites" :key="item.id"
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
                          <li class="">
                            <a @click="openInvite(item.id, 'Invite')"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <PaperAirplaneIcon class="h-5 w-5 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.resend_invite") }}
                            </a>
                          </li>
                          <li class="">
                            <a @click="openInvite(item.id, 'Delete', item.email)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-red-600">
                              <TrashIcon class="h-5 w-5 text-red-600 mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.delete_invite") }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td class="p-4">
                    <span>{{ item.email }}</span>
                  </td>
                  <td class="p-4 breakWord">
                    <span>{{ item.company }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
            <Pagination v-if="pendingInvitepagination.lastPage != 1" @refreshTable="pendingInviteTable"
              :currentPage="pendingInvitepagination.currentPage" :lastPage="pendingInvitepagination.lastPage"
              :total="pendingInvitepagination.total" />
          </div>
        </div>
      </div>

      <add @closeAdd="isCloseAdd()" @refreshTable="membersTable()" v-if="isAdd"></add>
      <edit @closeEdit="isCloseEdit()" v-if="isEdit" :data="modalData" @refreshTable="membersTable"></edit>
      <assign @closeAssign="isCloseAssign()" v-if="isAssign" :data="modalData"></assign>
      <delete ref="deletefunc" @cancel="isCloseDelete()" v-if="isDelete" :data="modalData"
        @deleteSubmitAction="submitDeleteModelAction()"></delete>
      <multiple ref="deletefunc" v-if="isMultipleModal" :data="modalData" @cancelMultipleArchive="cancelMultiple()"
        @multipleDelAction="submitMultipleDeleteAction()">
      </multiple>
      <privilage ref="privilagefunc" @cancelPrivilage="isClosePrivilage()" :data="this.privilesData"
        @privilageSubmitAction="submitAssignPrivilage()" v-if="isPrivilage"></privilage>
      <invite @closeInvite="closeInvite()" v-if="isInvite" :data="this.inviteData"></invite>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Add from "@/Components/Member/Add.vue";
import Edit from "@/Components/Member/Edit.vue";
import Assign from "@/Components/Member/Assign.vue";
import Delete from "@/Components/Member/Delete.vue";
import Multiple from "@/Components/ManageGroups/MultipleArchive.vue";
import Invite from "@/Components/Member/Invite.vue";
import Privilage from "@/Components/Member/Privilage.vue";
import Pagination from "@/Components/Pagination.vue";
import { mapStores, mapState } from "pinia";
import { useAppStore } from "@/store";
import GeneralMixin from "@/Mixins/GeneralMixin";
import { isAdmin, isCompany, isCompanyAdmin,isBusinessAdmin, isMember } from "@/helpers";
import {
  EllipsisVerticalIcon,
  ArrowLeftOnRectangleIcon,
  TrashIcon,
  PencilIcon,
  ArrowPathIcon,
  PaperAirplaneIcon,
  ArrowUpOnSquareStackIcon,
  AdjustmentsHorizontalIcon,
  UserMinusIcon,
  ArchiveBoxArrowDownIcon,
  ArchiveBoxXMarkIcon,
  FunnelIcon
} from "@heroicons/vue/24/solid";
export default {
  components: {
    Add,
    Edit,
    Assign,
    Delete,
    Multiple,
    AuthenticatedLayout,
    Privilage,
    Invite,
    Pagination,
    EllipsisVerticalIcon,
    ArrowLeftOnRectangleIcon,
    PencilIcon,
    TrashIcon,
    ArrowPathIcon,
    PaperAirplaneIcon,
    ArrowUpOnSquareStackIcon,
    AdjustmentsHorizontalIcon,
    UserMinusIcon,
    ArchiveBoxArrowDownIcon,
    ArchiveBoxXMarkIcon,
    FunnelIcon
  },
  props: ["is_archived", "is_restore", "user"],
  mixins: [GeneralMixin],
  data() {
    return {
      mobileFilters: false,
      inviteFilters: false,
      members: [],
      pendingInvites: [],
      searchTimeout: null,
      search: "",
      is_deleted: 0,
      isDeleteMember: false,
      pendingSearch: "",
      isAdd: false,
      isEdit: false,
      isAssign: false,
      isDelete: false,
      isInvite: false,
      isMultipleModal: false,
      isArchiveModal: false,
      isPrivilage: false,
      multipleMembers: [],
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
      pendingInvitepagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
      },
      queryData: {
        search: "",
        is_deleted: this.is_restore,
        page: 1,
        isArchived: this.is_archived,
        per_page: 20,
      },
      inviteData: {
        title: "",
        subTitle: "",
        actionUrl: "",
      },
      loggedUserRole: "",
      privilesData: {
        modalTitle: "",
        modalSubTitle: "",
        actionUrl: "",
      },
    };
  },
  computed: {
    ...mapStores(useAppStore),
    ...mapState(useAppStore, [
      "unreadNotificationCount",
      "recentNotifications",
      "user", 'userRole'
    ]),
    checkAdmin() {
      let role = this.appStore.userRole;
      return isAdmin(role);
    },
    checkCompany() {
      let role = this.appStore.userRole;
      return isCompany(role);
    },
    checkCompanyAdmin() {
      let role = this.appStore.userRole;
      return isCompanyAdmin(role);
    },
    checkBusinessAdmin() {
      let role = this.appStore.userRole;
      return isBusinessAdmin(role);
    },
    checkMember() {
      let role = this.appStore.userRole;
      return isMember(role);
    }
  },
  watch: {
    search: function (value) {
      let self = this;
      clearTimeout(self.searchTimeout);
      self.searchTimeout = setTimeout(function () {
        self.queryData.search = self.search;
        self.membersTable();
      }, 700);
    },
    pendingSearch: function (value) {
      let that = this;
      clearTimeout(that.searchTimeout);
      that.searchTimeout = setTimeout(function () {
        that.queryData.search = that.pendingSearch;
        that.pendingInviteTable();
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
    isInvite: function () {
      document.body.style.overflow = this.isInvite ? "hidden" : "";
    },
    isMultipleModal: function () {
      document.body.style.overflow = this.isMultipleModal ? "hidden" : "";
    },
    isArchiveModal: function () {
      document.body.style.overflow = this.isArchiveModal ? "hidden" : "";
    },
    isPrivilage: function () {
      document.body.style.overflow = this.isPrivilage ? "hidden" : "";
    },
  },
  methods: {
    openFilter() {
      this.mobileFilters = !this.mobileFilters;
    },
    openInviteFilter() {
      this.inviteFilters = !this.inviteFilters;
    },
    setPagination(response) {
      this.pagination.total = response.data.meta.total;
      this.pagination.lastPage = response.data.meta.last_page;
      this.pagination.currentPage = response.data.meta.current_page;
    },
    setPendingInvitePagination(response) {
      this.pendingInvitepagination.total = response.data.meta.total;
      this.pendingInvitepagination.lastPage = response.data.meta.last_page;
      this.pendingInvitepagination.currentPage =
        response.data.meta.current_page;
    },
    membersTable(page) {
      this.queryData.page = page;
      //for table data is loading after fetch ==>
      axios
        .get("/api/members", { params: this.queryData })
        .then((response) => {
          this.members = response.data.data;
          this.setPagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    pendingInviteTable(page) {
      this.queryData.page = page;
      //for table data is loading after fetch ==>
      axios
        .get("/api/pending-invites", { params: this.queryData })
        .then((response) => {
          this.pendingInvites = response.data.data;
          this.setPendingInvitePagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    submitDeleteModelAction() {
      let that = this;
      let formData = new FormData();
      formData.append("member_id", that.modalData.memberId);
      formData.append("actionType", that.modalData.actionType);
      axios
        .post("/api/member-action", formData, {
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
            that.membersTable(1);
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
      formData.append("multipleMembers", that.multipleMembers);
      axios
        .post("/api/multiple-member-action", formData, {
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
            that.membersTable(1);
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
    submitAssignPrivilage() {
      let that = this;
      axios
        .post("/api/" + that.privilesData.actionUrl)
        .then((res) => {
          //Perform Success Action
          if (res.data.status == true) {
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);
            that.membersTable(1);
            that.$refs.privilagefunc.processingFunc();
            that.isPrivilage = false;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;

          that.isPrivilage = false;
        })
        .finally(() => {
          //Perform action in always
        });
    },
    editField() {
      axios
        .get("/api/plans/" + this.itemid)
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
    isCloseAdd() {
      this.isAdd = false;
    },
    isCloseDelete() {
      this.isDelete = false;
    },
    isCloseArchive() {
      this.isArchiveModal = false;
    },
    edit(id) {
      let that = this;
      that.isEdit = true;
      that.modalData.memberId = id;
    },
    isCloseEdit() {
      this.isEdit = false;
    },
    assignModule(id) {
      let that = this;
      that.isAssign = true;
      that.modalData.memberId = id;
    },
    closeInvite() {
      this.isInvite = false;
      this.pendingInviteTable(1);
    },
    openInvite(id, actionType, email = "") {
      this.isInvite = true;
      if (actionType == "Delete") {
        this.inviteData.title = "Deleting invite for " + email;
        this.inviteData.subTitle = this.trans(
          "lang.labels.confirmation_needed_delete_invite_will_not_be_recoverable"
        );
        this.inviteData.actionUrl = "/delete-invite/" + id;
      } else {
        this.inviteData.title = "Reinvite User";
        this.inviteData.subTitle = "";
        this.trans(
          "lang.labels.invite_already_sent_do_you_want_to_resend_invite_to_the_user"
        );
        this.inviteData.actionUrl = "/resend-invite/" + id;
      }
    },
    openArchivedModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_member"
      );
      this.modalData.memberId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
    },
    openUnArcheivedModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_member"
      );
      this.modalData.memberId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },
    openSoftDeleteModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_delete_this_member"
      );
      this.modalData.memberId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_delete_it");
      this.modalData.actionType = "Delete";
    },
    openRestoreModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_restore_this_member"
      );
      this.modalData.memberId = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_restore_it");
      this.modalData.actionType = "Restore";
    },
    multipleArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.title = "Member";
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_members"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
    },
    multipleUnArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_members"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },
    isCloseAssign() {
      this.isAssign = false;
    },
    refreshTable() {
      this.pendingInviteTable(1);
      this.membersTable(1);
    },
    openPrivilage(id, actionType) {
      this.isPrivilage = true;
      if (actionType == "Assign Priviliges") {
        this.privilesData.actionUrl = "assign-admin-previleges/" + id;
        this.privilesData.modalTitle = "Assign Business Admin Privileges";
        this.privilesData.modalSubTitle =
          "Are you sure you want to assign business admin privileges to this user?";
      } else {
        this.privilesData.modalTitle = "Revoke Business Admin Privileges";
        this.privilesData.modalSubTitle =
          "Are you sure you want to revoke business admin privileges from this user?";
        this.privilesData.actionUrl = "remove-admin-previleges/" + id;
      }
    },
    isClosePrivilage() {
      this.isPrivilage = false;
    },
    cancelMultiple() {
      this.isMultipleModal = false;
    },
    loginAsUser(id) {
      axios
        .get("/api/login-as-user/" + id)
        .then((res) => {
          if (res.status == 200) {
            this.$inertia.visit(route("xme.overview"));
          }
        })
        .catch((error) => {
          // error.response.status Check status code
        })
        .finally(() => {
          //Perform action in always
        });
    },
  },
  created() {
    this.loggedUserRole = this.user.role.name;
    this.membersTable(1);
    this.pendingInviteTable(1);
  },
  unmounted() {
    clearTimeout(this.searchTimeout);
    this.membersTable(1);
    this.pendingInviteTable(1);
  },
};
</script>
