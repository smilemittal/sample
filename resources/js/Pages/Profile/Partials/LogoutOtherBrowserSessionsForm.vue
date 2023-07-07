<template>

    <div class="modalNew px-4 py-5 bg-sidebar sm:p-6 mt-5 shadow sm:rounded-t-md">

      <!--title-->
      <div class="md:col-span-1 flex justify-between">
        <div class="px-4 sm:px-0">
          <h3 class="text-lg font-medium text-neutral-200">
            {{ trans('lang.labels.browser_session') }}
          </h3>

          <p class="mt-1 text-sm text-neutral-400">
            {{ trans('lang.labels.manage_logout') }}
          </p>
        </div>
      </div>
      <!--title-->

      <!--content-->
      <div class="mt-5 grid grid-cols-1">
        <div class="">

          <div class="max-w-xl text-sm text-neutral-400">
            {{ trans('lang.labels.necessary') }}
          </div>

          <!-- Other Browser Sessions -->
          <div class="mt-5 space-y-6" v-if="sessions.length > 0">
            <div class="flex items-center" v-for="(session, i) in sessions" :key="i">
              <div>
                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-gray-500" v-if="session.agent.is_desktop">
                  <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-500" v-else>
                  <path d="M0 0h24v24H0z" stroke="none"></path><rect x="7" y="4" width="10" height="16" rx="1"></rect><path d="M11 5h2M12 17v.01"></path>
                </svg>
              </div>

              <div class="ml-3">
                <div class="text-sm text-neutral-400">
                  {{ session.agent.platform }} - {{ session.agent.browser }}
                </div>

                <div>
                  <div class="text-xs text-neutral-400">
                    {{ session.ip_address }},

                    <span class="text-btnSubmitText font-semibold" v-if="session.is_current_device">This device</span>
                    <span v-else>Last active {{ session.last_active }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!--content-->

    </div>

    <div
        class="flex items-center justify-end px-4 py-3 mb-5 bg-neutral-900 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md"
    >
        <!-- ACTIONS -->
        <div class="flex items-center justify-end">
          <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-900 border border-transparent rounded-md font-semibold text-white text-xs uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" @click="confirmLogout">
            {{ trans('lang.labels.other_browser') }}
          </button>
            <div class="ml-3">
              <transition leave-active-class="transition ease-in duration-1000" leave-from-class="opacity-100" leave-to-class="opacity-0">
                  <div v-show="form.recentlySuccessful" class="text-sm text-gray-600">
                    {{ trans('lang.labels.done') }}.
                  </div>
              </transition>
          </div>
         </div>

          <!-- Log Out Other Devices Confirmation Modal -->
          <jet-dialog-modal class="mymodal" :show="confirmingLogout" @close="closeModal">
            <template #title>
              {{ trans('lang.labels.other_browser') }}
            </template>

            <template #content>
             <p class="text-gray-300">{{ trans('lang.labels.password_confirm') }}</p> 
              <div class="mt-4">
                <input type="password" class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500" :placeholder="trans('lang.labels.password')"
                           ref="password"
                           v-model="form.password"
                           @keyup.enter="logoutOtherBrowserSessions" />
                <div v-show="form.errors.password">
                    <p class="text-sm text-red-600">
                        {{ typeof(form.errors.password) == 'object' ? form.errors.password[Object.keys(form.errors.password)[0]] : form.errors.password }}
                    </p>
                </div>
              </div>
            </template>

            <template #footer>
              <button type="button" class="text-btnCancelText bg-btnCancelBg font-medium rounded-lg text-sm px-5 py-2.5 text-center" @click="closeModal">
                {{ trans('lang.labels.cancel') }}
              </button>
             <button type="submit" class="ml-2 inline-flex items-center px-4 py-2 bg-primary-900 border border-transparent rounded-md font-semibold text-white text-xs uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" @click="logoutOtherBrowserSessions" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ trans('lang.labels.other_browser') }}
              </button>
            </template>
          </jet-dialog-modal>
        <!-- ACTIONS -->
    </div>
  </template>

  <script>
      import JetActionSection from '@/Common/ActionSection.vue'
      import JetDialogModal from '@/Common/DialogModal.vue'
         export default {
          props: ['sessions'],

          components: {
              JetActionSection,
              JetDialogModal,
          },

          data() {
              return {
                  confirmingLogout: false,

                  form: this.$inertia.form({
                      password: '',
                  })
              }
          },

          methods: {
              confirmLogout() {
                  this.confirmingLogout = true

                  setTimeout(() => this.$refs.password.focus(), 250)
              },

              logoutOtherBrowserSessions() {
                  this.form.delete(route('other-browser-sessions.destroy'), {
                      preserveScroll: true,
                      onSuccess: () => this.closeModal(),
                      onError: () => this.$refs.password.focus(),
                      onFinish: () => this.form.reset(),
                  })
              },

              closeModal() {
                  this.confirmingLogout = false

                  this.form.reset()
              },
          },
      }
  </script>
