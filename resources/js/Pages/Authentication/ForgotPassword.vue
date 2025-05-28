<template>
    <v-container>
      <h2>Forgot Password</h2>
      <v-form @submit.prevent="submit">
        <v-text-field
          v-model="form.email"
          label="Email"
          type="email"
          :error-messages="form.errors.email"
        />
  
        <v-btn type="submit" color="primary" :loading="form.processing">Send Reset Link</v-btn>
  
        <div v-if="$page.props.flash?.status" class="mt-3 text-success">
          {{ $page.props.flash.status }}
        </div>
      </v-form>
    </v-container>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3'
  
  const form = useForm({
    email: '',
  })
  const submit = () => {
    form.post(route('password.email'))
  }
  </script>
  