<template>
    <div>

        <InertiaHead title="Terms & Conditions" />
        <AuthenticatedLayout>
            <div class="mt-2">
               <div>
                    <h5 class="relative text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">
                        Multiply Me System Terms and Conditions
                    </h5>
                    <h5 class="mt-6 relative themeHeading text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">
                        User Facing
                    </h5>
                    <h4 class="mt-10 text-gray-500 text-lg">Version October 27th, March 2023</h4>
                </div>
            </div>
            <div class="mt-5">
                <h5 class="flex items-center text-neutral-400 font-bold text-xl relative themeSubHeading mt-12">
                    <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                    Definitions of this document:
                </h5>
                <p class="text-neutral-500 text-base mt-2">The term <span class="font-bold text-amber-500">‘user’</span> is
                    anyone utilising or accessing a Multiply ME
                    Company account other than the account owner.
                    The Multiply ME System online platform can be referred to as MMS or XME, both are correct.</p>
                <h5 class="flex items-center text-neutral-400 font-bold text-xl relative themeSubHeading mt-12">
                    <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                    Account Access:
                </h5>
                <p class="text-neutral-500 text-base mt-2">The user is responsible for keeping their account login and
                    password confidential and should not share it with
                    anyone.</p>
                <h5 class="flex items-center text-neutral-400 font-bold text-xl relative themeSubHeading mt-12">
                    <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                    Intellectual Property:
                </h5>
                <p class="text-neutral-500 text-base mt-2">The user acknowledges that all media files/knowledge viewed or
                    accessed on XME system are the
                    property of the account holder, and any use of the information outside of the account holder's business
                    is strictly prohibited.</p>
                <h5 class="flex items-center text-neutral-400 font-bold text-xl relative themeSubHeading mt-12">
                    <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                    Compliance:
                </h5>
                <p class="text-neutral-500 text-base mt-2">The user must comply with all applicable laws and regulations
                    when using the MMS system, including but not
                    limited to intellectual property, data protection, and privacy laws.
                </p>
                <h5 class="flex items-center text-neutral-400 font-bold text-xl relative themeSubHeading mt-12">
                    <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                    Indemnification:
                </h5>
                <p class="text-neutral-500 text-base mt-2">The user agrees to indemnify the account holder and MMS for any
                    claims, losses, damages, or expenses
                    arising from the user’s use of the MMS system in violation of these terms and conditions or applicable
                    laws and regulations.</p>
                <h5 class="flex items-center text-neutral-400 font-bold text-xl relative themeSubHeading mt-12">
                    <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                    Data Protection:
                </h5>
                <p class="text-neutral-500 text-base mt-2">The user must not upload any personal data to the MMS system
                    unless it is necessary for the training or
                    educational purposes of the account holder. The user must comply with all data protection laws and
                    regulations when
                    uploading personal data to the MMS system.</p>
                <h5 class="flex items-center text-neutral-400 font-bold text-xl relative themeSubHeading mt-12">
                    <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                    System Security:
                </h5>
                <p class="text-neutral-500 text-base mt-2">The user must take all necessary precautions to ensure the
                    security of the MMS system, including not sharing
                    their login credentials, not using weak passwords, and reporting any suspected security breaches or
                    incidents to the account
                    holder.</p>
                <h5 class="flex items-center text-neutral-400 font-bold text-xl relative themeSubHeading mt-12">
                    <ChevronDoubleRightIcon class="h-5 w-5 text-amber-500 mr-2" aria-hidden="true" />
                    Termination:
                </h5>
                <p class="text-neutral-500 text-base mt-2">The account holder reserves the right to terminate a user's
                    access to the MMS system at any time, without notice
                    or liability, for any reason, including but not limited to violation of these terms and conditions or
                    the account holders policies
                    and procedures.</p>

                <p class="text-neutral-300 font-bold text-base mt-16">In short, Please use this account responsibility, be
                    kind to
                    people, don’t comment with nasty words and learn all
                    you can to be better at your craft and just be an amazing human in general.</p>
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
  