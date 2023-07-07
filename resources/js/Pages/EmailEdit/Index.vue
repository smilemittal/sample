<template>
  <div>
    <InertiaHead title="Email Settings" />
    <AuthenticatedLayout>
      <div class="mb-4">
        <div class="page-header">
          <h4 class="text-3xl text-neutral-300 font-bold">{{ trans("lang.labels.email_settings") }}</h4>
        </div>
      </div>
      <div class="w-full md:w-full lg:w-3/4 p-0 mt-5">
        <span class="font-bold dark:text-gray-400 text-xl"
          >Parameters <span class="text-red-600">{username}</span> {username}
          {subject_name}</span
        >
          <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4">
            <div class="relative">
              <input
                type="text"
                id="host_name"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" "
                v-model="form.subject"
              />
              <label
                for="host_name"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1"
              >
                {{ trans("lang.labels.subject") }}
              </label>
            </div>
            <div class="relative">
              <input
                type="text"
                id="port_name"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                placeholder=" "
                v-model="form.display_order"
              />
              <label
                for="port_name"
                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-sidebar px-2 peer-focus:px-2 peer-focus:text-amber-500 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1"
              >
                {{ trans("lang.labels.display_order") }}
              </label>
            </div>
            <div class="relative col-span-2">
              <ckeditor
                class="identify_desc"
                :editor="editor"
                v-model="form.content"
                :config="editorConfig"
                tag-name="textarea"
              ></ckeditor>
            </div>
          </div>
          <div class="flex mt-4">
            <div>
              <label
                for="enable"
                class="block mb-2 text-sm font-medium dark:text-gray-400"
              >
                {{ trans("lang.labels.enable") }}
              </label>
              <select
                id="enable"
                v-model="form.enable"
                class="bg-sidebar border-1 border-transparent text-gray-400 text-sm rounded-lg focus:ring-0 focus:border-amber-500 block w-full p-2.5"
              >
                <option value="1">Enable</option>
                <option value="0">Disable</option>
              </select>
            </div>
          </div>
          <div class="flex items-center justify-end mt-4 mb-6">
            <button
              class="text-sm bg-btnCancelBg text-btnCancelText px-6 py-2 rounded-lg"
            >
              {{ trans("lang.modal.reset") }}
            </button>
            <button
              :disabled="processing" @click="submitForm()"
              class="text-sm bg-btnSubmitBg text-btnSubmitText px-6 py-2 rounded-lg ml-3"
            >
              {{ trans("lang.modal.save") }}
            </button>
          </div>
      </div>
    </AuthenticatedLayout>
  </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";

export default {
  name: "EmailTemplates",
  props: ["id"],
  components: {
    AuthenticatedLayout,
    
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
      editor: ClassicEditor,
      editorData: "<p>Content of the editor.</p>",
      editorConfig: {
        toolbar: [
          "heading",
          "bold",
          "italic",
          "|",
          "NumberedList",
          "BulletedList",
        ],
      },
    };
  },
  methods: {
    submitForm() {
      let that = this;
      // let formData = that.convertJsonToFormData(that.form);
      axios
        .post(route("app.email-template.update", that.id), that.form)
        .then((res) => {
          if (res.data.status == true) {
            that.$inertia.visit(route("email_template.index"));
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
          that.errors = error.response.data.errors;
        })
        .finally(() => {
          this.processing = false;

          //Perform action in always
        });
    },

    editField() {
      let that = this;
      axios
        .get(route("app.email-template.edit", that.id))
        .then((res) => {
          that.form = res.data.data;
        })
        .catch((error) => {
          that.errors = error.response.data.errors;
        })
        .finally(() => {
          //Perform action in always
        });
    },
  },
  created() {
    this.editField();
  },
  computed: {
        ...mapStores(useAppStore),
    },
};
</script>
