<template>
	<v-container>
		<v-breadcrumbs :items="['Application','Patient Management']"></v-breadcrumbs>
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
                      v-if="patients.data.length != 0"
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
                            <th class="text-center font-weight-bold">Patient No.</th>
                            <th class="text-center font-weight-bold">First Name</th>
                            <th class="text-center font-weight-bold">Middle Name</th>
                            <th class="text-center font-weight-bold">Last Name</th>
                            <th class="text-center font-weight-bold">Suffix</th>
                            <th class="text-center font-weight-bold">Created At</th>
                            <th class="text-center font-weight-bold">Action</th>
                        </tr>
                  </thead>
                  <tbody>
                      <tr v-for="data in patients.data" :key="data.id">
                          <td class="text-center">
                            {{ data.patient_no }}
                          </td>
                          <td class="text-center">
                            {{ data.firstname }}
                          </td>
                          <td class="text-center">
                            {{ data.middlename }}
                          </td>
                          <td class="text-center">
                            {{ data.lastname }}
                          </td>
                          <td class="text-center">
                            {{ data.suffix }}
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
                      <tr v-if="patients.data.length === 0">
                          <td colspan="7">
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
            <v-card-title>Patient Form</v-card-title>
            <v-card-subtitle>Please input the correct and valid data.</v-card-subtitle>
            <v-card-text>
                <form @submit.prevent="submit">
                    <v-text-field
                    label="Patient No"
                    :error-messages="Form.errors.patient_no"
                    v-model="Form.patient_no"
                    ></v-text-field>

                    <v-text-field
                    label="First Name"
                    v-model="Form.firstname"
                    :error-messages="Form.errors.firstname"
                    ></v-text-field>

                     <v-text-field
                    label="Middle Name"
                    v-model="Form.middlename"
                    ></v-text-field>

                    <v-text-field
                    label="Last Name"
                    v-model="Form.lastname"
                    :error-messages="Form.errors.lastname"
                    ></v-text-field>

                    <v-text-field
                    label="Suffix"
                    v-model="Form.suffix"
                    ></v-text-field>

                    <v-select
                    label="Gender"
                    :items="gender"
                    item-title="title"
                    item-value="value"
                    v-model="Form.gender"
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
            <v-card-title>Patient Form</v-card-title>
            <v-card-subtitle>Please input the correct and valid data.</v-card-subtitle>
            <v-card-text>
                <form @submit.prevent="submit">
                     <v-text-field
                    label="Patient No"
                    :error-messages="Form.errors.patient_no"
                    v-model="Form.patient_no"
                    ></v-text-field>

                    <v-text-field
                    label="First Name"
                    v-model="Form.firstname"
                    :error-messages="Form.errors.firstname"
                    ></v-text-field>

                     <v-text-field
                    label="Middle Name"
                    v-model="Form.middlename"
                    ></v-text-field>

                    <v-text-field
                    label="Last Name"
                    v-model="Form.lastname"
                    :error-messages="Form.errors.lastname"
                    ></v-text-field>

                    <v-text-field
                    label="Suffix"
                    v-model="Form.suffix"
                    ></v-text-field>

                    <v-select
                    label="Gender"
                    :items="gender"
                    item-title="title"
                    item-value="value"
                    v-model="Form.gender"
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

const patients = ref(page.props.patients);


const searchTerm = ref(page.props.searchTerm || '');
const pagination = ref({
    page: patients.value.current_page,
    pages: patients.value.last_page,
    total: patients.value.total,
    to: patients.value.to
});

const performSearch = () => {
    loadPage(1); 
};

const pageChanged = (newPage) => {
    loadPage(newPage); 
};

const loadPage = (pageNo) => {
    const url = new URL('/patients', window.location.origin);
    url.searchParams.set('page', pageNo);
    url.searchParams.set('search', searchTerm.value);
    
    router.get(url.toString(), { preserveState: true,});
};

const gender = ref([
    {
        title : "Male",
        value : 1
    },
    {
        title : "Female",
        value : 2
    }
])


const Form = useForm({
    id : null,
    firstname : null,
    middlename : null,
    lastname : null,
    suffix : null,
    patient_no : null,
    gender : null,
});

const dialogForm = ref(false);
const dialogNewForm = ref(false);

const handleEdit = (index, data) =>{
    dialogForm.value = true;
    Form.id = data.id;
    Form.firstname = data.firstname;
    Form.middlename = data.middlename;
    Form.lastname = data.lastname;
    Form.suffix = data.suffix;
    Form.patient_no = data.patient_no;
    Form.gender = data.gender;
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
    Form.post('/patients/delete',{
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
    Form.post('/patients/update',{
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
    Form.post('/patients/store',{
        preserveScroll: true,
        onSuccess: (success) => {
            Form.reset();
			      dialogNewForm.value = false;
        },
        onError: (error) => {

        }
    })
}



watch(() => page.props.patients , (newData) => {
    console.log(newData)
    patients.value = newData;
    patients.value.page = newData.current_page;
    patients.value.pages = newData.last_page;
    patients.value.total = newData.total;
    patients.value.to = newData.to;
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