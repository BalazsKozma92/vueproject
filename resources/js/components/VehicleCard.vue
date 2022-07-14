<template>
    <div class='bg-gray-500 border border-gray-200 rounded p-6'>
    <div class="flex">
        <div>
            <div class="space-x-4 flex">
                <h2 class="font-bold">
                    {{ vehicle.name }}
                </h2><p>|</p>
                <p>Tengelyek száma: {{ Object.keys(yaml).length }}</p><p>|</p>
                <p>Létrehozva: {{ format_date(vehicle.created_at) }}</p><p>|</p>
                <p>Szerkesztve: {{ format_date(vehicle.updated_at) }}</p>
            </div>
            <div class="bg-gray-700 border border-gray-200 rounded mt-4 p-2 flex space-x-6">
                <router-link class="text-green-500" :to="{ name: 'edit', params: { id: vehicle.id }}"><i class="fa-solid fa-pencil"></i> Szerkesztés</router-link>
                <button class="text-red-500" @click="deleteEntry">Törlés</button>
            </div>
            <input type="hidden" v-model="vehicle.id">
        </div>
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
                    return moment(String(value)).format('YYYY.MM.DD')
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