<template>
  <v-container>
    <v-breadcrumbs :items="['Application', 'Document Management']" />

    <v-container fluid class="py-4">
      <v-card elevation="4" class="pa-4">
        <v-tabs
        v-model="tab"
        bg-color="primary"
        >
            <v-tab value="one">Version 1</v-tab>
            <v-tab value="two">Version 2</v-tab>
        </v-tabs>

        <v-card-text>
            <v-tabs-window v-model="tab">
                <v-tabs-window-item value="one">
                        <v-row>
                            <v-col cols="4" sm="12" md ="4" lg="4">
                                <v-card elevation="5">
                                    <v-card-text>
                                    <form @submit.prevent="submit">
                                        <v-row dense>
                                            
                                            <!-- Patient Combobox -->
                                            <v-col cols="12" md="12">
                                                <v-combobox
                                                v-model="Form.patient"
                                                v-model:search="search"
                                                :items="patients.data"
                                                item-title="full_name"
                                                item-value="id"
                                                label="Select Patient"
                                                clearable
                                                return-object
                                                prepend-icon="mdi-account"
                                                density="compact"
                                                :error-messages="Form.errors.patient">

                                                    <template #append-inner>
                                                        <v-btn
                                                            v-if="!Form.patient"
                                                            icon
                                                            size="small"
                                                            variant="text"
                                                            @click="dialogNewForm = true"
                                                        >
                                                            <v-icon>mdi-account-plus</v-icon>
                                                            <v-tooltip activator="parent" location="top">Add New Patient</v-tooltip>
                                                        </v-btn>
                                                    </template>
                                                </v-combobox>
                                            </v-col>

                                            <!-- Physicians Combobox -->
                                            <v-col cols="12" md="12">
                                                <v-combobox
                                                v-model="Form.physician"
                                                v-model:search="search_physician"
                                                :items="physicians.data"
                                                item-title="full_name"
                                                item-value="id"
                                                label="Select Physician"
                                                clearable
                                                return-object
                                                prepend-icon="mdi-doctor"
                                                density="compact"
                                                chips
                                                multiple
                                                :error-messages="Form.errors.physician">
                                                     <template #append-inner>
                                                        <v-btn
                                                            v-if="!Form.patient"
                                                            icon
                                                            size="small"
                                                            variant="text"
                                                            @click="dialogNewFormPhysician = true"
                                                        >
                                                            <v-icon>mdi-account-plus</v-icon>
                                                            <v-tooltip activator="parent" location="top">Add New Physician</v-tooltip>
                                                        </v-btn>
                                                    </template>
                                                </v-combobox>
                                            </v-col>

                                            <!--Tagg -->
                                            <v-col cols="12" md="12">
                                                <v-text-field
                                                label="Document Tag"
                                                multiple
                                                v-model="Form.tagg"
                                                density="compact"
                                                prepend-icon="mdi-tag-text-outline"
                                                :error-messages="Form.errors.tagg"
                                                />
                                            </v-col>

                                                <!-- Document No -->
                                            <v-col cols="12" md="12">
                                                <v-text-field
                                                label="Document No"
                                                v-model="Form.document_no"
                                                density="compact"
                                                prepend-icon="mdi mdi-file-sign"
                                                :error-messages="Form.errors.document_no"
                                                />
                                            </v-col>


                                            <!-- File -->
                                            <v-col cols="12" md="12">
                                                <v-file-input
                                                label="Attach Files"
                                                multiple
                                                v-model="Form.attachment"
                                                density="compact"
                                                prepend-icon="mdi-paperclip"
                                                :error-messages="Form.errors.attachment"
                                                />
                                            </v-col>

                                            <!--Summary -->
                                            <v-col cols="12" md="12">
                                                <v-textarea
                                                label="Document Summary"
                                                multiple
                                                v-model="Form.summary"
                                                density="compact"
                                                prepend-icon="mdi-tag-text-outline"
                                                />
                                            </v-col>


                                            
                                            
                                            <!-- Submit Button -->
                                            <v-col cols="12" class="d-flex justify-end">
                                                <v-btn
                                                color="green"
                                                @click="HandleStoreSubmit"
                                                prepend-icon="mdi-check"
                                                >
                                                Submit
                                                </v-btn>
                                            </v-col>
                                            
                                        </v-row>
                                        
                                    </form>
                                </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="8" sm="12" md ="8" lg="8">
                                <v-row>
                                    <v-col sm="12" md="9" xsm="12">
                                        <v-text-field
                                        v-model="searchTerm"
                                        label="Search Name"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-table density="compact">
                                    <thead>
                                            <tr>
                                                
                                                <th class="text-center font-weight-bold" width="1%"></th>
                                                <th class="text-center font-weight-bold" width="20%">Document No.</th>
                                                <th class="text-center font-weight-bold" width="20%">Patient No.</th>
                                                <th class="text-center font-weight-bold" width="59%">Name</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="data in datas.data" :key="data.id">
                                            <tr>
                                                <td class="text-center" width="1p%">
                                                    <v-btn-group class="h-75">
                                                        <v-btn icon size="x-small" @click="toggleRow(data.id)">
                                                            <v-icon>{{ expandedRow === data.id ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                                                                <v-tooltip activator="parent" location="top">View More</v-tooltip>
                                                        </v-btn>
                                                    </v-btn-group>
                                                </td>
                                                <td class="text-center">
                                                    {{ data.document_no }}
                                                </td>
                                                <td class="text-center">
                                                    {{ data.patient.patient_no }}
                                                </td>
                                                <td class="text-center">
                                                    {{ data.patient.firstname }}  {{ data.patient.middlename }} {{ data.patient.lastname }} {{ data.patient.suffix }}
                                                </td>
                                                
                                               
                                            </tr>

                                            <!-- Expandable row -->
                                            <tr v-show="expandedRow === data.id">
                                                <td colspan="4">
                                                    <v-expand-transition>
                                                    <div class="p-4 bg-grey-lighten-4 rounded">
                                                        <p class="font-weight-bold">Patient Information :</p>
                                                        <v-row dense>
                                                            <v-col  cols="12">
                                                            <p class="ml-3">
                                                                <span class="font-weight-bold">Name : </span> 
                                                                 {{
                                                                    data.patient.full_name
                                                                    ? data.patient.full_name
                                                                    : `${data.patient.firstname} ${data.patient.middlename} ${data.patient.lastname} ${data.patient.suffix}`
                                                                }}
                                                            </p>
                                                            </v-col>
                                                        </v-row>

                                                        <p class="font-weight-bold mt-5">Physician Tagged :</p>
                                                        <v-row v-for="physician in data.tagged_physicians" :key="physician" dense class="ml-2">
                                                            <v-col cols="6">
                                                                <p>
                                                                    <span class="font-weight-bold">Name : </span> 
                                                                    {{
                                                                        physician.physician.full_name
                                                                        ? physician.physician.full_name
                                                                        : `${physician.physician.firstname} ${physician.physician.middlename} ${physician.physician.lastname} ${physician.physician.suffix}`
                                                                    }}
                                                                </p>
                                                            </v-col>
                                                             <v-col cols="6">
                                                                <p>
                                                                    <span class="font-weight-bold">License No : </span> 
                                                                    {{physician.physician.prc_no}}
                                                                </p>
                                                               
                                                            </v-col>
                                                        </v-row>
                                                        <p class="font-weight-bold mt-5">Files :</p>
                                                        <v-row dense class="ml-2">
                                                        <v-col
                                                            v-for="(file, index) in data.files"
                                                            :key="index"
                                                            cols="12"
                                                            sm="6"
                                                        >
                                                            <v-chip color="green" class="ma-1" @click="downloadFile(file.url)">
                                                            {{ getLastSegmentFromUrl(file.url) }}
                                                            </v-chip>
                                                        </v-col>
                                                        </v-row>

                                                    </div>
                                                    </v-expand-transition>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr v-if="datas.data.length <= 0">
                                            <td colspan="4">
                                                <p class="text-center font-weight-bold mt-5 mb-5">No Record Found! </p>
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
                       
                </v-tabs-window-item> 
                <v-tabs-window-item value="two">
                    <form @submit.prevent="submit">
                        <v-file-input
                        label="Attach Files"
                        multiple
                        v-model="Form.attachment"
                        density="compact"
                        prepend-icon="mdi-paperclip"
                        :error-messages="Form.errors.attachment"
                        />

                        <v-btn
                        color="green"
                        @click="HandleStoreSubmitAI"
                        prepend-icon="mdi-check"
                        >
                        Submit
                        </v-btn>
                    </form>
                </v-tabs-window-item> 
            </v-tabs-window>
        </v-card-text>
      </v-card>
    </v-container>
  </v-container>

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
                    v-model="PatientForm.patient_no"
                    ></v-text-field>

                    <v-text-field
                    label="First Name"
                    v-model="PatientForm.firstname"
                    :error-messages="PatientForm.errors.firstname"
                    ></v-text-field>

                     <v-text-field
                    label="Middle Name"
                    v-model="PatientForm.middlename"
                    ></v-text-field>

                    <v-text-field
                    label="Last Name"
                    v-model="PatientForm.lastname"
                    :error-messages="PatientForm.errors.lastname"
                    ></v-text-field>

                    <v-text-field
                    label="Suffix"
                    v-model="PatientForm.suffix"
                    ></v-text-field>

                    <v-select
                    label="Gender"
                    :items="gender"
                    item-title="title"
                    item-value="value"
                    v-model="PatientForm.gender"
                    ></v-select>

                    <v-btn
                    color="green"
                    @click="HandleStoreSubmitPatient"
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

     <v-dialog
    v-model="dialogNewFormPhysician"
    class="w-50"
    >
        <v-card>
            <v-card-title>Physician Form</v-card-title>
            <v-card-subtitle>Please input the correct and valid data.</v-card-subtitle>
            <v-card-text>
                <form @submit.prevent="submit">
                     <v-text-field
                    label="PRC No"
                    :error-messages="Form.errors.prc_no"
                    v-model="PhysicianForm.prc_no"
                    ></v-text-field>

                    <v-text-field
                    label="First Name"
                    v-model="PhysicianForm.firstname"
                    :error-messages="PhysicianForm.errors.firstname"
                    ></v-text-field>

                     <v-text-field
                    label="Middle Name"
                    v-model="PhysicianForm.middlename"
                    ></v-text-field>

                    <v-text-field
                    label="Last Name"
                    v-model="PhysicianForm.lastname"
                    :error-messages="PhysicianForm.errors.lastname"
                    ></v-text-field>

                    <v-text-field
                    label="Suffix"
                    v-model="PhysicianForm.suffix"
                    ></v-text-field>

                    <v-text-field
                    label="Specialization"
                    v-model="PhysicianForm.specialization"
                    ></v-text-field>


                    <v-btn
                    color="green"
                    @click="HandleStoreSubmitPhysician"
                    >Submit</v-btn>

                    <v-btn
                    class="ml-2"
                    color="red"
                    @click="dialogNewFormPhysician = false"
                    >Cancel</v-btn>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { useAppStore } from "@/stores/appStore.js";
import { ref, watch, defineProps } from 'vue';
import { router, usePage,useForm } from '@inertiajs/vue3';
import debounce from 'lodash/debounce'
const expandedRow = ref(null)

const toggleRow = (id) => {
  expandedRow.value = expandedRow.value === id ? null : id
}
const tab = ref('')
const page = usePage();
const Form = useForm({
    attachment : [],
    patient: null,
    document_no : null,
    tagg : null,
    physician: [],
    summary : null,
});

const patients = ref(page.props.patients)
const physicians = ref(page.props.physicians)
const datas = ref(page.props.data.data)

const search = ref('')
const search_physician = ref('')

const fetchPatients = debounce((val) => {
  router.get('/document', { search: val }, {
    preserveState: true,
    replace: true,
    only: ['patients']
  });
}, 300)

const fetchPhysicians = debounce((val) => {
  router.get('/document', { search_physician: val }, {
    preserveState: true,
    replace: true,
    only: ['physicians']
  });
}, 300)


watch(search,fetchPatients)
watch(search_physician,fetchPhysicians)

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

const HandleStoreSubmitAI = () => {
    Form.post('/document/storeDocumentAiV1',{
        preserveScroll: true,
        forceFormData: true, 
        onSuccess: (success) => {
            Form.reset();
        },
        onError: (error) => {

        }
    })
}

const searchTerm = ref(page.props.searchTerm || '');
const pagination = ref({
    page: page.props.data.current_page,
    pages: page.props.datalast_page,
    total: page.props.data.total,
    to: page.props.data.to
});


const performSearch = () => {
    loadPage(1); 
};

const pageChanged = (newPage) => {
    loadPage(newPage); 
};

const loadPage = (pageNo) => {
    const url = new URL('/document', window.location.origin);
    url.searchParams.set('page', pageNo);
    url.searchParams.set('search_table', searchTerm.value);
    
    router.get(url.toString(), { preserveState: true,});
};

function getLastSegmentFromUrl(url) {
  return url.substring(url.lastIndexOf('/') + 1);
}

const downloadFile = (url) => {
  const allowedExtensions = ['docx', 'pptx', 'xlsx', 'xls', 'pdf'];
  const fileName = url.split('/').pop();
  const extension = fileName.split('.').pop().toLowerCase();

  if (allowedExtensions.includes(extension)) {
    const link = document.createElement('a');
    link.href = url;
    link.download = fileName; // triggers download
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } else {
    alert('Unsupported file type for download.');
  }
};


watch(() => page.props.data, (newData) => {
    console.log("ITO")
    console.log(newData)
    datas.value = newData;
    datas.value.page = newData.current_page;
    datas.value.pages = newData.last_page;
    datas.value.total = newData.total;
    datas.value.to = newData.to;
}, { immediate: true });

watch(searchTerm, () => {
    loadPage(1); 
});


const PatientForm = useForm({
    firstname : null,
    middlename : null,
    lastname : null,
    suffix : null,
    patient_no : null,
    gender : null,
});

const dialogNewForm = ref(false);
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

const HandleStoreSubmitPatient = () => {
    PatientForm.post('/patients/store',{
        preserveScroll: true,
        onSuccess: (success) => {
            PatientForm.reset();
            dialogNewForm.value = false;
            fetchPatients();
        },
        onError: (error) => {

        }
    })
}

const PhysicianForm = useForm({
    id : null,
    firstname : null,
    middlename : null,
    lastname : null,
    suffix : null,
    specialization : null,
    prc_no : null,
});

const dialogNewFormPhysician = ref(false);
const HandleStoreSubmitPhysician = () => {
    PhysicianForm.post('/physicians/store',{
        preserveScroll: true,
        onSuccess: (success) => {
            PhysicianForm.reset();
			dialogNewFormPhysician.value = false;
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