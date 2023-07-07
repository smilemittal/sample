<template>
  <div>
    <span v-if="is_develop == true">
      <InertiaHead title="Develop Area" />
    </span>
    <span v-else>
      <InertiaHead title="Identify Area" />
    </span>
    <AuthenticatedLayout>
      <div class="mt-2" v-if="is_develop == true">
        <h4 class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">
          {{ trans("lang.labels.develop_area") }}
        </h4>
        <h5 class="text-base text-neutral-400">
          {{
            trans(
              "lang.labels.here_is_your_list_ready_to_develop_into_video_modules"
            )
          }}
        </h5>
      </div>
      <div class="mt-2" v-else>
        <h4 class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">
          {{ trans("lang.labels.opportunites_idops") }}
        </h4>
        <h5 class="text-base text-neutral-400">
          {{ trans("lang.labels.here_is_your_live_list_of_idops") }}
        </h5>
      </div>
      <div class="h-full drop-shadow-md rounded-3xl mt-5">
        <div class="relative shadow-md sm:rounded-lg mt-5">
          <div class="flex justify-between items-center flex-wrap p-4 bg-sidebar rounded-t-3xl border-b border-zinc-200">
            <h4 class="text-xl text-neutral-300 font-semibold" v-if="is_develop == false">
              {{ trans("lang.modal.identify") }}
            </h4>
            <h4 class="text-xl text-neutral-300 font-semibold" v-if="is_develop == true">
              {{ trans("lang.labels.develop") }}
            </h4>
            <div class="filterBtn">
              <button type="button" @click="openFilter()"
                class="btn h-8 w-8 theme-dropdown-btn bg-amber-500 rounded-lg flex justify-center items-center">
                <FunnelIcon class="h-5 w-5 text-white" aria-hidden="true" />
              </button>
            </div>
            <div class="flex justify-start items-center flex-wrap gap-3 mt-3 sm:mt-0 md:mt-0 multiplyMeTableActions">
              <div class="desktopFilters w-full sm:w-fit md:w-fit">
                <span class="text-base text-neutral-400">{{
                  trans("lang.labels.filter_by_priority")
                }}</span>
              </div>
              <select id="form_status" @change="changeType($event)"
                class="desktopFilters bg-sidebar border border-gray-300 text-neutral-400 focus:border-amber-500 focus:ring-0 text-sm rounded-lg p-2.5"
                v-model="priorityType">
                <option value="0">Low</option>
                <option value="1">Medium</option>
                <option value="2">High</option>
              </select>
              <div v-if='!checkMember' class="flex items-center">
                <div class="desktopFilters w-full sm:w-fit md:w-fit">
                  <span class="text-base text-neutral-400">{{
                    trans("lang.labels.status")
                  }}: </span>
                </div>
                <select id="form_type" @change="changeIdopType($event)"
                  class="desktopFilters bg-sidebar border border-gray-300 text-neutral-400 focus:border-amber-500 focus:ring-0 text-sm rounded-lg p-2.5 ml-2"
                  v-model="is_idops">
                  <option value="0">IDOPS</option>
                  <option value="1">Review IDOPS</option>
                </select>
              </div>

              <div class="desktopFilters w-full sm:w-fit md:w-fit">
                <span class="text-base text-neutral-400">
                  {{ trans("lang.labels.idop_type") }}</span>
              </div>
              <select id="form_type" @change="changeStatus($event)" v-if="!is_develop"
                class="desktopFilters bg-sidebar border border-gray-300 text-neutral-400 focus:border-amber-500 focus:ring-0 text-sm rounded-lg p-2.5"
                v-model="status">
                <option value="0">Draft</option>
              </select>
              <select id="form_type" @change="changeStatus($event)" v-if="is_develop"
                class="desktopFilters bg-sidebar border border-gray-300 text-neutral-400 focus:border-amber-500 focus:ring-0 text-sm rounded-lg p-2.5"
                v-model="status">
                <option value="101">Approved</option>
                <option value="100">Incomplete</option>
                <option value="103">Submitted</option>
              </select>
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
                  placeholder="Search" />
              </div>
              <inertia-link href="/identify/new" v-if="!is_develop && is_archived == 0 && is_restore == 0"
                class="desktopFilters flex items-center justify-center h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                {{ trans("lang.labels.add_new_subject") }}
              </inertia-link>
              <inertia-link v-if="is_develop == false && is_archived == 0"
                class="desktopFilters h-10 text-sm w-32 flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                :href="route('app.identify.archive')">{{ trans("lang.modal.archived") }}</inertia-link>
              <inertia-link v-if="is_develop == false && is_archived == 1"
                class="desktopFilters h-10 text-sm w-32 flex items-center justify-center text-neutral-400 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                :href="route('app.identify')">{{ trans("lang.modal.identify") }}</inertia-link>

              <!-- is_develop == true -->
              <inertia-link v-if="is_develop == true && is_archived == 0"
                class="desktopFilters h-10 text-sm w-32 flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                :href="route('app.develop.archive')">{{ trans("lang.modal.archived") }}</inertia-link>
              <inertia-link v-if="is_develop == true && is_archived == 1"
                class="desktopFilters h-10 text-sm w-32 flex items-center justify-center text-neutral-400 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                :href="route('app.develop')">{{ trans("lang.labels.develop") }}</inertia-link>
              <button v-if="is_archived == 0" @click="multipleArchiveModel()" type="button"
                class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                {{ trans("lang.modal.archive_selected") }}
              </button>
              <button v-if="is_archived == 1" @click="multipleUnArchiveModel()" type="button"
                class="desktopFilters h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                {{ trans("lang.modal.unarchive_selected") }}
              </button>
            </div>
            <div v-if="mobileFilters" class="mobileFilters mt-4">
              <div class="relative">
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
              <div class="mobileFilterIndentify mt-4">
                <select id="form_status" @change="changeType($event)"
                  class="bg-sidebar border border-gray-300 text-neutral-400 focus:border-amber-500 focus:ring-0 text-sm rounded-lg p-2.5"
                  v-model="priorityType">
                  <option value="0">Low</option>
                  <option value="1">Medium</option>
                  <option value="2">High</option>
                </select>
                <select id="form_type" @change="changeIdopType($event)" v-if="!checkMember"
                  class="bg-sidebar border border-gray-300 text-neutral-400 focus:border-amber-500 focus:ring-0 text-sm rounded-lg p-2.5"
                  v-model="is_idops">
                  <option value="0">IDOPS</option>
                  <option value="1">Review IDOPS</option>
                </select>
                <select id="form_type" @change="changeStatus($event)" v-if="!is_develop"
                  class="bg-sidebar border border-gray-300 text-neutral-400 focus:border-amber-500 focus:ring-0 text-sm rounded-lg p-2.5"
                  v-model="status">
                  <option value="0">Draft</option>
                </select>
                <select id="form_type" @change="changeStatus($event)" v-if="is_develop"
                  class="bg-sidebar border border-gray-300 text-neutral-400 focus:border-amber-500 focus:ring-0 text-sm rounded-lg p-2.5"
                  v-model="status">
                  <option value="101">Approved</option>
                  <option value="100">Incomplete</option>
                  <option value="103">Submitted</option>
                </select>
                <inertia-link href="/identify/new" v-if="!is_develop && is_archived == 0 && is_restore == 0"
                  class="flex items-center justify-center h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white">
                  {{ trans("lang.labels.add_new_subject") }}
                </inertia-link>
                <inertia-link v-if="is_develop == false && is_archived == 0"
                  class="h-10 text-sm flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                  :href="route('app.identify.archive')">{{ trans("lang.modal.archived") }}</inertia-link>
                <inertia-link v-if="is_develop == false && is_archived == 1"
                  class="h-10 text-sm w-32 flex items-center justify-center text-neutral-400 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                  :href="route('app.identify')">{{ trans("lang.modal.identify") }}</inertia-link>

                <!-- is_develop == true -->
                <inertia-link v-if="is_develop == true && is_archived == 0"
                  class="h-10 text-sm flex items-center justify-center text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                  :href="route('app.develop.archive')">{{ trans("lang.modal.archived") }}</inertia-link>
                <inertia-link v-if="is_develop == true && is_archived == 1"
                  class="h-10 text-sm flex items-center justify-center text-neutral-400 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2"
                  :href="route('app.develop')">{{ trans("lang.labels.develop") }}</inertia-link>
                <button v-if="is_archived == 0" @click="multipleArchiveModel()" type="button"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.archive_selected") }}
                </button>

                <button v-if="is_archived == 1" @click="multipleUnArchiveModel()" type="button"
                  class="h-10 text-sm text-neutral-400 px-2 rounded-lg border-solid border border-zinc-300 hover:bg-amber-500 hover:text-white ml-0 sm:ml-2 md:ml-2">
                  {{ trans("lang.modal.unarchive_selected") }}
                </button>
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
            <table v-else class="theme-table w-full text-sm text-left text-gray-500 dark:text-gray-400 themeTableDevelop">
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
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4 tableIdentifySubject">
                    {{ trans("lang.labels.subject") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.priority") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.status") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4">
                    {{ trans("lang.labels.last_edit") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4" v-if="checkAdmin">
                    {{ trans("lang.labels.company_name") }}
                  </th>
                  <th scope="col" class="text-neutral-300 font-semibold text-base p-4" v-if="is_archived == 1">
                    {{ trans("lang.labels.archive_reason") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in items" :key="item.id"
                  class="border-b dark:border-gray-700 text-base text-neutral-300 hover:text-white">
                  <td class="p-4 text-center">
                    <!-- Default dropstart button -->
                    <div class="btn-group dropstart" v-if="!checkMember">
                      <button type="button" class="btn btn-secondary h-10 w-10 theme-dropdown-btn"
                        data-bs-toggle="dropdown" aria-expanded="false" data-dropdown-trigger="hover">
                        <EllipsisVerticalIcon class="h-8 w-8 text-white m-auto" aria-hidden="true" />
                      </button>
                      <div class="dropdown-menu bg-card rounded-2xl p-0.5 w-60">
                        <!-- Actions for Identify -->
                        <ul v-if="is_develop == 0" class="py-0">
                          <li class="px-4 py-3 bg-card text-white rounded-t-2xl">
                            Options
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <inertia-link :href="route('app.identity-edit', item.encryptId)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.site.Edit Details") }}
                            </inertia-link>
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <a @click="develop(item)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <BeakerIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.send_to_develop") }}
                            </a>
                          </li>
                          <li class="" v-if="item.status == 'backburner' && is_archived != 1">
                            <a @click="draft(item)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <DocumentCheckIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.mark_as_draft") }}
                            </a>
                          </li>
                          <li class="" v-if="is_archived != 1">
                            <a @click="openArchivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArrowPathIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.archived") }}
                            </a>
                          </li>
                          <li class="" v-if="is_archived == 1">
                            <a @click="openUnArcheivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArrowPathIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.unarchived") }}
                            </a>
                          </li>
                        </ul>
                        <!-- Actions For Develop -->
                        <ul v-else class="py-0">
                          <li class="px-4 py-3 bg-card text-white rounded-t-2xl">
                            Options
                          </li>
                          <!-- Review Details -->
                          <li class="" v-if="is_archived != 1 && item.status != 'submitted'
                            ">
                            <inertia-link :href="route('app.develop-edit', item.encryptId)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.review_details") }}
                            </inertia-link>
                          </li>
                          <li class="" v-if="is_archived != 1 &&
                            item.status == 'submitted' && checkAdmin">
                            <inertia-link :href="route('app.develop-edit', item.encryptId)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <PencilIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.review_details") }}
                            </inertia-link>
                          </li>
                          <!-- Mark As Approved -->
                          <li class="" v-if="item.status != 'approved' &&
                            item.status != 'submitted' &&
                            item.status != 'reopen' &&
                            is_archived != 1
                            ">
                            <a @click="markComplete(item)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ClipboardDocumentCheckIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.mark_as_approved") }}
                            </a>
                          </li>
                          <!-- Return to Identify -->
                          <li class="" v-if="item.status != 'approved' &&
                            item.status != 'submitted' &&
                            item.status != 'reopen' &&
                            is_archived != 1
                            ">
                            <a @click="returnToIdentify(item)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-red-600">
                              <BeakerIcon class="h-4 w-4 text-red-600 mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.return_to_identify") }}
                            </a>
                          </li>
                          <!-- Finalize and Submit -->
                          <li class="" v-if="(item.status == 'approved' ||
                            item.status == 'reopen') &&
                            item.status != 'submitted' &&
                            !checkAdmin &&
                            is_archived != 1
                            ">
                            <a @click="finalise(item)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <DocumentArrowUpIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.finalise_and_submit") }}
                              {{}}
                            </a>
                          </li>
                          <!-- Mark As Incomplete -->
                          <li class="" v-if="item.status == 'approved' &&
                            item.status != 'submitted' &&
                            item.status != 'reopen' &&
                            is_archived != 1
                            ">
                            <a @click="markInComplete(item)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                              <DocumentCheckIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.mark_as_incomplete") }}
                            </a>
                          </li>
                          <!-- Reopen -->
                          <li class="" v-if="item.status == 'submitted' &&
                            checkAdmin &&
                            is_archived != 1
                            ">
                            <a @click="reOpen(item)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                              <DocumentCheckIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.reopen") }}
                            </a>
                          </li>
                          <!-- <li class="" v-if="(item.status == 'reopen' ||
                            item.status == 'submitted') &&
                            item.reopen_description != '' &&
                            is_archived != 1
                            ">
                            <a @click="viewReOpenDescription(item)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white rounded-b-2xl">
                              <DocumentCheckIcon class="h-4 w-4 text-white mr-3" aria-hidden="true" />
                              {{ trans("lang.labels.reopen_request") }}
                            </a>
                          </li> -->
                          <!-- Archive Develop -->
                          <li class="" v-if="is_archived != 1 && item.status != 'submitted'">
                            <a @click="openArchivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArrowPathIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.archived") }}
                            </a>
                          </li>
                          <!-- UnArchive Develop -->
                          <li class="" v-if="is_archived == 1 && is_restore != 1">
                            <a @click="openUnArcheivedModal(item.id)"
                              class="cursor-pointer flex items-center bg-body w-full px-4 py-3 hover:bg-card text-white">
                              <ArrowPathIcon class="h-4 w-4 text-white mr-2" aria-hidden="true" />
                              {{ trans("lang.modal.unarchived") }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                  <td class="w-4 p-4">
                    <div class="flex items-center" v-if="item.status != 'submitted'">
                      <input id="checkbox-table-search-1" type="checkbox" :value="item.id" v-model="multipleSubject"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                      <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                  </td>
                  <td class="p-4 breakWord">
                    <span v-if="item.identify_reason != NUll" class="bg-bgAmberTag text-amber-500 rounded-full px-2 text-xs">Review Subject</span>
                    <span class="themeManageTitle">{{ item.subject }}</span>
                  </td>
                  <td class="p-4">
                    <span :class="item.prioritytag">{{
                      item.priorityString
                    }}</span>
                  </td>
                  <td class="p-4">
                    <div v-if="item.status == 'reopen'">
                      <span class="text-xs">
                        {{
                          trans("lang.labels.click_to_reopen_description")
                        }} </span><br />
                      <span class="hover:bg-amber-500 hover:text-white cursor-pointer" :class="item.statusTag"
                        @click="viewReOpenDescription(item)">{{ item.status }}
                      </span>
                    </div>
                    <span :class="item.statusTag" v-else>{{
                      item.status
                    }}</span>
                  </td>
                  <td class="p-4">
                    <span class="">
                      <div class="flex items-center">
                        <span>
                          {{ trans("lang.labels.last_edit_by") }} <br />
                          {{ item.updated_at }}
                        </span>
                        <img :src="mediaPath + item.imageUrl" alt="image"
                          class="rounded-full w-10 h-10 object-cover ml-3" />
                      </div>
                    </span>
                  </td>
                  <td class="p-4 breakWord" v-if="checkAdmin">
                    <span class="themeManageTitle">{{ item.company }}</span>
                  </td>
                  <td class="p-4 breakWord" v-if="is_archived == 1 && item.archived_reason != null">
                    <span class="themeManageTitle">{{ item.archived_reason }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <delete ref="deletefunc" :data="modalData" @closeArchive="isCloseArchive()"
        @deleteSubmitAction="submitDeleteModelAction()" v-if="isDelete"></delete>
      <multiple ref="deletefunc" v-if="isMultipleModal" :data="modalData" @cancelMultipleArchive="cancelMultiple()"
        @multipleDelAction="submitMultipleDeleteAction()">
      </multiple>
      <develop ref="developfunc" @closeDevelop="isCloseDevelop()" @submit="submitIndentify" v-if="isDevelop"
        :data="modalData">
      </develop>
      <re-open-description @closeReopenDescription="closeReopenDescription()" v-if="isReOpenDescription"
        :data="modalData">
      </re-open-description>
      <module-form v-if="isMoveModule" @closeAdd="closeMove()"></module-form>
      <div class="bg-sidebar px-4 py-5 rounded-b-3xl">
        <Pagination v-if="pagination.lastPage != 1" @refreshTable="createTable" :currentPage="pagination.currentPage"
          :lastPage="pagination.lastPage" :total="pagination.total" />
      </div>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// import ArchiveForm from '@/Components/Design/Module/ArchiveForm.vue'
import Develop from "@/Components/Identify/Develop.vue";
import ReOpenDescription from "@/Components/Identify/ReOpenDescription.vue";
import Pagination from "@/Components/Pagination.vue";
import Delete from "@/Components/Identify/Delete.vue";
import Multiple from "@/Components/Identify/MultipleArchive.vue";
import ModuleForm from "@/Components/Module/Add.vue";
import { mapState, mapStores } from 'pinia'
import { useAppStore } from '@/store'
import GeneralMixin from "@/Mixins/GeneralMixin";
import { isAdmin, isMember } from "@/helpers";
import {
  EllipsisVerticalIcon,
  PencilIcon,
  TrashIcon,
  ArrowPathIcon,
  DocumentArrowUpIcon,
  DocumentCheckIcon,
  BeakerIcon,
  ClipboardDocumentCheckIcon,
  FireIcon,
  PaperAirplaneIcon, FunnelIcon
} from "@heroicons/vue/24/solid";
export default {
  props: ["is_develop", "is_archived", "is_restore", "user"],
  mixins: [GeneralMixin],
  components: {
    AuthenticatedLayout,
    Pagination,
    Develop,
    Delete,
    Multiple,
    ReOpenDescription,
    ModuleForm,
    EllipsisVerticalIcon,
    PencilIcon,
    TrashIcon,
    ArrowPathIcon,
    DocumentArrowUpIcon,
    DocumentCheckIcon,
    BeakerIcon,
    ClipboardDocumentCheckIcon,
    FireIcon,
    PaperAirplaneIcon,
    FunnelIcon
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
    checkMember() {
      let role = this.appStore.userRole;
      return isMember(role);
    }
  },
  data() {
    return {
      mobileFilters: false,
      items: [],
      search: "",
      isEdit: false,
      isAssign: false,
      isDevelop: false,
      isDelete: false,
      isMultipleModal: false,
      isReOpenDescription: false,
      isMoveModule: false,
      priorityType: "Select",
      is_idops: "Select",
      idopType: 1,
      status: "Select",
      multipleSubject: [],
      currentUserRole: "",
      queryData: {
        search: "",
        page: 1,
        priorityType: null,
        status: null,
        is_idops: null,
        isArchived: this.is_archived,
        isDeleted: this.is_restore,
        is_develop: this.is_develop,
      },
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
      },

      modalData: {
        id: "",
        subject: "",
        is_reason: false,
        heading: "",
        subHeading: "",
        buttonHeading: "",
        actionType: "",
        item: "",
        reason: "",
        actionPage: this.is_develop == true ? "develop" : "identify",
      },
    };
  },
  watch: {
    is_develop: function (value) {
      this.queryData.is_develop = value;
      this.createTable();
    },
    search: function (value) {
      let self = this;
      clearTimeout(self.searchTimeout);
      self.searchTimeout = setTimeout(function () {
        self.queryData.search = self.search;
        self.createTable();
      }, 700);
    },
    isEdit: function () {
      document.body.style.overflow = this.isEdit ? "hidden" : "";
    },
    isAssign: function () {
      document.body.style.overflow = this.isAssign ? "hidden" : "";
    },
    isDevelop: function () {
      document.body.style.overflow = this.isDevelop ? "hidden" : "";
    },
    isDelete: function () {
      document.body.style.overflow = this.isDelete ? "hidden" : "";
    },
    isMultipleModal: function () {
      document.body.style.overflow = this.isMultipleModal ? "hidden" : "";
    },
    isMoveModule: function () {
      document.body.style.overflow = this.isMoveModule ? "hidden" : "";
    },
    isReOpenDescription: function () {
      document.body.style.overflow = this.isReOpenDescription ? "hidden" : "";
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
    createTable(page = 1) {
      this.queryData.page = page;
      //for table data is loading after fetch ==>
      axios
        .get("/api/subjects", { params: this.queryData })
        .then((response) => {
          this.items = response.data.data;
          this.setPagination(response);
        })
        .catch((error) => { })
        .finally(() => { });
    },
    isCloseEdit() {
      this.isEdit = false;
    },
    isCloseAssign() {
      this.isAssign = false;
    },
    isCloseArchive() {
      this.isDelete = false;
      this.createTable(1);
    },
    cancelMultiple() {
      this.isMultipleModal = false;
    },
    isCloseDevelop() {
      this.isDevelop = false;
    },
   /*  */ changeType(event) {
      let that = this;
      that.queryData.priorityType = event.target.value;
      that.createTable();
    },
    changeStatus(event) {
      let that = this;
        that.queryData.status = event.target.value;
      that.createTable();
    },
    changeIdopType(event) {
      let that = this;
      that.queryData.is_idops = event.target.value;
      that.createTable();
    },
    openArchivedModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_subject"
      );
      this.modalData.id = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
      this.modalData.reason = true;
      console.log(this.modalData);
    },
    openUnArcheivedModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_subject"
      );
      this.modalData.id = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },
    openSoftDeleteModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_delete_this_subject"
      );
      this.modalData.id = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_delete_it");
      this.modalData.actionType = "softDelete";
    },
    openRestoreModal(id) {
      this.isDelete = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_restore_this_subject"
      );
      this.modalData.id = id;
      this.modalData.buttonText = this.trans("lang.modal.yes_restore_it");
      this.modalData.actionType = "Restore";
    },
    multipleArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_archived_this_subject"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_archived_it");
      this.modalData.actionType = "Archive";
    },
    multipleUnArchiveModel() {
      this.modalData = {};
      this.isMultipleModal = true;
      this.modalData.modalText = this.trans(
        "lang.labels.are_you_sure_you_want_to_unarchived_this_subject"
      );
      this.modalData.buttonText = this.trans("lang.modal.yes_unarchived_it");
      this.modalData.actionType = "UnArchive";
    },
    develop(item) {
      this.modalData = {};
      let that = this;
      that.modalData.id = item.id;
      that.isDevelop = true;
      if (item.status == "backburner") {
        that.modalData.heading =
          this.trans("lang.labels.confirm") +
          item.subject +
          this.trans(
            "lang.labels.s_last_reason_was_resolved_before_sending_it_to_develop"
          );
        that.modalData.subHeading = this.trans(
          "lang.labels.was_the_last_reason_resolved"
        );
        that.modalData.is_reason = true;
      } else {
        that.modalData.heading =
          this.trans("lang.labels.confirm_sending") +
          item.subject +
          this.trans("lang.labels.to_develop");
        that.modalData.subHeading = this.trans(
          "lang.labels.this_item_will_be_moved_to_your_develop_list"
        );
      }
      that.modalData.actionType = "develop";
      that.modalData.buttonHeading = this.trans(
        "lang.labels.yes_send_item_to_develop"
      );
      that.modalData.item = item;
    },
    backBurner(item) {
      this.modalData = {};
      let that = this;
      that.modalData.id = item.id;
      that.modalData.heading =
        this.trans("lang.labels.sending") +
        item.subject +
        this.trans("lang.labels.to_backburner");
      that.isDevelop = true;
      that.modalData.actionType = "backburner";
      that.modalData.buttonHeading = this.trans(
        "lang.labels.send_to_backburner"
      );
      that.modalData.is_reason = true;
      that.modalData.item = item;
    },
    draft(item) {
      this.modalData = {};
      let that = this;
      that.modalData.id = item.id;
      that.modalData.heading =
        this.trans("lang.labels.confirm") +
        item.subject +
        this.trans("lang.labels.was_the_last_reason_resolved");
      that.isDevelop = true;
      that.modalData.actionType = "draft";
      that.modalData.buttonHeading = this.trans(
        "lang.labels.yes_send_item_to_draft"
      );
      that.modalData.is_reason = true;
      that.modalData.item = item;
    },
    markComplete(item) {
      this.modalData = {};
      let that = this;
      that.modalData.id = item.id;
      that.modalData.actionType = "ready";
      that.submitIndentify("");
    },
    markInComplete(item) {
      this.modalData = {};
      let that = this;
      that.modalData.id = item.id;
      that.modalData.actionType = "incomplete";
      that.submitIndentify("");
    },
    returnToIdentify(item) {
      this.modalData = {};
      let that = this;
      that.modalData.id = item.id;
      that.modalData.actionType = "identify";
      that.modalData.item = item;
      that.submitIndentify("");
    },
    finalise(item) {
      this.modalData = {};
      let that = this;
      that.modalData.id = item.id;
      that.modalData.actionType = "finalise";
      that.modalData.heading =
        this.trans("lang.labels.sending") +
        item.subject +
        this.trans("lang.labels.for_finalise_&_submit");
      that.isDevelop = true;
      that.modalData.buttonHeading = this.trans(
        "lang.labels.send_to_finalise_&_submit"
      );
      that.modalData.item = item;
    },
    reOpen(item) {
      this.modalData = {};
      let that = this;
      that.modalData.id = item.id;
      that.modalData.actionType = "reOpen";
      that.modalData.heading =
        this.trans("lang.labels.sending_item") +
        item.subject +
        this.trans("lang.labels.for_reopening_and_refinalise");
      that.isDevelop = true;
      that.modalData.buttonHeading = this.trans("lang.labels.send_to_reopen");
      that.modalData.item = item;
    },
    openMoveModule(id) {
      this.isMoveModule = true;
    },
    closeMove() {
      this.isMoveModule = false;
    },
    viewReOpenDescription(item) {
      this.modalData = {};
      let that = this;
      that.modalData.actionType = "reOpen";
      that.modalData.item = item;
      that.isReOpenDescription = true;
    },
    closeReopenDescription() {
      this.isReOpenDescription = false;
    },
    submitIndentify(data) {
      let formData = new FormData();
      formData.append("modelId", this.modalData.id);
      formData.append("destination", this.modalData.actionType);
      if (data.reason) {
        formData.append("reason", data.reason);
      }
      if (data.description) {
        formData.append("reopen_description", data.description);
      }
      axios
        .post("/api/subject-action", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          //Perform Success Action
          if (res.status == 200) {
            this.isCloseDevelop();
            this.createTable();
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            this.appStore.setNotification(notification);
            this.isDevelop = false;
            this.modalData = {};
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          this.errors = error.response.data.errors;
        })
        .finally(() => {
          if (this.$refs.developfunc) {
            this.$refs.developfunc.processingFunc();
          }
          //Perform action in always
        });
    },
    submitMultipleDeleteAction() {
      let that = this;
      let formData = new FormData();
      formData.append("actionType", that.modalData.actionType);
      formData.append("multipleSubject", that.multipleSubject);
      formData.append(
        "actionPage",
        this.is_develop == true ? "develop" : "identify"
      );
      axios
        .post("/api/multiple-subject-action", formData, {
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
            that.isMultipleModal = false;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
          that.errors = error.response.data.errors;
        })
        .finally(() => {
          //Perform action in always
        });
    },
  },
  created() {
    this.createTable();
    this.currentUserRole = this.user.role.name;
  },
  unmounted() {
    clearTimeout(this.searchTimeout);
  },
};
</script>
