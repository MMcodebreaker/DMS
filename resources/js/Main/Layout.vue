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
					:subtitle="user_info.name"
					:title="user_info.email"
					></v-list-item>
				</v-list>
			</template>
			<v-divider/>

			<v-list density="compact" nav>
				<v-list-item
					v-for="(item, i) in items" :key="i" link :title="item.text"
					@click="() => { drawer = false; $inertia.get(item.url) }"
					:prepend-icon="item.icon"
				/>
			</v-list>

			<template v-slot:append>
				<div class="pa-2 border-t-sm">
					<v-btn
						block class="border-md bg-red-darken-4" prepend-icon="mdi-exit-to-app"
						size="small" 
						@click="logout"
					>Logout</v-btn>
				</div>
			</template>
		</v-navigation-drawer>

		<v-app-bar color="blue" density="compact" scroll-behavior="collapse" height="70" class="position-relative">
			<template v-slot:prepend>
				<div class="position-absolute d-flex left-0">
					<v-app-bar-nav-icon @click.stop="drawer = !drawer"  v-if="routeBack" />
				</div>
			</template>
			<template #title>
				<div class="d-flex align-center flex-column cursor-pointer lh-sm" >
					<v-icon icon="mdi mdi-line-scan" size="large"/>
				</div>
			</template>
			<template v-slot:append>
				<div class="position-absolute d-flex right-0">
					<v-btn icon="mdi mdi-logout" @click="logout" v-if="routeBack" ></v-btn>
				</div>
			</template>
		</v-app-bar>

		<v-main class="pt-3" v-touch="{right: () => drawer = true, }">
			<slot/>
		</v-main>

		<v-footer
			class="flex-column justify-center text-subtitle-2 text-grey-lighten-1 lh-sm"
			style="flex: none; user-select: none; min-height: 20px;"
		>
		</v-footer>
	</v-app>
</template>
<script setup>
import { onMounted, ref, nextTick } from 'vue'
import { router, usePoll } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import { usePage } from '@inertiajs/vue3'

// Props
const props = defineProps({
  routeBack: { type: String, default: null },
  toastification: { type: Array, default: () => [] },
  user_info: { type: Array, default: () => [] },
})

// State
const overlay = ref(false)
const drawer = ref(null)
const items = ref([
  { text: 'Dashboard', url: route('page.dashboard'), icon: 'mdi-home' },
  { text: 'System Monitoring', url:  '/pulse', icon: ' mdi-monitor-dashboard' },
])

const toast = useToast()

// Methods
function showToasts() {
  if (props.toastification) {
    props.toastification.forEach((toastData) => {
      console.log(toastData)
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
		},
		onError: (errors) => {
			console.error('Logout failed', errors);
		}
	});

	drawer.value = false;
}

const user_info = ref({})



// Lifecycle
onMounted(() => {
 user_info.value = props?.user_info
  router.on('start', (event) => {
    console.log(`Starting a visit to ${event.detail.visit.url}`)
    overlay.value = true
  })



  router.on('finish', (event) => {
    console.log(`Finish a visit to ${event.detail.visit.url}`)
    overlay.value = false
	const props = window?.__inertia?.page?.props
	showToasts(props?.toastification)
  })

  

})
</script>

