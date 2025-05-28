<template>
	<v-container>
		<v-breadcrumbs :items="['Application','User Management']"></v-breadcrumbs>
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
                      v-if="users.data.length != 0"
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
                           <th class="text-center font-weight-bold">Role</th>
                          <th class="text-center font-weight-bold">Created At</th>
                          <th class="text-center font-weight-bold">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr v-for="data in users.data" :key="data.id">
                          <td class="text-center">
                              {{ data.name }}
                          </td>
                          <td class="text-center">
                            <template v-if="data.role === 1">
                              <v-chip
                              block
                              color="blue"
                              >Administrator</v-chip>
                            </template>
                            <template v-else-if="data.role === 2">
                              <v-chip
                              block
                              color="green"
                              >Physician</v-chip>
                            </template>
                            <template v-else-if="data.role === 3">
                              <v-chip
                              block
                              color="orange"
                              >Staff</v-chip>
                            </template>
                            <template v-else>
                              <v-chip
                              block
                              color="black"
                              >Undefined</v-chip>
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
                      <tr v-if="users.data.length === 0">
                          <td colspan="3">
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
            <v-card-title>User Form</v-card-title>
            <v-card-subtitle>Please input the correct and valid data.</v-card-subtitle>
            <v-card-text>
                <form @submit.prevent="submit">
                    <v-text-field
                    label="Name"
                    v-model="Form.name"
                    ></v-text-field>

                    <v-text-field
                    label="Email"
                    type="email"
                    v-model="Form.email"
                    ></v-text-field>

                    <v-text-field
                    type="password"
                    label="Password"
                    v-model="Form.password"
                    ></v-text-field>

                    <v-select
                    label="Role"
                    clearable
                    v-model="Form.role"
                    :items="user_role"
                    item-title="title"
                    item-value="value"
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
            <v-card-title>User Form</v-card-title>
            <v-card-subtitle>Please input the correct and valid data.</v-card-subtitle>
            <v-card-text>
                <form @submit.prevent="submit">
                    <v-text-field
                    label="Name"
                    v-model="Form.name"
                    ></v-text-field>

                    <v-text-field
                    label="Email"
                    type="email"
                    v-model="Form.email"
                    ></v-text-field>

                    <v-text-field
                    type="password"
                    label="Password"
                    v-model="Form.password"
                    ></v-text-field>

                    <v-select
                    label="Role"
                    clearable
                    v-model="Form.role"
                    :items="user_role"
                    item-title="title"
                    item-value="value"
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

const users = ref(page.props.users);
const user_role = ref([
  {
    title : 'Administrator',
    value : 1,
  },
  {
    title : 'Physician',
    value : 2,
  },
  {
    title : 'Staff',
    value : 3,
  }
])
const searchTerm = ref(page.props.searchTerm || '');
const pagination = ref({
    page: users.value.current_page,
    pages: users.value.last_page,
    total: users.value.total,
    to: users.value.to
});

const performSearch = () => {
    loadPage(1); 
};

const pageChanged = (newPage) => {
    loadPage(newPage); 
};

const loadPage = (pageNo) => {
    const url = new URL('/users', window.location.origin);
    url.searchParams.set('page', pageNo);
    url.searchParams.set('search', searchTerm.value);
    
    router.get(url.toString(), { preserveState: true,});
};


const Form = useForm({
    id : null,
    name : null,
    email : null,
    role : null,
    password : null,
});

const dialogForm = ref(false);
const dialogNewForm = ref(false);

const handleEdit = (index, data) =>{
    dialogForm.value = true;
    Form.id = data.id;
    Form.name = data.name;
    Form.role = data.role;
    Form.email = data.email;
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
    Form.post('/users/delete',{
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
    Form.post('/users/update',{
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
    Form.post('/users/store',{
        preserveScroll: true,
        onSuccess: (success) => {
            Form.reset();
			      dialogNewForm.value = false;
        },
        onError: (error) => {

        }
    })
}



watch(() => page.props.users, (newData) => {
    console.log(newData)
    users.value = newData;
    pagination.value.page = newData.current_page;
    pagination.value.pages = newData.last_page;
    pagination.value.total = newData.total;
    pagination.value.to = newData.to;
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