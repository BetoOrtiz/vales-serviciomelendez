<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    modelValue: Object,
    ruta: String,
    titulo: String,
    campo: String,
    buscar: String,
    params: Object,
    filtros: Array,
});

const buscar = ref(props.buscar ? props.buscar : "");
const filtros = ref(props.filtros ? props.filtros : []);
const ruta = ref(props.ruta ? props.ruta : "");
const campo = ref(props.campo ? props.campo : "");
const titulo = ref(props.titulo ? props.titulo : "");
const params = ref(props.params ? props.params : {});
const orderByObject = ref(
    props.modelValue ? props.modelValue : { field: "id", sort: "asc" }
);

defineEmits(["update:modelValue"]);

const ordena = (key) => {
    if (orderByObject.value.field === key) {
        orderByObject.value.sort =
            orderByObject.value.sort === "asc" ? "desc" : "asc";
    } else {
        orderByObject.value.field = key;
        orderByObject.value.sort = "asc";
    }

    if (filtros.value.length > 0) {
        router.get(
            route(
                ruta.value + ".filter",
                {
                    filtros: filtros.value,
                    orderBy: orderByObject.value,
                    ...params.value,
                },
                { preserveState: true }
            )
        );
    } else if (buscar.value.length > 0) {
        router.get(
            route(
                ruta.value + ".search",
                {
                    filtro: buscar.value,
                    orderBy: orderByObject.value,
                    ...params.value,
                },
                { preserveState: true }
            )
        );
    } else {
        router.get(
            route(
                ruta.value + ".index",
                {
                    orderBy: orderByObject.value,
                    ...params.value,
                },
                { preserveState: true }
            )
        );
    }
};
</script>
<script>
export default {};
</script>

<template>
    <span
        @click="ordena(campo)"
        class="hover:underline cursor-pointer"
        :class="orderByObject.field === campo ? 'text-gray-900' : ''"
        >{{ titulo }}
        <font-awesome-icon
            v-if="orderByObject.field === campo"
            :icon="'sort-' + orderByObject.sort"
        />
    </span>
</template>
