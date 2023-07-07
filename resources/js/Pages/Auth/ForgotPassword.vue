<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <div class="grid lg:grid-cols-3 md:grid-cols-1 sm:grid-cols-1">
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

            <InertiaHead title="Forgot Password" />

            <div class="mb-4 text-sm text-gray-600">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset
                link that will allow you to choose a new one.
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                        autocomplete="username" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Email Password Reset Link
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    </div>
</template>
