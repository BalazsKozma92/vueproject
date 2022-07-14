<style>
input[type=checkbox] {
  visibility: hidden;
}
.checkbox_circle {
  position: relative;
}
.checkbox_circle label {
  cursor: pointer;
  position: absolute;
  width: 16px;
  height: 16px;
  left: 0px;
  top: 5px;
  border-radius: 999px;
  border: 1px solid grey;
}
.main_checkbox label {
    left: 48px;
}
.checkbox_circle label span{
  position: absolute;
  left: 25px;
  font-size: 15px;
  top: -5px;
}
.main_checkbox label span{
    width: 300px;
}
.checkbox_circle label:after {
  filter: alpha(opacity=0);
  opacity: 0;
  content: '';
  position: absolute;
  left: 2px;
  top: 2px;
  right: 2px;
  bottom: 2px;
  background: rgb(71, 164, 250);
  border-radius: 999px;
}
.checkbox_circle label:hover::after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
  filter: alpha(opacity=30);
  opacity: 0.3;
}
.checkbox_circle input[type=checkbox]:checked + label:after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  filter: alpha(opacity=100);
  opacity: 1;
}
.checkbox_circle input[type=checkbox]:checked + label {
  border-color: grey;
}
</style>

<template>
    <div class="p-25 rounded max-w-6xl mx-auto mt-24">
        <header class="text-center">
            <h2 class="text-5xl font-bold uppercase mb-1">Szerkesztés</h2>
            <p class="mb-24">{{ vehicle.name }} Szerkesztése</p>
        </header>

        <form @submit.prevent="updateVehicle">
        
            <div class="mb-6">
                <label for="name" class="inline-block text-lg mb-2 mr-3">A jármű neve</label>
                <input
                    v-model="vehicleName"
                    type="text"
                    class="border border-gray-200 rounded p-2 w-1/3 text-black"
                    name="name"
                    placeholder="Példa: Renault Megan"
                    />
            </div>

            <div class="container mx-auto px-4 bg-opacity-90 p-3 text-white bg-[#171010]">
                <div class="flex items-center mb-4">
                    <img class="w-16 logo border-blue" src="/images/axle.webp" alt=""/>
                    <p class="uppercase font-bold text-lg ml-2">Gumiabroncs és beállítások</p>
                    <div class="checkbox_circle main_checkbox">
                        <input id="checkbox" type="checkbox" name="checkbox" v-model="canEdit">
                        <!-- <label for="checkbox" class="font-bold text-sm ml-1 w-5 h-5 bg-white border border-solid border-slate-600 rounded-full cursor-pointer absolute left-16 top-1" :class="{'bg-blue-600': canEdit}"> -->
                        <label for="checkbox">
                            <span>Tengely szerkesztés engedélyezése</span>
                        </label>
                    </div>
                </div>
                <hr>

                <div class="grid grid-cols-8 gap-4 mt-5 place-items-center" v-for="(yamlContent, mainIndex) in yamlContents['other_values']" :key="mainIndex">

                    <div v-if="leftArray[mainIndex][0] != -1" class="flex justify-center">
                        <div class="w-7 h-12 rounded-md flex items-center justify-center bg-gray-500 bg-opacity-30 border mr-1 font-bold" v-for="(tyreNumber, tyreIndex) in leftArray[mainIndex]" :key="tyreIndex">
                            {{ tyreNumber }}
                        </div>
                    </div>

                    <div class="flex justify-center" :class="{'col-span-2': leftArray[mainIndex][0] == -1}">
                        <div class="w-7 h-12 rounded-md flex items-center justify-center bg-gray-500 bg-opacity-30 border mr-1 font-bold" v-for="(tyreNumber, tyreIndex) in rightArray[mainIndex]" :key="tyreIndex">
                            {{ tyreNumber }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm">
                            Kerekek
                        </label>
                        <select :id="['tyres_'] + mainIndex" name="tyres" class="rounded gray bg-stone-900 border pl-2 pr-6 py-2 rounded-lg" :disabled="!canEdit" v-on:click="somethingChanged($event)">
                            <option value="1" v-if="mainIndex < 2" :selected="yamlContent['tyres'] == 1">1</option>
                            <option value="2" :selected="yamlContent['tyres'] == 2">2</option>
                            <option value="4" :selected="yamlContent['tyres'] == 4">4</option>
                            <option value="6" :selected="yamlContent['tyres'] == 6">6</option>
                        </select>
                    </div>

                    <div class="checkbox_circle">
                        <input type="checkbox" :id="['hajtott_'] + mainIndex" name="hajtott" :checked="yamlContent['hajtott'] == true" :disabled="!canEdit" v-on:click="somethingChanged($event)">
                        <label :for="['hajtott_'] + mainIndex">
                            <span>hajtott</span>
                        </label>
                    </div>

                    <div class="checkbox_circle">
                        <input type="checkbox" :id="['kormanyzott_'] + mainIndex" name="kormanyzott" :checked="yamlContent['kormanyzott'] == true" :disabled="!canEdit" v-on:click="somethingChanged($event)">
                        <label :for="['kormanyzott_'] + mainIndex">
                            <span>kormányzott</span>
                        </label>
                    </div>

                    <div v-if="mainIndex != 0" class="checkbox_circle">
                        <input type="checkbox" :id="['boogie_'] + mainIndex" name="boogie" :checked="yamlContent['boogie'] == true" :disabled="!canEdit" v-on:click="somethingChanged($event)">
                        <label :for="['boogie_'] + mainIndex">
                            <span>boogie</span>
                        </label>
                    </div>
                    <div v-else>

                    </div>

                    <div class="col-span-2 flex justify-center" v-if="mainIndex == Object.keys(yamlContents['other_values']).length - 1 && Object.keys(yamlContents['other_values']).length != 1">
                        <a id="newAxleButton" class="bg-stone-900 text-cyan-500 w-6 h-6 rounded-full border-2 text-2xl inline-flex flex-col justify-center items-center pb-2 text-md" :disabled="!canEdit" v-on="canEdit ? { click: removeAxle } : {}" :class="{'hover:bg-stone-700 cursor-pointer': canEdit}">
                            -
                        </a>
                        <label class="text-sm text-gray-500 ml-3 self-center">Tengely törlése</label>
                    </div>
                    <div class="col-span-2" v-else>

                    </div>
                </div>
                <div class="grid grid-cols-8 gap-4 mt-5 place-items-center">
                    <div class="col-span-2 text-center mt-2">
                        <a id="newAxleButton" class="bg-stone-900 text-cyan-500 w-6 h-6 rounded-full border-2 text-2xl align-center inline-flex flex-col justify-center items-center text-sm" :disabled="!canEdit" v-on="canEdit ? { click: addAxle } : {}" :class="{'hover:bg-stone-700 cursor-pointer': canEdit}">
                            +
                        </a>
                        <label class="text-sm mt-2 text-gray-500 flex">Tengely hozzáadása</label>
                    </div>
                    <div class="col-span-2 mt-4">
                        <label class="block text-gray-400 text-sm mb-2" for="spareTyres">
                            Pótkerekek
                        </label>
                        <input class="rounded gray bg-stone-900 border pl-2 pr-6 py-2 rounded-lg" id="spare_tyres" type="text" name="spareTyres" v-model="spareTyre" :disabled="!canEdit">
                    </div>
                </div>
                <div class="mb-3 mt-8">
                    <button class="text-white rounded py-2 px-4 bg-stone-600 hover:bg-stone-400 mr-5">
                        Mentés
                    </button>
                    <router-link class="text-white rounded py-2 px-4 bg-red-500 hover:bg-red-300" :to="{ name: 'home'}"> Vissza</router-link>
                </div>
            </div>
        </form>
    </div>
</template>
 
<script>
import { useRoute } from 'vue-router';

    export default {
        data: function () {
            const routes = useRoute();
            return {
                canEdit: false,
                vehicle: [],
                yamlContents: {},
                vehicleName: "",
                spareTyre: "",
                routes: routes,
                leftArray: [],
                rightArray: []
            }
        },
        methods: {
            somethingChanged(event) {
                console.log(event.currentTarget);
                let targetId = (event.currentTarget.id).split('_')[1];
                let changedValue = event.currentTarget.name == "tyres" ? parseInt(event.currentTarget.value) : event.currentTarget.checked;
                this.yamlContents['other_values'][targetId][event.currentTarget.name] = changedValue;
                this.setTyreNumbers()
            }, 
            updateVehicle() {
                if (this.vehicleName == '') {
                    return;
                } 
                axios.put('api/vehicle/' + (this.routes.params.id - 1), {
                    vehicleName: this.vehicleName,
                    yamlContents: this.yamlContents,
                    spareTyre: this.spareTyre
                })
                .then( response => {
                    if (response.status == 200) {
                        this.$router.push('/'); 
                    }
                })
                .catch( error => {
                    console.log(error);
                })
            },
            getVehicles() {
                axios.get('api/vehicles')
                .then (response => {
                    response.data.forEach(element => {
                        if (element.id == this.routes.params.id) {
                            this.vehicle = element;
                        }
                        this.vehicleName = this.vehicle.name
                    });
                })
                .catch (error => {
                    console.log(error);
                })
            },
            getYamlContents() {
                axios.get('api/yaml')
                .then (response => {
                    this.yamlContents = response.data[this.routes.params.id - 1]
                    this.setTyreNumbers();
                    this.spareTyre = this.yamlContents['spare_tyres']
                })
                .catch (error => {
                    console.log(error);
                })
            },
            addAxle() {
                axios.post('api/vehicle/addAxle/' + (this.routes.params.id - 1), {
                    yamlTemp: this.yamlContents
                })
                .then (response => {
                    this.yamlContents = response.data[this.routes.params.id - 1]
                    this.setTyreNumbers()
                    console.log(response.data);
                })
                .catch (error => {
                    console.log(error);
                })
            },
            removeAxle() {
                axios.post('api/vehicle/removeAxle/' + (this.routes.params.id - 1), {
                    yamlTemp: this.yamlContents
                })
                .then (response => {
                    this.yamlContents = response.data[this.routes.params.id - 1]
                    this.setTyreNumbers()
                })
                .catch (error => {
                    console.log(error);
                })
            },
            setTyreNumbers() {
                let sum = 1;
                let rightArray = [];
                let leftArray = [];
                let tempArrayRight = [];
                let tempArrayLeft = [];
                for (let i = 0; i < this.yamlContents['other_values'].length ; i++) {
                    if (this.yamlContents['other_values'][i]['tyres'] == 1) {
                        rightArray.push([sum])
                        sum++;
                        leftArray.push([-1]);
                    } else {
                        for (let j = 0; j < this.yamlContents['other_values'][i]['tyres']; j+=2) {
                            tempArrayRight.push(sum);
                            sum++;
                            tempArrayLeft.push(sum);
                            sum++;
                            if (j == this.yamlContents['other_values'][i]['tyres'] - 2) {
                                tempArrayRight.reverse();
                                rightArray.push(tempArrayRight);
                                leftArray.push(tempArrayLeft);
                                tempArrayLeft = [];
                                tempArrayRight = [];
                            }
                        }
                    }
                }
                this.rightArray = rightArray;
                this.leftArray = leftArray;
            }
        },
        created() {
            this.getVehicles();
            this.getYamlContents();
        }
    }
</script>