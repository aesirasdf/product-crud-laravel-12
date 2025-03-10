<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { onMounted, onUpdated, ref } from 'vue'
import DataTable from 'primevue/datatable'
import Paginator from 'primevue/paginator'
import Column from 'primevue/column'
import { ProductIndex } from '../../api/products'
import InputText from 'primevue/inputtext'

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



const refreshPage = (v) => {
    if(+v?.page){
        others.value.page = +v.page + 1
    }else{
        v.preventDefault()
    }
    ProductIndex({
        page: others.value.page,
        limit: others.value.limit,
        search_params: others.value.search_params
    }).then(res => {
        if(res?.ok){
            products.value = res.data
            others.value = res.others
        }
    })

}



</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-10">
            <DataTable :value="products">
                <template #header>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="text-xl font-bold">Products</span>
                        <div>
                            <form v-on:submit="refreshPage">
                                <InputText v-model="others.search_params" placeholder="Search..." />
                            </form>
                        </div>
                    </div>
                </template>
                <Column field="id" header="ID"></Column>
                <Column field="name" header="Name"></Column>
                <Column field="category" header="Category"></Column>
                <Column field="description" header="Description"></Column>
                <Column field="date_time" header="Date and Time"></Column>
                <template></template>
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
    </AppLayout>
</template>
