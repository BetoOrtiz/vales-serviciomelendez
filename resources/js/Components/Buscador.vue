<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import Select from "@/Components/Select.vue";
import { notify } from "notiwind";
import NumberInput from "@/Components/NumberInput.vue";

const props = defineProps({
    modelValue: Object,
    ruta: String,
    orderByObject: Object,
    params: Object,
    titulo: String,
    campos: Object,
    filtros: Array,
});

const buscar = ref(props.modelValue ? props.modelValue : "");
const filtros = ref(props.filtros ? props.filtros : []);
const ruta = ref(props.ruta ? props.ruta : "");
const buscando = ref(false);
const titulo = ref(
    props.titulo
        ? "Buscar " + props.titulo + "..."
        : "Buscar " + ruta.value + "..."
);
const campos = ref(props.campos ? props.campos : false);
const params = ref(props.params ? props.params : {});
const orderByObject = ref(
    props.orderByObject ? props.orderByObject : { field: "id", sort: "asc" }
);

// select_filtros es un array con {name: "nombre", value: "valor"} de los campos
const select_filtros = campos.value
    ? Object.keys(campos.value).map((key) => {
          return { name: campos.value[key].label, value: key };
      })
    : [];

// select_condiciones es un array con {name: "nombre", value: "valor"} de las condiciones
const select_condiciones = [
    {
        name: "Igual",
        value: "=",
    },
    {
        name: "Mayor",
        value: ">",
    },
    {
        name: "Menor",
        value: "<",
    },
    {
        name: "Mayor o igual",
        value: ">=",
    },
    {
        name: "Menor o igual",
        value: "<=",
    },
    {
        name: "Diferente",
        value: "!=",
    },
    {
        name: "Como",
        value: "LIKE",
    },
    {
        name: "No como",
        value: "NOT LIKE",
    },
    {
        name: "Es nulo",
        value: "IS NULL",
    },
    {
        name: "No es nulo",
        value: "IS NOT NULL",
    },
];

// Valor para almacenar los campos agregados

const muestraFiltros = ref(
    filtros.value && filtros.value.length > 0 ? true : false
);

const rotaIcono = ref(false);

const campo = ref("");
const condicion = ref("");
const valor = ref("");
const conjuncion = ref("AND");

// Función para agregar campos
const agregarFiltro = () => {
    if (campo.value && condicion.value && valor.value) {
        filtros.value.push({
            campo: campo.value,
            nombre_campo: select_filtros.find(
                (filtro) => filtro.value == campo.value
            ).name,
            condicion: condicion.value,
            valor: valor.value,
            conjuncion: conjuncion.value,
        });
        campo.value = "";
        condicion.value = "";
        valor.value = "";
        conjuncion.value = "AND";
    } else {
        notify(
            {
                group: "error",
                title: "Error",
                text: "Debe llenar todos los campos",
            },
            3000
        );
    }
};

function abreFiltros() {
    rotaIcono.value = true;
    muestraFiltros.value = !muestraFiltros.value;
    // Limpia los campos
    if (campos.value) {
        Object.keys(campos.value).forEach((key) => {
            campos.value[key].value = "";
        });
    }
    setTimeout(() => {
        rotaIcono.value = false;
    }, 300);
}

// Función para editar campos
const editaFiltro = (campo_1, condicion_1, valor_1, conjuncion_1, index) => {
    // Comprueba que no se este editando ya un filtro
    if (campo.value && condicion.value && valor.value) {
        notify(
            {
                group: "error",
                title: "Error",
                text: "Debe guardar el filtro actual antes de editar otro",
            },
            3000
        );
        return;
    }

    filtros.value.splice(index, 1);
    campo.value = campo_1;
    condicion.value = condicion_1;
    valor.value = valor_1;
    conjuncion.value = conjuncion_1;
};

const realizarBusqueda = () => {
    buscando.value = true;
    if (buscar.value.length > 0) {
        router.get(
            route(ruta.value + ".search", {
                filtro: buscar.value,
                orderBy: orderByObject.value,
                ...params.value,
            })
        );
    } else {
        router.get(
            route(ruta.value + ".index", {
                orderBy: orderByObject.value,
                ...params.value,
            }),
            { preserveState: true }
        );
    }
};

const filtrar = () => {
    buscando.value = true;
    if (filtros.value.length > 0) {
        router.get(
            route(ruta.value + ".filter", {
                filtros: filtros.value,
                orderBy: orderByObject.value,
                ...params.value,
            })
        );
    } else {
        router.get(
            route(ruta.value + ".index", {
                orderBy: orderByObject.value,
                ...params.value,
            }),
            { preserveState: true }
        );
    }
};

defineEmits(["update:modelValue"]);

const input = ref(null);
</script>
<script>
export default {};
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.25s ease-out;
}

.slide-up-enter-from {
    opacity: 0;
    transform: translateY(30px);
}

.slide-up-leave-to {
    opacity: 0;
    transform: translateY(-30px);
}

.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(30px);
}
</style>

<template>
    <div
        class="flex flex-wrap border-b border-slate-300 bg-slate-200 bg-gradient-to-b from-slate-100 to-slate-200 relative rounded-md shadow-sm rounded-b-none"
    >
        <div
            class="absolute inset-y-0 left-0 pl-4 p-3 items-center pointer-events-none"
        >
            <transition name="slide-up" mode="out-in">
                <font-awesome-icon
                    v-if="muestraFiltros"
                    icon="filter"
                    class="text-slate-500"
                />
                <font-awesome-icon
                    v-else-if="!buscando"
                    icon="magnifying-glass"
                    class="text-slate-400"
                />
                <font-awesome-icon
                    v-else-if="buscando"
                    icon="spinner"
                    class="text-slate-400 animate-spin"
                />
            </transition>
        </div>

        <transition name="slide-up" mode="out-in">
            <div
                v-if="muestraFiltros"
                class="m-1 block w-full pl-10 pr-10 pb-2"
            >
                <h4 class="text-slate-500 font-bold text-lg pt-[5px]">
                    Filtros
                </h4>

                <!-- Sección para mostrar los campos agregados en forma de etiquetas -->

                <TransitionGroup name="list">
                    <template v-for="(filtro, index) in filtros" :key="index">
                        <div
                            class="inset-0 items-start justify-end my-2 inline-flex"
                            :title="
                                'Filtro: ' +
                                (index > 0
                                    ? filtro.conjuncion === 'AND'
                                        ? 'Y'
                                        : 'O'
                                    : '') +
                                ' ' +
                                filtro.nombre_campo +
                                ' ' +
                                filtro.condicion +
                                ' ' +
                                filtro.valor
                            "
                        >
                            <div
                                v-if="index > 0"
                                @click="
                                    filtros[index].conjuncion =
                                        filtros[index].conjuncion === 'AND'
                                            ? 'OR'
                                            : 'AND'
                                "
                                :title="
                                    filtros[index].conjuncion === 'AND'
                                        ? 'Conjunción: Actualmente es Y, cambiar a O'
                                        : 'Conjunción: Actualmente es O, cambiar a Y'
                                "
                                class="cursor-pointer flex items-center justify-center w-10 min-w-max p-2 transition-all duration-200 ease-in-out rounded-full mx-2 top-1 relative"
                                :class="{
                                    'bg-green-600 hover:bg-green-500 active:bg-green-700':
                                        filtro.conjuncion === 'AND',
                                    'bg-blue-600 hover:bg-blue-500 active:bg-blue-700':
                                        filtro.conjuncion === 'OR',
                                }"
                            >
                                <font-awesome-icon
                                    :icon="
                                        filtro.conjuncion === 'AND'
                                            ? 'chevron-up'
                                            : 'chevron-down'
                                    "
                                    class="text-white"
                                />
                            </div>

                            <div
                                class="max-w-sm flex mx-auto overflow-hidden rounded-lg shadow-sm bg-slate-50 transition-transform duration-500 transform"
                            >
                                <div
                                    class="flex items-center justify-center w-10 min-w-max p-2 bg-white font-mono"
                                >
                                    {{ filtro.nombre_campo }}
                                </div>
                                <div
                                    class="flex items-center justify-center w-10 min-w-max p-2 bg-gray-100 font-mono"
                                >
                                    {{ filtro.condicion }}
                                </div>
                                <div
                                    class="flex items-center justify-center w-10 min-w-max p-2 bg-white font-mono"
                                >
                                    {{ filtro.valor }}
                                </div>

                                <!-- Boton de eliminar integrado -->
                                <div
                                    @click="
                                        editaFiltro(
                                            filtro.campo,
                                            filtro.condicion,
                                            filtro.valor,
                                            filtro.conjuncion,
                                            index
                                        )
                                    "
                                    title="Editar filtro"
                                    class="cursor-pointer flex items-center justify-center w-10 min-w-max p-2 bg-slate-300 hover:bg-slate-700 active:bg-slate-900 transition-all duration-200 ease-in-out"
                                >
                                    <font-awesome-icon
                                        icon="edit"
                                        class="text-white"
                                    />
                                </div>
                                <!-- Boton de eliminar integrado -->
                                <div
                                    @click="filtros.splice(index, 1)"
                                    title="Eliminar filtro"
                                    class="cursor-pointer flex items-center justify-center w-10 min-w-max p-2 bg-red-300 hover:bg-red-500 active:bg-red-700 transition-all duration-200 ease-in-out"
                                >
                                    <font-awesome-icon
                                        icon="trash"
                                        class="text-white"
                                    />
                                </div>
                            </div>
                        </div>
                    </template>
                </TransitionGroup>

                <!-- Division -->
                <div
                    v-if="filtros.length > 0"
                    class="border-b border-slate-300 my-2"
                ></div>

                <!-- Sección para seleccionar, el campo, condición y valor -->
                <!-- Div de 3 columnas responsivo -->
                <div
                    class="inset-0 my-2 sm:inline-flex flex-wrap text-center items-center justify-center w-full"
                >
                    <!-- Columna 1 -->
                    <div class="sm:mr-2 sm:w-56 mr-0 w-full">
                        <JetLabel
                            for="campo"
                            value="Campo"
                            class="float-left"
                        />
                        <Select
                            id="campo"
                            v-model="campo"
                            :options="select_filtros"
                            placeholder="Seleccione un campo"
                            class="mt-1 block w-full"
                        />
                    </div>
                    <!-- Columna 2 -->
                    <div class="sm:mr-2 sm:w-32 mr-0 w-full">
                        <JetLabel
                            for="condicion"
                            value="Condición"
                            class="float-left"
                        />
                        <Select
                            id="condicion"
                            v-model="condicion"
                            :options="select_condiciones"
                            placeholder="Seleccione un condición"
                            class="mt-1 block w-full"
                        />
                    </div>
                    <!-- Columna 3 -->
                    <div class="sm:mr-2 sm:w-56 mr-0 w-full">
                        <JetLabel
                            for="valor"
                            value="Valor"
                            class="float-left"
                        />
                        <JetInput
                            id="valor"
                            type="text"
                            v-model="valor"
                            placeholder="Ingrese un valor"
                            class="mt-1 block w-full"
                        />
                    </div>
                    <!-- Columna 4 -->
                    <div class="sm:mr-2 sm:w-32 mr-0 w-full">
                        <JetButton
                            @click="agregarFiltro"
                            type="button"
                            class="mt-5 block h-10 w-full text-center"
                        >
                            <font-awesome-icon icon="plus" class="mr-2" />
                            Agregar
                        </JetButton>
                    </div>
                </div>
                <div class="flex justify-between mt-4">
                    <div class="w-1/2">
                        <JetSecondaryButton
                            class="bg-opacity-95"
                            type="cancel"
                            @click="abreFiltros"
                            >Cancelar</JetSecondaryButton
                        >
                    </div>
                    <div class="w-1/2">
                        <JetButton
                            @click="filtrar"
                            class="float-right"
                            :colors="{
                                bg: 'bg-blue-500',
                                hover: 'hover:bg-blue-600',
                                active: 'active:bg-blue-700',
                            }"
                        >
                            <font-awesome-icon icon="filter" class="mr-2" />
                            Filtrar
                        </JetButton>
                    </div>
                </div>
            </div>
            <JetInput
                v-else
                ref="input"
                class="m-1 block w-full pl-10 h-10"
                :class="{
                    'rounded-r-md': !campos,
                    'mr-1 pr-12': campos,
                }"
                :placeholder="titulo"
                v-model="buscar"
                type="text"
                autofocus
                @keyup.enter="realizarBusqueda"
                :disabled="buscando"
            />
        </transition>
        <button
            v-if="campos"
            @click="abreFiltros"
            class="absolute w-10 inset-y-1 right-1 flex items-center rounded-md hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-opacity-50 border-slate-400 border h-10 transition-all duration-200 ease-in-out"
            :class="{
                'bg-slate-200 rounded-l-none': !muestraFiltros,
                'bg-slate-300 shadow-inner': muestraFiltros,
            }"
            title="Mostrar campos"
        >
            <font-awesome-icon
                icon="gear"
                class="text-slate-500 m-auto"
                :class="{
                    'animate-spin': rotaIcono,
                }"
            />
        </button>
    </div>
</template>
