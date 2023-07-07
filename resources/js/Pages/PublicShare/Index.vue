<template>
  <div class="grid lg:grid-cols-3 md:grid-cols-1 sm:grid-cols-1">
    <InertiaHead title="Multiply Me" />
   
    <div class="lg:col-span-2 md:col-span-1 sm:col-span-1 sm:hidden md:block">
        <div class="relative w-full h-full themeLogoOverlay">
            <div class="relative w-full h-full bg-slate-50 multiplyMelogin">
                <div class="absolute bottom-8 left-6 z-40 themeLoginLogo">
                    <img src="./../../../img/logo.png" alt="logo">
                    <span class="text-white">Imagine what you could do if you multiply you</span>
                </div>
            </div>
        </div>
    </div>
    <GuestLayout>
      <div class="flex flex-col">
        <div class="mb-24">
            <h3 class="text-gray-400 text-center">"The secret of getting ahead is getting started"</h3>
            <h4 class="text-gray-400 mt-5 text-center">~ Mark Twain</h4>
        </div>

        <div>
            <div class="">
                <h5 class="text-gray-200 text-xl font-medium mb-1">Welcome to MultiplyMe!</h5>
                <p class="text-neutral-400 text-lg mt-3 mb-1">Please Enter Details</p>
            </div>

            <div class="theme-input mt-4">
                <label for="name" class="text-sm text-gray-500">
                  Name
                </label>
                <div class="relative mt-1">
                    <input type="text" id="name"
                        class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                        placeholder="Name" v-model="form.name" />
                </div>
                
            </div>
            <div class="theme-input mt-2">
                <label for="email" class="text-sm text-gray-500">
                  E-Mail address
                </label>
                <div class="relative mt-1">
                    <input type="email" id="email"
                        class="block px-2.5 pb-3 pt-3 w-full text-sm text-neutral-100 bg-sidebar rounded-lg border-1 border-transparent appearance-none dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-500 peer"
                        placeholder="Enter e-mail address" v-model="form.email" />
                </div>
              
            </div>
           
            <div class="block mt-4">
                <button @click="next()"
                    class="bg-btnSubmitBg text-btnSubmitText hover:bg-amber-500 hover:text-white w-full justify-center px-3 py-3 text-center rounded-lg cursor-pointer">
                    Next
                </button>
            </div>
        </div>
      </div>
    </GuestLayout>
  </div>
</template>
<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { validateEmail } from "@/helpers";
import { mapStores } from 'pinia'
import { useAppStore } from "@/store";

export default {
  components: {
    GuestLayout
  },
  props: ['id'],
  data() {
    return {
      form: {
        formId: this.id,
        name: '',
        email:''
      },
      
    }
  },
  methods: {
    next() {
      if(this.form.name.length > 2 && this.form.email.length > 5 && validateEmail(this.form.email)) {
        this.$inertia.visit(route('public.digital-asset.form', this.form));
      } else {
        let notification = {
                            heading: 'Error',
                            subHeading: this.trans('lang.messages.please_fill_valid_details'),
                            type: "error",
                          };
        this.appStore.setNotification(notification);
      }
    },
    
  },
  computed: {
        ...mapStores(useAppStore),
    },
}
</script>
