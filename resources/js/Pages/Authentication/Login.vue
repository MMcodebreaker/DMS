<template>
    <div>
        <v-card
        class="mx-auto pa-12 pb-8 mt-10"
        elevation="8"
        min-width="250"
        max-width="448"
        rounded="lg"
        >
            <div  class="mb-4 font-medium text-sm text-red">
                {{ form.errors.status }}
            </div>
            <form @submit.prevent="submit">
            
            <div class="text-subtitle-1 text-medium-emphasis font-weight-bold">Email</div>

            <v-text-field
                density="compact"
                placeholder="Enter your email"
                v-model="form.email"
                prepend-inner-icon="mdi-account-box-outline"
                variant="outlined"
            ></v-text-field>
            <p class="mt-n5  mb-2 text-red text-subtitle-2"> {{ form.errors.email }}</p>

            <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between font-weight-bold">
                Password
            </div>

            <v-text-field
                :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                :type="visible ? 'text' : 'password'"
                density="compact"
                v-model="form.password"
                placeholder="Enter your password"
                prepend-inner-icon="mdi-lock-outline"
                variant="outlined"
                @click:append-inner="visible = !visible"
            ></v-text-field>
            <p class="mt-n5  mb-2 text-red text-subtitle-2"> {{ form.errors.password }}</p>

        

            <v-btn
                color="blue"
                size="large"
                block
            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
            type="submit"
            >
                Log In
            </v-btn>

            <a
            class="text-caption text-decoration-none text-blue mb-3"
            href="#"
            rel="noopener noreferrer"
            target="_blank"
            >
            Forgot login password?</a>
            </form>
        </v-card>
  </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted,computed } from 'vue';
import axios from 'axios';
import { router, usePage,useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});


const visible = ref(false)
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('action.login'), {
        onFinish: () => form.reset('password'),
    });
    
};


onMounted(() => {
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function () {
        window.history.go(1);
    };
})
</script>