<template>
  <div>

    <InertiaHead title="Email Settings" />
    <AuthenticatedLayout>
      <div class="mb-4">
        <div class="page-header">
          <h4 class="text-3xl text-neutral-300 font-bold">{{ trans("lang.labels.email_settings") }}</h4>
        </div>
      </div>
      <div class="p-0 mt-5 w-full md:w-full lg:w-3/4">
        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4">
          <div class="relative">
            <input type="text" id="host_name"
              class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
              placeholder=" " v-model="emailSettings.host" />
            <label for="host_name"
              class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
              {{ trans("lang.labels.host") }}:
            </label>
          </div>
          <div class="relative">
            <input type="text" id="port_name"
              class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
              placeholder=" " v-model="emailSettings.port" />
            <label for="port_name"
              class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
              {{ trans("lang.labels.port") }}:
            </label>
          </div>
          <div>
            <div class="relative">
              <input type="text" id="user_name"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="emailSettings.username" />
              <label for="user_name"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.username") }}:
              </label>
            </div>
            <label for="user_name" class="text-sm text-gray-500 mt-2">
              {{
                trans("lang.labels.leave_it_blank_if_you_have_configured_your_smtp_server_to_be_authenticated_by_ip_address")
              }}
            </label>
          </div>
          <div>
            <div class="relative">
              <input type="password" id="password"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" " v-model="emailSettings.password" />
              <label for="password"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                {{ trans("lang.labels.password") }}:
              </label>
            </div>
            <label for="password" class="text-sm text-gray-500 mt-2">
              {{
                trans("lang.labels.leave_it_blank_if_you_have_configured_your_smtp_server_to_be_authenticated_by_ip_address")
              }}
            </label>
          </div>
          <div class="relative">
            <input type="text" id="from_email"
              class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
              placeholder=" " v-model="emailSettings.from_address" />
            <label for="from_email"
              class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
              {{ trans("lang.labels.from_address") }}:
            </label>
          </div>
          <div class="relative">
            <input type="text" id="from_name"
              class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
              placeholder=" " v-model="emailSettings.from_name" />
            <label for="from_name"
              class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
              {{ trans("lang.labels.from_name") }}:
            </label>
          </div>
        </div>
        <div class="flex mt-4">
          <div>
            <label for="countries" class="block mb-2 text-sm font-medium dark:text-gray-400">
              {{ trans("lang.labels.encryption") }}
            </label>
            <select id="encryption_name" v-model="emailSettings.encryption"
              class="bg-sidebar border-1 border-transparent text-gray-400 text-sm rounded-lg focus:ring-0 focus:border-amber-500 block w-full p-2.5">
              <option selected>None</option>
              <option value="tls">TLS</option>
              <option value="ssl">SSL</option>
            </select>
          </div>
        </div>
        <div class="flex items-center justify-end mt-4 mb-6">
          <button class="text-sm bg-btnCancelBg text-btnCancelText px-6 py-2 rounded-lg" @click="isTestMail = true">
            {{ trans("lang.labels.test_mail") }}
          </button>
          <button :disabled="emailSettingProcessing"
            class="text-sm bg-btnSubmitBg text-btnSubmitText px-6 py-2 rounded-lg ml-3" @click="updateEmailSetting()">
            {{ trans("lang.modal.update") }}
          </button>
        </div>
        <div class="mb-3">
          <div class="w-full">
            <div
              class="theme-upload-img block sm:flex md:flex items-center justify-between border-2 border-gray-500 border-dashed rounded-lg cursor-pointer bg-sidebar">
              <label for="dropzone-file" class="w-full h-64">
                <div class="flex flex-col h-full items-center justify-center pt-5 pb-6">
                  <div>
                    <div>
                      <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                          viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                          </path>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                          <span class="font-semibold">{{ trans("lang.labels.click_to_upload") }}</span>
                          or drag and drop
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                          SVG, PNG, JPG or GIF (MAX. 800x400px)
                        </p>

                        <input id="dropzone-file" type="file" class="hidden" @change="previewImage" />
                      </div>
                    </div>
                  </div>
                </div>
              </label>
              <template v-if="(setting.emailHeaderImage &&
                setting.emailHeaderImage != '') ||
                preview != null
                ">
                <div class="flex flex-col items-center justify-center w-full xs:w-28 sm:w-30 h-64 p-5">
                  <div class="flex flex-col items-center justify-center pt-5 pb-6 w-full xs:w-28 sm:w-30">
                    <img :src="preview != null
                      ? preview
                      :  mediaPath + '/' + setting.emailHeaderImage
                      " alt="logo" class="h-56 object-cover w-full rounded-lg" />
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4">
          <div class="relative">
            <input type="text" id="head_text" v-model="setting.headerTextSize"
              class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
              placeholder=" " />
            <label for="head_text"
              class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
              {{ trans("lang.labels.header_text_size_px") }}
            </label>
          </div>
          <div class="relative">
            <input type="text" id="body_text" v-model="setting.bodyTextSize"
              class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
              placeholder=" " />
            <label for="body_text"
              class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
              {{ trans("lang.labels.body_text_size_px") }}
            </label>
          </div>
          <div class="relative">
            <input type="text" id="header_text" v-model="setting.headerText"
              class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
              placeholder=" " />
            <label for="header_text"
              class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
              {{ trans("lang.labels.header_text") }}
            </label>
          </div>
          <div class="relative">
            <input type="text" id="footer_text" v-model="setting.footerText"
              class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
              placeholder=" " />
            <label for="footer_text"
              class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
              {{ trans("lang.labels.footer_text") }}
            </label>
          </div>
        </div>
        <div class="flex items-center justify-end mt-4">
          <button :disabled="settingProcessing" @click="updateSetting()"
            class="text-sm bg-btnSubmitBg text-btnSubmitText hover:bg-amber-500 hover:text-white px-6 py-2 rounded-lg">
            {{ trans("lang.modal.update") }}
          </button>
        </div>
        <test-mail @closeTest="closeTestMail()" v-if="isTestMail" :form="emailSettings"></test-mail>
      </div>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TestMail from "@/Components/EmailSettings/TestMail.vue";
import { mapStores, mapState } from "pinia";
import { useAppStore } from "@/store";
export default {
  name: "EmailSettings",
  components: {
    TestMail,
    AuthenticatedLayout,

  },
  computed: {
    ...mapStores(useAppStore),
    ...mapState(useAppStore, [
      'user'
    ])
  },
  data() {
    return {
      emailSettings: {
        host: "",
        port: "",
        username: "",
        password: "",
        from_address: "",
        from_name: "",
        encryption: "",
      },
      setting: {
        headerTextSize: "",
        bodyTextSize: "",
        headerText: "",
        footerText: "",
      },
      settingProcessing: false,
      emailSettingProcessing: false,
      isTestMail: false,
      preview: null,
      image: null,
    };
  },
  watch: {
    isTestMail: function () {
      document.body.style.overflow = this.isTestMail ? 'hidden' : ''
    },
  },
  methods: {
    closeTestMail() {
      this.isTestMail = false;
    },
    emailSetting() {
      axios
        .get(route("app.email-settings.edit"))
        .then((res) => {
          if (res.status == 200) {
            this.emailSettings = res.data.data.smtp_settings;
            this.setting.headerTextSize = res.data.data.email_header_text_size;
            this.setting.bodyTextSize = res.data.data.email_body_text_size;
            this.setting.headerText = res.data.data.email_header_text;
            this.setting.footerText = res.data.data.email_footer_text;
            this.setting.emailHeaderImage = res.data.data.email_header_image;
          }
        })
        .catch((error) => {
          // error.response.status Check status code
        })
        .finally(() => {
          //Perform action in always
        });
    },
    updateEmailSetting() {
      let that = this;
      that.emailSettingProcessing = true;
      axios
        .post(route("app.email-settings.update"), that.emailSettings, {})
        .then((res) => {
          if (res.status == 200) {
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);;
          }
        })
        .catch((error) => {
          that.errors = error.response.data.errors;
        })
        .finally(() => {
          that.emailSettingProcessing = false;
        });
    },
    updateSetting() {
      let that = this;
      that.settingProcessing = true;
      let formData = this.convertJsonToFormData(that.setting);
      formData.append("_method", "PUT")
      if (that.image) {
        formData.append('file', this.image);
      }
      axios
        .post(route("app.email-layout-settings.update"), formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          if (res.status == 200) {
            let notification = {
              heading: "success",
              subHeading: res.data.message,
              type: "success",
            };
            that.appStore.setNotification(notification);
          }
        })
        .catch((error) => {
          that.errors = error.response.data.errors;
        })
        .finally(() => {
          that.settingProcessing = false;
        });
    },
    previewImage: function (event) {
      let input = event.target;
      if (input.files) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.preview = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        this.image = input.files[0];
      }
    },
  },
  created() {
    this.emailSetting();
  },
};
</script>
