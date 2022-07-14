<template>
    <div class='bg-[#111827] border border-gray-200 rounded p-6 border-0.5'>
    <div class="flex">

        <div class="space-x-4 flex text-[#9CA3AF] text-base grid grid-cols-5 place-items-center">
            <h2 class="font-bold text-lg text-white">
                {{ vehicle.name }}
            </h2>
            <p>Tengelyszám: <span class="font-bold">{{ Object.keys(yaml).length }}</span></p>
            <p>Létrehozva: <span class="font-bold">{{ format_date(vehicle.created_at) }}</span></p>
            <p>Szerkesztve: <span class="font-bold">{{ format_date(vehicle.updated_at) }}</span></p>
            <div class="space-x-5">
                <router-link class="text-green-500" :to="{ name: 'edit', params: { id: vehicle.id }}"><i class="fa-solid fa-pencil"></i> </router-link>
                <button class="text-red-500" @click="deleteEntry"><i class="fa-solid fa-trash"></i> </button>
            </div>
        </div>

        <input type="hidden" v-model="vehicle.id">
    </div>
</div>
</template>
 
<script>
import moment from 'moment'
import EditVehicle from './EditVehicle'

    export default {
        props: {
            vehicle: {
                required: true
            },
            yaml: {
                required: true
            }
        },
        components: {
            EditVehicle,
        },
        methods: { 
            format_date(value){
                if (value) {
                    return moment(String(value)).format('YY.MM.DD')
                }
            },
            deleteEntry() {
                console.log("hello");
                axios.delete('api/vehicle/' + (this.vehicle.id), {})
                location.reload();
            }
        },
    }
</script>