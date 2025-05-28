<template>
    <v-container>
      <h2>Reset Password</h2>
      <v-form @submit.prevent="submit">
        <input type="hidden" v-model="form.token" />
  
        <v-text-field
          v-model="form.email"
          label="Email"
          type="email"
          :error-messages="form.errors.email"
        />
  
        <v-text-field
          v-model="form.password"
          label="Password"
          type="password"
          :error-messages="form.errors.password"
        />
  
        <v-text-field
          v-model="form.password_confirmation"
          label="Confirm Password"
          type="password"
        />
  
        <v-btn type="submit" color="primary" :loading="form.processing">Reset Password</v-btn>
  
        <div v-if="$page.props.flash?.status" class="mt-3 text-success">
          {{ $page.props.flash.status }}
        </div>
      </v-form>
    </v-container>
  </template>
  
  <script setup>
  import { useForm, usePage } from '@inertiajs/vue3'
  
  const props = defineProps({
    token: String,
    email: String,
  })
  
  const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
  })
  
  const submit = () => {
    form.post(route('password.update'))
  }
  </script>
  