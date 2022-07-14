<template>
    <div class="p-10 rounded max-w-lg mx-auto mt-2">
        <header class="text-center">
            <h2 class="text-2xl font-bold uppercase mb-1">Jármű hozzáadása</h2>
        </header>
        <form @submit.prevent="addVehicle">   
            <div class="mb-6">
                <label for="name" class="inline-block text-lg mb-2">A jármű neve</label>
                <input
                    v-model="vehicle.name"
                    type="text"
                    class="border border-gray-200 rounded p-2 w-full text-black"
                    name="name"
                    placeholder="Példa: Renault Megan" />
            </div>
            <div class="mb-6">
                <button
                    class="bg-laravel text-white rounded py-2 px-4 hover:bg-black">
                    Új jármű hozzáadása
                </button>
            </div>
        </form>
    </div>
    <div>
        <h3 class="text-center" v-if="vehicles.length > 0">Járművek</h3><br/>
        <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
            <div v-for="(vehicle, index) in vehicles" :key="index">
                <vehicle-card :vehicle="vehicle" :yaml="yamlContents[vehicle.id - 1]['other_values']"/>
            </div>
        </div>
    </div>
</template>
 
<script>
    import VehicleCard from './VehicleCard'

    export default {
        components: {
            VehicleCard
        },
        data: function () {
            return {
                vehicles: [],
                yamlContents: [],
                vehicle: {
                    name: ""
                }
            }
        },
        methods: {
            getVehicles() {
                axios.get('api/vehicles')
                .then (response => {
                    this.vehicles = response.data
                })
                .catch (error => {
                    console.log(error);
                })
            },
            getYamlContents() {
                axios.get('api/yaml')
                .then (response => {
                    this.yamlContents = response.data
                })
                .catch (error => {
                    console.log(error);
                })
            },
            addVehicle() {
                if (this.vehicle.name == '') {
                    return;
                }
                axios.post('api/vehicle/store', {
                    vehicle: this.vehicle
                })
                .then( response => {
                    if (response.status == 200) {
                        this.vehicle.name = " ";
                        location.reload();
                    }
                })
                .catch( error => {
                    console.log(error);
                })
            }
        },
        created() {
            this.getVehicles();
            this.getYamlContents();
        }
    }
</script>