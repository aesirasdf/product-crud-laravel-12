<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { onMounted, onUpdated, ref } from 'vue'
import DataTable from 'primevue/datatable'
import Paginator from 'primevue/paginator'
import Column from 'primevue/column'
import { ProductIndex } from '../../api/products'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'

const breadcrumbs = [
    {
        title: 'Products',
        href: '/products',
    },
];

const props = defineProps({
    products: Array,
    others: Object,
})

const products = ref([...props.products])
const others = ref({...props.others})
const deleteDialog = ref()



const refreshPage = (v) => {
    if(+v?.page){
        others.value.page = +v.page + 1
    }else{
        v.preventDefault()
    }
    ProductIndex({
        page: others.value.page,
        limit: others.value.limit,
        search_params: others.value.search_params,
        category: others.value.category
    }).then(res => {
        if(res?.ok){
            products.value = res.data
            others.value = res.others
        }
    })

}

const deleteConfirm = (e) => {
    router.delete(`/products/${deleteDialog.value}`)
    deleteDialog.value = null
    refreshPage(e)
}



</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-10">
            <DataTable :value="products">
                <template #header>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex gap-2 items-center">
                            <span class="text-xl font-bold">Products</span>
                            <Button label="+" size="small" class="text-5xl rounded-full" />
                        </div>
                        <div>
                            <form v-on:submit="refreshPage" class="flex gap-2">
                                <Select optionValue="category" v-model="others.category" :options="[{category: ''}, ...others.categories]" optionLabel="category" placeholder="Select a category"  />
                                <InputText v-model="others.search_params" placeholder="Search..." />
                                <Button type="submit">Filter</Button>
                            </form>
                        </div>
                    </div>
                </template>
                <Column field="id" header="ID"></Column>
                <Column field="name" header="Name"></Column>
                <Column field="category" header="Category"></Column>
                <Column field="description" header="Description"></Column>
                <Column field="date_time" header="Date and Time"></Column>
                <Column header="Actions">
                    <template #body="slotProps">
                        <div class="flex gap-2">
                            <Button severity="warn" label="Edit" />
                            <Button @click="deleteDialog = slotProps.data.id" severity="danger" label="Delete" />
                        </div>
                    </template>
                </Column>
                <template #footer>
                    <div class="flex justify-between items-center">
                        <div>
                            Page {{ others.page }}
                        </div>
                        <Paginator v-on:page="refreshPage" :rows="+others.limit" :totalRecords="others.total" template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink" />
                        <div>
                            Showing {{ products.length }} of {{ others.total }}
                        </div>
                    </div>
                </template>
            </DataTable>
        </div>
        <Dialog @update:visible="deleteDialog = null" :visible="!!deleteDialog" modal header="Delete Confirmation">
                Are you sure you want to delete this product?
                <template #footer>
                    <div class="flex justify-end gap-3">
                        <Button @click="deleteDialog = null" severity="info">Cancel</Button>
                        <Button @click="deleteConfirm" severity="danger">Confirm</Button>
                    </div>
                </template>
        </Dialog>
    </AppLayout>
</template>
