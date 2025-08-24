<template>
    <AppLayout title="Employees">
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Employees Management</h1>
                <a-button type="primary" @click="showCreateModal = true">
                    <template #icon><PlusOutlined /></template>
                    Add Employee
                </a-button>
            </div>

            <div class="mb-4 flex gap-4">
                <a-input-search
                    v-model:value="searchQuery"
                    placeholder="Search employees..."
                    allow-clear
                    @search="handleSearch"
                    class="max-w-md"
                />
                <a-select
                    v-model:value="selectedCompany"
                    placeholder="Filter by company"
                    allow-clear
                    @change="handleSearch"
                    class="w-64"
                >
                    <a-select-option :value="null">All Companies</a-select-option>
                    <a-select-option v-for="company in companies" :key="company.id" :value="company.id">
                        {{ company.name }}
                    </a-select-option>
                </a-select>
            </div>

            <a-table
                :columns="columns"
                :data-source="employees.data"
                :pagination="pagination"
                :loading="loading"
                @change="handleTableChange"
                :scroll="{ x: 900 }"
            >
                <template #bodyCell="{ column, record, index }">
                    <template v-if="column.key === 'index'">
                        {{ (pagination.current - 1) * pagination.pageSize + index + 1 }}
                    </template>
                    <template v-else-if="column.key === 'full_name'">
                        {{ record.full_name }}
                    </template>
                    <template v-else-if="column.key === 'company'">
                        <a-tag color="blue" @click="showCompanyDetails(record.company)" class="cursor-pointer">
                            {{ record.company?.name || '-' }}
                        </a-tag>
                    </template>
                    <template v-else-if="column.key === 'email'">
                        <a v-if="record.email" :href="`mailto:${record.email}`" class="text-blue-600 hover:underline">
                            {{ record.email }}
                        </a>
                        <span v-else class="text-gray-400">-</span>
                    </template>
                    <template v-else-if="column.key === 'phone'">
                        {{ record.phone || '-' }}
                    </template>
                    <template v-else-if="column.key === 'action'">
                        <a-space>
                            <a-button type="link" size="small" @click="editEmployee(record)">
                                <template #icon><EditOutlined /></template>
                                Edit
                            </a-button>
                            <a-popconfirm
                                title="Are you sure you want to delete this employee?"
                                ok-text="Yes"
                                cancel-text="No"
                                @confirm="deleteEmployee(record.id)"
                            >
                                <a-button type="link" danger size="small">
                                    <template #icon><DeleteOutlined /></template>
                                    Delete
                                </a-button>
                            </a-popconfirm>
                        </a-space>
                    </template>
                </template>
            </a-table>

            <a-modal
                v-model:open="showCreateModal"
                title="Add New Employee"
                :confirm-loading="submitting"
                @ok="handleCreateSubmit"
                @cancel="resetForm"
                width="600px"
            >
                <a-form
                    ref="formRef"
                    :model="formState"
                    :rules="rules"
                    layout="vertical"
                    class="mt-4"
                >
                    <a-form-item label="First Name" name="first_name">
                        <a-input v-model:value="formState.first_name" placeholder="Enter first name" />
                    </a-form-item>

                    <a-form-item label="Last Name" name="last_name">
                        <a-input v-model:value="formState.last_name" placeholder="Enter last name" />
                    </a-form-item>

                    <a-form-item label="Company" name="company_id">
                        <a-select v-model:value="formState.company_id" placeholder="Select company">
                            <a-select-option v-for="company in companies" :key="company.id" :value="company.id">
                                {{ company.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>

                    <a-form-item label="Email" name="email">
                        <a-input v-model:value="formState.email" placeholder="employee@example.com" />
                    </a-form-item>

                    <a-form-item label="Phone" name="phone">
                        <a-input v-model:value="formState.phone" placeholder="+1234567890" />
                    </a-form-item>
                </a-form>
            </a-modal>

            <a-modal
                v-model:open="showEditModal"
                title="Edit Employee"
                :confirm-loading="submitting"
                @ok="handleEditSubmit"
                @cancel="resetForm"
                width="600px"
            >
                <a-form
                    ref="editFormRef"
                    :model="editFormState"
                    :rules="rules"
                    layout="vertical"
                    class="mt-4"
                >
                    <a-form-item label="First Name" name="first_name">
                        <a-input v-model:value="editFormState.first_name" placeholder="Enter first name" />
                    </a-form-item>

                    <a-form-item label="Last Name" name="last_name">
                        <a-input v-model:value="editFormState.last_name" placeholder="Enter last name" />
                    </a-form-item>

                    <a-form-item label="Company" name="company_id">
                        <a-select v-model:value="editFormState.company_id" placeholder="Select company">
                            <a-select-option v-for="company in companies" :key="company.id" :value="company.id">
                                {{ company.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>

                    <a-form-item label="Email" name="email">
                        <a-input v-model:value="editFormState.email" placeholder="employee@example.com" />
                    </a-form-item>

                    <a-form-item label="Phone" name="phone">
                        <a-input v-model:value="editFormState.phone" placeholder="+1234567890" />
                    </a-form-item>
                </a-form>
            </a-modal>

            <a-modal
                v-model:open="showCompanyModal"
                title="Company Details"
                :footer="null"
                width="500px"
            >
                <div v-if="selectedCompanyDetails" class="space-y-4">
                    <div v-if="selectedCompanyDetails.logo_url" class="text-center mb-4">
                        <a-avatar :src="selectedCompanyDetails.logo_url" :size="80" />
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-sm">Company Name</p>
                            <p class="font-medium">{{ selectedCompanyDetails.name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-500 text-sm">Email</p>
                            <p class="font-medium">{{ selectedCompanyDetails.email || '-' }}</p>
                        </div>
                        
                        <div class="col-span-2">
                            <p class="text-gray-500 text-sm">Website</p>
                            <a v-if="selectedCompanyDetails.website" 
                               :href="selectedCompanyDetails.website" 
                               target="_blank" 
                               class="text-blue-600 hover:underline">
                                {{ selectedCompanyDetails.website }}
                            </a>
                            <p v-else class="font-medium">-</p>
                        </div>
                    </div>
                </div>
            </a-modal>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import { 
    PlusOutlined, 
    EditOutlined, 
    DeleteOutlined 
} from '@ant-design/icons-vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Company {
    id: number;
    name: string;
    email: string | null;
    logo: string | null;
    logo_url: string | null;
    website: string | null;
}

interface Employee {
    id: number;
    first_name: string;
    last_name: string;
    full_name: string;
    company_id: number;
    company: Company;
    email: string | null;
    phone: string | null;
}

const props = defineProps<{
    employees: {
        data: Employee[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    companies: Company[];
    filters: {
        search?: string;
        company_id?: number;
    };
}>();

const page = usePage();
const loading = ref(false);
const submitting = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showCompanyModal = ref(false);
const searchQuery = ref(props.filters?.search || '');
const selectedCompany = ref(props.filters?.company_id || null);
const selectedCompanyDetails = ref<Company | null>(null);

// Watch for flash messages
watch(() => page.props.flash, (flash: any) => {
    if (flash?.success) {
        message.success(flash.success);
    }
    if (flash?.error) {
        message.error(flash.error);
    }
}, { immediate: true });

const formRef = ref();
const editFormRef = ref();

const formState = reactive({
    first_name: '',
    last_name: '',
    company_id: null as number | null,
    email: '',
    phone: '',
});

const editFormState = reactive({
    id: null as number | null,
    first_name: '',
    last_name: '',
    company_id: null as number | null,
    email: '',
    phone: '',
});

const rules = {
    first_name: [
        { required: true, message: 'Please enter first name', trigger: 'blur' },
    ],
    last_name: [
        { required: true, message: 'Please enter last name', trigger: 'blur' },
    ],
    company_id: [
        { required: true, message: 'Please select a company', trigger: 'change' },
    ],
    email: [
        { type: 'email', message: 'Please enter valid email', trigger: 'blur' },
    ],
};

const columns = [
    {
        title: '#',
        key: 'index',
        width: 60,
    },
    {
        title: 'Full Name',
        key: 'full_name',
        dataIndex: 'full_name',
    },
    {
        title: 'Company',
        key: 'company',
        dataIndex: 'company',
    },
    {
        title: 'Email',
        key: 'email',
        dataIndex: 'email',
    },
    {
        title: 'Phone',
        key: 'phone',
        dataIndex: 'phone',
    },
    {
        title: 'Action',
        key: 'action',
        width: 150,
    },
];

const pagination = computed(() => ({
    current: props.employees.current_page,
    pageSize: props.employees.per_page,
    total: props.employees.total,
    showSizeChanger: false,
}));

const handleTableChange = (paginationInfo: any) => {
    loading.value = true;
    router.get(route('employees.index'), {
        page: paginationInfo.current,
        search: searchQuery.value,
        company_id: selectedCompany.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};

const handleSearch = () => {
    loading.value = true;
    router.get(route('employees.index'), {
        search: searchQuery.value,
        company_id: selectedCompany.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};

const showCompanyDetails = (company: Company) => {
    selectedCompanyDetails.value = company;
    showCompanyModal.value = true;
};

const handleCreateSubmit = async () => {
    try {
        await formRef.value.validate();
        submitting.value = true;

        router.post(route('employees.store'), formState, {
            preserveScroll: true,
            onSuccess: (page) => {
                showCreateModal.value = false;
                resetForm();
                // Check if email was sent
                const company = props.companies.find(c => c.id === formState.company_id);
                if (company?.email) {
                    message.success({
                        content: 'Employee created and notification email sent to company!',
                        duration: 5,
                    });
                }
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                if (firstError) {
                    message.error(String(firstError));
                }
            },
            onFinish: () => {
                submitting.value = false;
            },
        });
    } catch (error) {
        console.error(error);
    }
};

const editEmployee = (employee: Employee) => {
    editFormState.id = employee.id;
    editFormState.first_name = employee.first_name;
    editFormState.last_name = employee.last_name;
    editFormState.company_id = employee.company_id;
    editFormState.email = employee.email || '';
    editFormState.phone = employee.phone || '';
    showEditModal.value = true;
};

const handleEditSubmit = async () => {
    try {
        await editFormRef.value.validate();
        submitting.value = true;

        router.put(route('employees.update', editFormState.id), {
            first_name: editFormState.first_name,
            last_name: editFormState.last_name,
            company_id: editFormState.company_id,
            email: editFormState.email || null,
            phone: editFormState.phone || null,
        }, {
            onSuccess: () => {
                message.success('Employee updated successfully');
                showEditModal.value = false;
                resetForm();
            },
            onError: () => {
                message.error('Failed to update employee');
            },
            onFinish: () => {
                submitting.value = false;
            },
        });
    } catch (error) {
        console.error(error);
    }
};

const deleteEmployee = (id: number) => {
    router.delete(route('employees.destroy', id), {
        onSuccess: () => {
            message.success('Employee deleted successfully');
        },
        onError: () => {
            message.error('Failed to delete employee');
        },
    });
};

const resetForm = () => {
    formState.first_name = '';
    formState.last_name = '';
    formState.company_id = null;
    formState.email = '';
    formState.phone = '';
    
    editFormState.id = null;
    editFormState.first_name = '';
    editFormState.last_name = '';
    editFormState.company_id = null;
    editFormState.email = '';
    editFormState.phone = '';
    
    formRef.value?.resetFields();
    editFormRef.value?.resetFields();
};
</script>