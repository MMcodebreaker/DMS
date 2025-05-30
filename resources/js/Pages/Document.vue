<template>
	<v-container>
		<v-breadcrumbs :items="['Application','Document Management']"></v-breadcrumbs>
		<v-container fluid class="py-6">
            <v-card
            elevation="5"
            >
                <v-card-text>
                    <form @submit.prevent="submit">
                        <v-file-input
                            label="File input"
                            multiple
                            v-model="Form.attachment"
                        ></v-file-input>

                        <v-btn
                        color="green"
                        @click="HandleStoreSubmit"
                        >Submit</v-btn>
                    </form>
                </v-card-text>
            </v-card>
        </v-container>
	</v-container>
</template>

<script setup>
import { useAppStore } from "@/stores/appStore.js";
import { ref, watch, defineProps } from 'vue';
import { router, usePage,useForm } from '@inertiajs/vue3';
const page = usePage();
const props = defineProps({
  current_time: { type: String, required: true },
  breadcrumbs: {
    type: Array,
    default: () => [],
  },
});

const Form = useForm({
    attachment : [],
});

const HandleStoreSubmit = () => {
    Form.post('/document/storeDocumentV1',{
        preserveScroll: true,
        forceFormData: true, 
        onSuccess: (success) => {
            Form.reset();
        },
        onError: (error) => {

        }
    })
}

</script>
<style scoped>
.v-table-wrapper {
    max-height: 400px; 
    overflow-y: auto;
    display: block;
    border: 1px solid #ddd; 
    border-radius: 4px; 
    outline: 1px solid #ccc; 
}

.v-table {
    width: 100%;
    border-collapse: collapse; 
    border: 1px solid #ddd;
}

.v-table thead {
    width: 100%;
    background: #cdcccc;
}

.v-table thead th {
    border-bottom: 1px solid #ddd; 
}

.v-table tbody td, .v-table tbody th {
    border-bottom: 1px solid #ddd; 
}

.v-table tbody tr {
    transition: background-color 0.3s ease;
}

.v-table tbody tr:hover {
    background-color: #f0f0f0; 
    cursor: pointer;
}
</style>