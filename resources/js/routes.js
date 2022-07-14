import AllVehicle from './components/AllVehicle.vue';
import EditVehicle from './components/EditVehicle.vue';
 
export const routes = [
    {
        name: 'home',
        path: '/',
        component: AllVehicle
    },
    {
        name: 'edit',
        path: '/edit/:id',
        component: EditVehicle
    }
];