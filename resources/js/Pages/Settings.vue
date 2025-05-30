<template>
	<v-container>
		<v-breadcrumbs :items="['Application','Setting Management']"></v-breadcrumbs>
		<v-container fluid class="py-6">
        <v-row no-gutter>
          <v-col sm="12" md="12">
              <v-row>
                  <v-col sm="12" md="9" xsm="12">
                      <v-text-field
                      v-model="searchTerm"
                      label="Search Name"
                      ></v-text-field>
                  </v-col>
                  <v-col sm="12" md="3">
                      <v-btn 
                      v-if="settings.data.length != 0"
                      @click="handleNew"
                      block
                      class="mt-2 mb-3"
                      color="blue">
                      Create new record
                      </v-btn>
                  </v-col>
              </v-row>
          </v-col>
          <v-col sm="12" md="12">
              <v-table density="compact">
                  <thead>
                        <tr>
                            <th class="text-center font-weight-bold">Name</th>
                            <th class="text-center font-weight-bold">Value</th>
                            <th class="text-center font-weight-bold">Status</th>
                            <th class="text-center font-weight-bold">Created At</th>
                            <th class="text-center font-weight-bold">Action</th>
                        </tr>
                  </thead>
                  <tbody>
                      <tr v-for="data in settings.data" :key="data.id">
                          <td class="text-center">
                            {{ data.name }}
                          </td>
                          <td class="text-center">
                            {{ data.value }}
                          </td>
                          <td class="text-center">
                            <template v-if="data.status === 1">
                                <v-chip color="green">Enabled</v-chip>
                            </template>
                             <template v-else>
                                <v-chip color="red">Disbaled</v-chip>
                            </template>
                          </td>
                          <td class="text-center">
                            {{ data.created_at }}
                          </td>
                          <td class="text-center">
                              <v-btn-group class="h-75">
                                  <v-btn color="green" size="x-small" @click="handleEdit(data.id, data)">
                                      <v-icon>mdi-pencil</v-icon>
                                      <v-tooltip activator="parent" location="top">Edit</v-tooltip>
                                  </v-btn>
                                  <v-btn color="red" size="x-small" @click="handleDelete(data.id)">
                                      <v-icon>mdi mdi-delete-outline</v-icon>
                                      <v-tooltip activator="parent" location="top">Delete</v-tooltip>
                                  </v-btn>
                              </v-btn-group>
                          </td>
                      </tr>
                      <tr v-if="settings.data.length === 0">
                          <td colspan="5">
                              <p class="text-center font-weight-bold mt-5">No Record Found! </p>
                              <p class="text-center">Do you want to create new record?</p>
                              <p class="text-center">
                                  <v-btn 
                                  @click="handleNew"
                                  class="mt-2 mb-3"
                                  color="blue">
                                  Create new record
                                  </v-btn>
                              </p>
                          </td>
                      </tr>
                  </tbody>
              </v-table>
              <v-row class="mt-2">
                  <v-col>
                      <p class="mt-5 font-italic">Showing <b>{{ pagination.to }}</b> of <b>{{ pagination.total }}</b> System Records</p>
                  </v-col>
                  <v-col>
                      <v-pagination
                      :total-visible="7"
                      v-model="pagination.page"
                      :length="pagination.pages"
                      @update:modelValue="pageChanged"
                      >
                      </v-pagination>
                  </v-col>
              </v-row>
          </v-col>
      </v-row>
    </v-container>

    <!---Delete Dialog-->
    <v-dialog
    v-model="dialogDelete"
    max-width="500"
    >
        <v-card>
            <v-card-title>System Said!</v-card-title>
            <v-card-text>
                Are you sure, Do you want to delete this ?
            </v-card-text>
            <template v-slot:actions>
                <v-spacer></v-spacer>
                <v-btn
                color="green"
                @click="Delete"
                >Yes, Proceed</v-btn>
                <v-btn
                color="red"
                @click="dialogDelete = false"
                >Cancel</v-btn>
            </template>
        </v-card>
    </v-dialog>


    <!---Edit Dialog-->
    <v-dialog
    v-model="dialogForm"
    class="w-50"
    >
        <v-card>
            <v-card-title>Setting Form</v-card-title>
            <v-card-subtitle>Please input the correct and valid data.</v-card-subtitle>
            <v-card-text>
                <form @submit.prevent="submit">
                    <v-text-field
                    label="Name"
                    :error-messages="Form.errors.name"
                    v-model="Form.name"
                    ></v-text-field>

                    <v-text-field
                    label="Value"
                    v-model="Form.value"
                    :error-messages="Form.errors.value"
                    ></v-text-field>

                    <v-select
                    label="Status"
                    :items="statuses"
                    item-title="title"
                    item-value="value"
                    v-model="Form.status"
                    ></v-select>

                    <v-btn
                    color="green"
                    @click="HandleUpdateSubmit"
                    >Submit</v-btn>

                    <v-btn
                    class="ml-2"
                    color="red"
                    @click="dialogForm = false"
                    >Cancel</v-btn>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
    
    <!--Create New Dialog-->
    <v-dialog
    v-model="dialogNewForm"
    class="w-50"
    >
        <v-card>
            <v-card-title>Setting Form</v-card-title>
            <v-card-subtitle>Please input the correct and valid data.</v-card-subtitle>
            <v-card-text>
                <form @submit.prevent="submit">
                    <v-text-field
                    label="Name"
                    :error-messages="Form.errors.name"
                    v-model="Form.name"
                    ></v-text-field>

                    <v-text-field
                    label="Value"
                    v-model="Form.value"
                    :error-messages="Form.errors.value"
                    ></v-text-field>

                    <v-select
                    label="Status"
                    :items="statuses"
                    item-title="title"
                    item-value="value"
                    v-model="Form.status"
                    ></v-select>


                    <v-btn
                    color="green"
                    @click="HandleStoreSubmit"
                    >Submit</v-btn>

                    <v-btn
                    class="ml-2"
                    color="red"
                    @click="dialogNewForm = false"
                    >Cancel</v-btn>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
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

const settings = ref(page.props.settings);

const statuses = ref([
    {
        title : "Enabled",
        value : 1
    },
     {
        title : "Disbaled",
        value : 0
    }
])

const searchTerm = ref(page.props.searchTerm || '');
const pagination = ref({
    page: settings.value.current_page,
    pages: settings.value.last_page,
    total: settings.value.total,
    to: settings.value.to
});

const performSearch = () => {
    loadPage(1); 
};

const pageChanged = (newPage) => {
    loadPage(newPage); 
};

const loadPage = (pageNo) => {
    const url = new URL('/settings', window.location.origin);
    url.searchParams.set('page', pageNo);
    url.searchParams.set('search', searchTerm.value);
    
    router.get(url.toString(), { preserveState: true,});
};


const Form = useForm({
    id : null,
    name : null,
    value : null,
    status : null,
});

const dialogForm = ref(false);
const dialogNewForm = ref(false);

const handleEdit = (index, data) =>{
    dialogForm.value = true;
    Form.id = data.id;
    Form.name = data.name;
    Form.value = data.value;
    Form.status = data.status;
}

const handleNew = () =>{
    dialogNewForm.value = true;
    Form.reset(); 
}

const dialogDelete = ref(false);
const handleDelete = (id) => {
    dialogDelete.value = true;
    Form.id = id;
}



const Delete = () => {
    Form.post('/settings/delete',{
        preserveScroll: true,
        onSuccess: (success) => {
            Form.reset();
			      dialogDelete.value = false;
        },
        onError: (error) => {

        }
    })
}


const HandleUpdateSubmit = () => {
    Form.post('/settings/update',{
        preserveScroll: true,
        onSuccess: (success) => {
            Form.reset();
			      dialogForm.value = false;
        },
        onError: (error) => {

        }
    })
}

const HandleStoreSubmit = () => {
    Form.post('/settings/store',{
        preserveScroll: true,
        onSuccess: (success) => {
            Form.reset();
			      dialogNewForm.value = false;
        },
        onError: (error) => {
            console.log(error)
        }
    })
}



watch(() => page.props.settings, (newData) => {
    console.log(newData)
    settings.value = newData;
    settings.value.page = newData.current_page;
    settings.value.pages = newData.last_page;
    settings.value.total = newData.total;
    settings.value.to = newData.to;
}, { immediate: true });

watch(searchTerm, () => {
    loadPage(1); 
});
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