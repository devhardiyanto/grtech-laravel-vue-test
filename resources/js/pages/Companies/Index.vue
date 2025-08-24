<template>
    <AppLayout title="Companies">
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Companies Management</h1>
                <a-button type="primary" @click="showCreateModal = true">
                    <template #icon><PlusOutlined /></template>
                    Add Company
                </a-button>
            </div>

            <div class="mb-4">
                <a-input-search
                    v-model:value="searchQuery"
                    placeholder="Search companies..."
                    allow-clear
                    @search="handleSearch"
                    class="max-w-md"
                />
            </div>

            <a-table
                :columns="columns"
                :data-source="companies.data"
                :pagination="pagination"
                :loading="loading"
                @change="handleTableChange"
                :scroll="{ x: 800 }"
            >
                <template #bodyCell="{ column, record, index }">
                    <template v-if="column.key === 'index'">
                        {{ (pagination.current - 1) * pagination.pageSize + index + 1 }}
                    </template>
                    <template v-else-if="column.key === 'logo'">
                        <a-avatar v-if="record.logo_url" :src="record.logo_url" :size="40" />
                        <a-avatar v-else :size="40">
                            <template #icon><PictureOutlined /></template>
                        </a-avatar>
                    </template>
                    <template v-else-if="column.key === 'website'">
                        <a v-if="record.website" :href="record.website" target="_blank" class="text-blue-600 hover:underline">
                            {{ record.website }}
                        </a>
                        <span v-else class="text-gray-400">-</span>
                    </template>
                    <template v-else-if="column.key === 'action'">
                        <a-space>
                            <a-button type="link" size="small" @click="editCompany(record)">
                                <template #icon><EditOutlined /></template>
                                Edit
                            </a-button>
                            <a-popconfirm
                                title="Are you sure you want to delete this company?"
                                ok-text="Yes"
                                cancel-text="No"
                                @confirm="deleteCompany(record.id)"
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
                title="Add New Company"
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
                    <a-form-item label="Company Name" name="name">
                        <a-input v-model:value="formState.name" placeholder="Enter company name" />
                    </a-form-item>

                    <a-form-item label="Email" name="email">
                        <a-input v-model:value="formState.email" placeholder="company@example.com" />
                    </a-form-item>

                    <a-form-item label="Website" name="website">
                        <a-input v-model:value="formState.website" placeholder="https://example.com" />
                    </a-form-item>

                    <a-form-item label="Logo" name="logo">
                        <a-upload
                            v-model:file-list="fileList"
                            list-type="picture-card"
                            :max-count="1"
                            :before-upload="beforeUpload"
                            @remove="handleRemove"
                        >
                            <div v-if="fileList.length < 1">
                                <PlusOutlined />
                                <div class="mt-2">Upload Logo</div>
                            </div>
                        </a-upload>
                    </a-form-item>
                </a-form>
            </a-modal>

            <a-modal
                v-model:open="showEditModal"
                title="Edit Company"
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
                    <a-form-item label="Company Name" name="name">
                        <a-input v-model:value="editFormState.name" placeholder="Enter company name" />
                    </a-form-item>

                    <a-form-item label="Email" name="email">
                        <a-input v-model:value="editFormState.email" placeholder="company@example.com" />
                    </a-form-item>

                    <a-form-item label="Website" name="website">
                        <a-input v-model:value="editFormState.website" placeholder="https://example.com" />
                    </a-form-item>

                    <a-form-item label="Logo" name="logo">
                        <div v-if="editFormState.logo_url && !fileList.length" class="mb-4">
                            <img :src="editFormState.logo_url" alt="Current logo" class="h-20 w-20 object-cover rounded" />
                            <p class="text-sm text-gray-500 mt-2">Current logo</p>
                        </div>
                        <a-upload
                            v-model:file-list="fileList"
                            list-type="picture-card"
                            :max-count="1"
                            :before-upload="beforeUpload"
                            @remove="handleRemove"
                        >
                            <div v-if="fileList.length < 1">
                                <PlusOutlined />
                                <div class="mt-2">Change Logo</div>
                            </div>
                        </a-upload>
                    </a-form-item>
                </a-form>
            </a-modal>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, reactive, onMounted, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import type { UploadFile } from 'ant-design-vue';
import { 
    PlusOutlined, 
    EditOutlined, 
    DeleteOutlined,
    PictureOutlined 
} from '@ant-design/icons-vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Company {
    id: number;
    name: string;
    email: string | null;
    logo: string | null;
    logo_url: string | null;
    website: string | null;
    employees_count?: number;
}

const props = defineProps<{
    companies: {
        data: Company[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters: {
        search?: string;
    };
}>();

const page = usePage();
const loading = ref(false);
const submitting = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const searchQuery = ref(props.filters?.search || '');
const fileList = ref<UploadFile[]>([]);

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
    name: '',
    email: '',
    website: '',
    logo: null as File | null,
});

const editFormState = reactive({
    id: null as number | null,
    name: '',
    email: '',
    website: '',
    logo_url: '',
    logo: null as File | null,
});

const rules = {
    name: [
        { required: true, message: 'Please enter company name', trigger: 'blur' },
    ],
    email: [
        { type: 'email', message: 'Please enter valid email', trigger: 'blur' },
    ],
    website: [
        { type: 'url', message: 'Please enter valid URL', trigger: 'blur' },
    ],
};

const columns = [
    {
        title: '#',
        key: 'index',
        width: 60,
    },
    {
        title: 'Logo',
        key: 'logo',
        dataIndex: 'logo',
        width: 80,
    },
    {
        title: 'Name',
        dataIndex: 'name',
        key: 'name',
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
    },
    {
        title: 'Website',
        key: 'website',
        dataIndex: 'website',
    },
    {
        title: 'Action',
        key: 'action',
        width: 150,
    },
];

const pagination = computed(() => ({
    current: props.companies.current_page,
    pageSize: props.companies.per_page,
    total: props.companies.total,
    showSizeChanger: false,
}));

const handleTableChange = (paginationInfo: any) => {
    loading.value = true;
    router.get(route('companies.index'), {
        page: paginationInfo.current,
        search: searchQuery.value,
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
    router.get(route('companies.index'), {
        search: searchQuery.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};

const beforeUpload = (file: UploadFile) => {
    const isImage = file.type?.startsWith('image/');
    if (!isImage) {
        message.error('You can only upload image files!');
        return false;
    }
    const isLt2M = (file.size || 0) / 1024 / 1024 < 2;
    if (!isLt2M) {
        message.error('Image must be smaller than 2MB!');
        return false;
    }
    
    if (showCreateModal.value) {
        formState.logo = file as any;
    } else {
        editFormState.logo = file as any;
    }
    
    return false;
};

const handleRemove = () => {
    formState.logo = null;
    editFormState.logo = null;
};

const handleCreateSubmit = async () => {
    try {
        await formRef.value.validate();
        submitting.value = true;

        const formData = new FormData();
        formData.append('name', formState.name);
        if (formState.email) formData.append('email', formState.email);
        if (formState.website) formData.append('website', formState.website);
        if (formState.logo) formData.append('logo', formState.logo);

        router.post(route('companies.store'), formData, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                showCreateModal.value = false;
                resetForm();
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

const editCompany = (company: Company) => {
    editFormState.id = company.id;
    editFormState.name = company.name;
    editFormState.email = company.email || '';
    editFormState.website = company.website || '';
    editFormState.logo_url = company.logo_url || '';
    editFormState.logo = null;
    fileList.value = [];
    showEditModal.value = true;
};

const handleEditSubmit = async () => {
    try {
        await editFormRef.value.validate();
        submitting.value = true;

        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('name', editFormState.name);
        if (editFormState.email) formData.append('email', editFormState.email);
        if (editFormState.website) formData.append('website', editFormState.website);
        if (editFormState.logo) formData.append('logo', editFormState.logo);

        router.post(route('companies.update', editFormState.id), formData, {
            forceFormData: true,
            onSuccess: () => {
                message.success('Company updated successfully');
                showEditModal.value = false;
                resetForm();
            },
            onError: () => {
                message.error('Failed to update company');
            },
            onFinish: () => {
                submitting.value = false;
            },
        });
    } catch (error) {
        console.error(error);
    }
};

const deleteCompany = (id: number) => {
    router.delete(route('companies.destroy', id), {
        onSuccess: () => {
            message.success('Company deleted successfully');
        },
        onError: () => {
            message.error('Failed to delete company');
        },
    });
};

const resetForm = () => {
    formState.name = '';
    formState.email = '';
    formState.website = '';
    formState.logo = null;
    
    editFormState.id = null;
    editFormState.name = '';
    editFormState.email = '';
    editFormState.website = '';
    editFormState.logo_url = '';
    editFormState.logo = null;
    
    fileList.value = [];
    formRef.value?.resetFields();
    editFormRef.value?.resetFields();
};
</script>