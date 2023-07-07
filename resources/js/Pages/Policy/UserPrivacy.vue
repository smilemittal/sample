<template>
    <div>

        <InertiaHead title="Terms & Conditions" />
        <AuthenticatedLayout>
            <div class="mt-2">
                 <div>
                    <h5 class="relative text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">
                        Multiply Me System Privacy Policy
                    </h5>
                    <h5 class="mt-6 relative themeHeading text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">
                        User Facing
                    </h5>
                    <h4 class="mt-10 text-gray-500 text-lg">Version October 27th, March 2023</h4>
                </div>
            </div>
            <div class="mt-8">
                <h5 class="flex items-center text-neutral-400 font-bold text-xl relative themeSubHeading">
                    <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                    Definitions of this document:
                </h5>
                <p class="text-neutral-500 text-base mt-2">The term <span class="font-bold text-amber-500">‘user’</span> is
                    anyone utilising or accessing a Multiply ME
                    Company account other than the account
                    owner.
                    The Multiply ME System online platform can be referred to as MMS or XME, both are correct.</p>
                <p class="text-neutral-100 text-base mt-12">Multiply Me System (MMS) is committed to protect the privacy of
                    our customers. This privacy
                    policy applies <a href="https://www.multiplyme.com.au"
                        class="text-amber-500">www.multiplyme.com.au</a>
                    owned and operated by MMS. This privacy policy
                    describes how MMS collects and uses the personal information you provide on our website:</p>

                <p class="text-neutral-100 text-base mt-8">It also describes the choices available to you regarding our use
                    of your personal information and
                    how you can access and update this information</p>
                <p class="text-neutral-100 text-base mt-3">Things you should know</p>
                <div class="mt-8">
                    <p class="flex text-neutral-400 text-base mt-8">
                        <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                        <span class="w-full">When you register with MMS, information is needed such as your name,
                            organisation
                            name, email address
                        </span>
                    </p>
                    <p class="flex text-neutral-400 text-base mt-8">
                        <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                        <span class="w-full">MMS will not make collected information available to any other parties. We will
                            share
                            your personal information with third parties only in the ways that are described in this
                            privacy policy. We will not sell, rent, exchange or share your personal information with
                            any third-parties without permission
                        </span>
                    </p>
                    <p class="flex text-neutral-400 text-base mt-8">
                        <ChevronDoubleRightIcon class="h-6 w-5 text-amber-500 mr-2" aria-hidden="true" />
                        <span class="w-full">MMS will share the collected information in order to respond to investigation,
                            court
                            orders, legal process, or to investigate, prevent or take action regarding illegal activities,
                            suspected fraud, or situations involving potential threats to the physical safety of any
                            person, violations of MMS terms of service, or as otherwise required by law.
                        </span>
                    </p>
                    <p class="flex text-neutral-400 text-base mt-8">
                        <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                        <span class="w-full">MMS will send a welcome email, and email account users for service-related
                            announcements.
                            You cannot opt out of service related emails as this is part of your service.
                        </span>
                    </p>
                    <p class="flex text-neutral-400 text-base mt-8">
                        <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                        <span class="w-full">MMS may update this Privacy Policy, and will notify account holders of
                            significant
                            changes in the way we treat any personal information by sending a notice to the primary
                            email address specified in your account. We may also place a notice on our site.
                        </span>
                    </p>
                </div>
                <div class="mt-16">
                    <p class="text-neutral-100 text-base mt-6">We encourage you to periodically review this page for the
                        latest information on our privacy practices.</p>

                    <p class="text-neutral-300 text-base mt-4">For questions related to this Privacy Policy please
                        contact MMS by Email at <a href="mailto:hello@multiplyme.com.au" class="text-amber-500">hello@multiplyme.com.au</a></p>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    ChevronDoubleRightIcon
} from '@heroicons/vue/24/solid'
export default {
    components: {
        AuthenticatedLayout,
        
        
        ChevronDoubleRightIcon
    }
};
</script>
  