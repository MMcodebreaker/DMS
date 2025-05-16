<template>
	<v-container>
		<v-breadcrumbs :items="['Home','Dashboard']"></v-breadcrumbs>
		<v-container fluid class="py-6">
			<div v-if="hasRole('admin')">
      <p>Admin Panel</p>
    </div>

    <div v-if="hasPermission('edit post')">
      <p>You can edit posts</p>
    </div>
        <v-row dense>
          <!-- Stat Widgets -->
          <v-col cols="12" md="3">
            <v-card class="pa-4" elevation="2">
              <v-icon size="36" color="blue">mdi-account</v-icon>
              <div class="text-h6 mt-2">Users</div>
              <div class="text-h5 font-weight-bold">1,245</div>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card class="pa-4" elevation="2">
              <v-icon size="36" color="green">mdi-currency-usd</v-icon>
              <div class="text-h6 mt-2">Revenue</div>
              <div class="text-h5 font-weight-bold">$24,500</div>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card class="pa-4" elevation="2">
              <v-icon size="36" color="red">mdi-alert-circle</v-icon>
              <div class="text-h6 mt-2">Alerts</div>
              <div class="text-h5 font-weight-bold">5</div>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card class="pa-4" elevation="2">
              <v-icon size="36" color="orange">mdi-timer</v-icon>
              <div class="text-h6 mt-2">Pending Tasks</div>
              <div class="text-h5 font-weight-bold">17</div>
            </v-card>
          </v-col>

          <!-- Larger Widgets -->
          <v-col cols="12" md="6">
            <v-card class="pa-4" elevation="2">
              <v-card-title>Recent Activity</v-card-title>
              <v-divider class="my-2" />
              <v-list density="compact">
                <v-list-item v-for="n in 5" :key="n" title="User logged in" subtitle="5 mins ago" />
              </v-list>
            </v-card>
          </v-col>

          <v-col cols="12" md="6">
            <v-card class="pa-4" elevation="2">
              <v-card-title>System Status</v-card-title>
              <v-divider class="my-2" />
              <v-progress-linear model-value="80" color="green" height="10" class="mb-4" />
              <v-progress-linear model-value="60" color="blue" height="10" />
            </v-card>
          </v-col>
        
        </v-row>
      </v-container>
	</v-container>
</template>

<script setup>
import { useAppStore } from "@/stores/appStore.js";
import { defineProps } from 'vue';
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  current_time: { type: String, required: true },
  breadcrumbs: {
    type: Array,
    default: () => [],
  },
});

const page = usePage()
const user = page.props.auth.user
const hasRole = (role) => user?.roles?.includes(role)
const hasPermission = (perm) => user?.permissions?.includes(perm)

const appStore = useAppStore();
const homeUrl = '/'; 
</script>
