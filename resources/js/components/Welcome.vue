<template>

        <nav class="flex justify-between items-center m-15 p-4">
            <a href="/"><img class="w-24 logo" src="/images/axle.webp" alt=""/></a>
        </nav>

        <section class="relative h-64 bg-[#111827] flex flex-col justify-center align-center text-center space-y-4 mb-4">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-no-repeat bg-center">
            </div>
            <div class="z-10">
                <h1 class="text-6xl font-bold uppercase text-white">Jármű és tengelynyilvántartó</h1>
            </div>
        </section>

        <router-view></router-view>

</template>
 
 
<script>
    export default {
        data: function () {
            return {
                vehicle: {
                    name: ""
                }
            }
        },
        methods: {
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
        }
        
    }
</script>