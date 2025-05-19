<template>
	<v-app>
		<v-overlay :model-value="overlay" class="align-center justify-center" persistent>
			<v-progress-circular color="primary" size="64" width="5" indeterminate></v-progress-circular>
		</v-overlay>

		<v-navigation-drawer temporary v-model="drawer" width="300">
			<template v-slot:prepend>
				<v-list>
					<v-list-item
						prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
						:subtitle="user_info?.name"
						:title="user_info?.email"
					/>
				</v-list>
			</template>

			<v-divider />

			<v-list density="compact" nav>
				<v-list-item
					v-for="(item, i) in sidebarItems"
					:key="i"
					link
					:title="item.text"
					@click="() => { drawer = false; $inertia.get(item.url) }"
					:prepend-icon="item.icon"
				/>
			</v-list>

			<template v-slot:append>
				<div class="pa-2 border-t-sm">
					<v-btn
						block
						class="border-md bg-red-darken-4"
						prepend-icon="mdi-exit-to-app"
						size="small"
						@click="logout"
					>Logout</v-btn>
				</div>
			</template>
		</v-navigation-drawer>

		<v-app-bar color="blue" density="compact" scroll-behavior="collapse" height="70" class="position-relative">
			<template v-slot:prepend>
				<div class="position-absolute d-flex left-0">
					<v-app-bar-nav-icon @click.stop="drawer = !drawer" v-if="user_info?.name" />
				</div>
			</template>

			<template #title>
				<div class="d-flex align-center flex-column cursor-pointer lh-sm">
					<v-icon icon="mdi mdi-line-scan" size="large" />
				</div>
			</template>

			<template v-slot:append>
				<div class="position-absolute d-flex right-0">
					<v-btn icon="mdi mdi-logout" @click="logout" v-if="user_info?.name" />
				</div>
			</template>
		</v-app-bar>

		<v-main class="pt-3" v-touch="{ right: () => drawer = true }">
			<slot />
		</v-main>

		<v-footer
			class="flex-column justify-center text-subtitle-2 text-grey-lighten-1 lh-sm"
			style="flex: none; user-select: none; min-height: 20px;"
		/>
	</v-app>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import { storeToRefs } from 'pinia'
import { useAppStore } from '@/Stores/appStore'

const userStore = useAppStore()
const { user_info } = storeToRefs(userStore) // ✅ reactive reference

const props = defineProps({
	routeBack: { type: String, default: null },
	toastification: { type: Array, default: () => [] },
	user_info: { type: Object, default: () => ({}) },
})

const overlay = ref(false)
const drawer = ref(null)

const items = ref([
	{ text: 'Dashboard', url: route('page.dashboard'), icon: 'mdi-home', role_access: [1, 2, 3] },
	{ text: 'Document Management', url: '', icon: 'mdi mdi-printer-wireless', role_access: [1, 2, 3] },
	{ text: 'Patient Management', url: '', icon: 'mdi mdi-account-injury-outline', role_access: [1, 2, 3] },
	{ text: 'Physician Management', url: '', icon: 'mdi mdi-doctor', role_access: [1, 2, 3] },
	{ text: 'System Monitoring', url: '/pulse', icon: 'mdi-monitor-dashboard', role_access: [1] },
	{ text: 'User Management', url: route('page.users'), icon: 'mdi mdi-account-group-outline', role_access: [1] },
])

const toast = useToast()

const showToasts = () => {
	if (props.toastification) {
		props.toastification.forEach((toastData) => {
			switch (toastData.type) {
				case 1:
					toast(toastData.text)
					break
				case 2:
					toast.success(toastData.text)
					break
				case 3:
					toast.info(toastData.text)
					break
				case 4:
					toast.warning(toastData.text)
					break
				case 5:
					toast.error(toastData.text)
					break
			}
		})
	}
}

const logout = () => {
	router.post(route('action.logout'), {}, {
		onSuccess: () => {
			userStore.clearUserInfo() // ✅ this should be defined in the store
			drawer.value = false
		},
		onError: (errors) => {
			console.error('Logout failed', errors)
		}
	})
}

const sidebarItems = computed(() => {
  return items.value.filter((item) =>
    userStore.user_info.role && item.role_access.includes(userStore.user_info.role)
  )
})

onMounted(() => {
	userStore.setUserInfo(props?.user_info) // ✅ store initialization
	const { user_info } = storeToRefs(userStore)
	router.on('start', (event) => {
		overlay.value = true
	})

	router.on('finish', (event) => {
		overlay.value = false
		const props = window?.__inertia?.page?.props
		showToasts(props?.toastification)
	})
})
</script>
