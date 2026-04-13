<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { usePage, Link, useForm } from "@inertiajs/vue3";
import { reactive, ref, watch, toRefs } from "vue";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import Tabla from "@/Components/Tabla.vue";
import { router } from "@inertiajs/vue3";
import Paginador from "@/Components/Paginador.vue";
import { notify } from "notiwind";
import JetInputError from "@/Jetstream/InputError.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import Select from "@/Components/Select.vue";
import Buscador from "@/Components/Buscador.vue";
import Ordena from "@/Components/Ordena.vue";
import Progress from "@/Components/Progress.vue";

const props = defineProps({
    configuraciones: Object,
    configuracion: Object,
    filtro: String,
    orderBy: Object,
});

const form = useForm({
    clave: "",
    valor: null,
    tipo: "",
});

const error = reactive({
    clave: "",
    valor: "",
    tipo: "",
});

const configuraciones = toRefs(props).configuraciones;
const buscar = ref(props.filtro ? props.filtro : "");
const estadoModalConfiguracion = ref(false);
const estadoModalEliminaConfiguracion = ref(false);

let photoPreview = ref(null);
let photoInput = ref(null);

const accion = ref("new");
const idActual = ref(null);
const currentConfiguracion = ref(null);
const orderByObject = ref(
    props.orderBy
        ? props.orderBy
        : {
              field: "name",
              sort: "asc",
          }
);

const nuevoConfiguracion = () => {
    accion.value = "new";
    form.reset("clave", "valor", "tipo");
    error.clave = "";
    error.valor = "";
    error.tipo = "";
    estadoModalConfiguracion.value = true;
};

const nuevoConfiguracionDo = () => {
    error.clave = "";
    error.valor = "";
    error.tipo = "";
    form.post(route("configuraciones.store"), {
        preserveScroll: true,
        onSuccess: (rest) => {
            notify(
                {
                    group: "main",
                    title: "Configuración creada",
                    text: "La configuración se ha creado exitosamente",
                },
                3000
            );
            estadoModalConfiguracion.value = false;
            form.reset("clave", "valor");
        },
        onError: (errors) => {
            if (!errors.clave && !errors.valor && !errors.tipo) {
                notify(
                    {
                        group: "error",
                        title: "Ocurrió un error",
                        text: "Error al registrar la configuración",
                    },
                    3000
                );
            }
            error.clave = errors.clave;
            error.valor = errors.valor;
            error.tipo = errors.tipo;
        },
    });
};

const editaConfiguracion = (configuracion) => {
    accion.value = "edit";
    currentConfiguracion.value = configuracion;
    idActual.value = configuracion.id;
    form.clave = configuracion.clave;
    form.valor = configuracion.valor;
    form.tipo = configuracion.tipo;
    if (form.tipo === "Imagen") {
        photoPreview.value = form.valor;
    }

    error.clave = "";
    error.valor = "";
    error.tipo = "";
    estadoModalConfiguracion.value = true;
};

const editaConfiguracionDo = () => {
    if (form.tipo === "Imagen") {
        if (photoInput.value) {
            form.valor = photoInput.value.files[0];
        } else {
            form.valor = null;
        }
    }

    form.post(route("configuraciones.update", idActual.value), {
        preserveScroll: true,
        onSuccess: (rest) => {
            notify(
                {
                    group: "main",
                    title: "Configuración editada",
                    text: "La configuración se ha editado exitosamente",
                },
                3000
            );
            estadoModalConfiguracion.value = false;
            form.reset("clave", "valor", "tipo");
            clearPhotoFileInput();
        },
        onError: (errors) => {
            if (!errors.clave && !errors.valor && !errors.tipo) {
                notify(
                    {
                        group: "error",
                        title: "Ocurrió un error",
                        text: "Error al editar la configuración",
                    },
                    3000
                );
            }
            error.clave = errors.clave;
            error.valor = errors.valor;
            error.tipo = errors.tipo;
        },
    });
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];
    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <AppLayout title="Configuraciones">
        <div class="max-w-full mx-auto px-0 sm:px-8 py-4 pt-0 sm:pt-4">
            <div
                class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-slate-300/90"
            >
                <Buscador
                    v-model="buscar"
                    :orderByObject="orderByObject"
                    ruta="configuraciones"
                    autofocus
                />
                <Tabla v-if="configuraciones.data.length > 0">
                    <template #col>
                        <th class="px-4 py-1 w-32">
                            <Ordena
                                v-model="orderByObject"
                                ruta="configuraciones"
                                :buscar="buscar"
                                titulo="ID"
                                campo="id"
                            />
                        </th>
                        <th class="px-4 py-1">
                            <Ordena
                                v-model="orderByObject"
                                ruta="configuraciones"
                                :buscar="buscar"
                                titulo="Nombre"
                                campo="clave"
                            />
                        </th>
                        <th class="px-4 py-1">
                            <Ordena
                                v-model="orderByObject"
                                ruta="configuraciones"
                                :buscar="buscar"
                                titulo="Descripción"
                                campo="valor"
                            />
                        </th>
                        <th
                            class="px-4 py-1 w-5 text-center sticky right-0 bg-slate-200/50 border-l border-slate-200"
                        >
                            Acciones
                        </th>
                    </template>
                    <template #row>
                        <tr
                            class="text-slate-700 hover:bg-slate-300 border-b border-slate-200"
                            v-for="configuracion in configuraciones.data"
                            :key="configuracion.id"
                        >
                            <td class="px-4 py-1">
                                {{ configuracion.id }}
                            </td>
                            <td class="px-4 py-1">
                                {{ configuracion.clave }}
                            </td>
                            <td class="px-4 py-1">
                                <template v-if="configuracion.tipo === 'Imagen'">
                                    <img
                                        :src="configuracion.valor"
                                        alt="Configuración"
                                        class="h-16 w-16 object-contain rounded border"
                                    />
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ configuracion.valor }}
                                    </div>
                                </template>
                                <template v-else>
                                    {{ configuracion.valor }}
                                </template>
                            </td>
                            <td
                                class="px-4 py-1 text-center sticky right-0 bg-slate-200/50 border-l border-slate-200"
                            >
                                <JetButton
                                    class="bg-blue-500 hover:bg-blue-700 text-white"
                                    @click="editaConfiguracion(configuracion)"
                                >
                                    <font-awesome-icon icon="pen" />
                                </JetButton>
                            </td>
                        </tr>
                    </template>
                    <template #pagination>
                        <Paginador
                            :links="configuraciones.links"
                            :total="configuraciones.total"
                            :to="configuraciones.to"
                            :from="configuraciones.from"
                        />
                    </template>
                </Tabla>
                <div v-else class="text-center p-5 text-slate-500 text-md">
                    Sin resultados
                    <br />
                </div>
            </div>
        </div>

        <form
            class="flex flex-col"
            @submit.prevent="
                accion === 'new' ? nuevoConfiguracionDo : editaConfiguracionDo
            "
        >
            <JetDialogModal
                :show="estadoModalConfiguracion"
                @close="estadoModalConfiguracion = false"
                max-width="md"
                id="elmodal"
            >
                <template #title
                    >{{
                        accion === "new"
                            ? "Crear nuevo configuracion"
                            : "Editar configuracion"
                    }}
                </template>
                <template #content>
                    <label class="block mb-1">
                        <JetLabel
                            for="clave"
                            value="Nombre"
                            class="float-left"
                        />
                        <JetInput
                            id="clave"
                            v-model="form.clave"
                            placeholder="Nombre configuración"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            disabled
                        />
                        <JetInputError :message="error.clave" class="mt-2" />
                    </label>
                    <label v-if="form.tipo === 'Texto'" class="block mb-1">
                        <JetLabel
                            for="valor"
                            value="Valor"
                            class="float-left"
                        />
                        <JetInput
                            id="valor"
                            v-model="form.valor"
                            placeholder="Valor de la configuración"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autofocus
                            :disabled="form.processing"
                        />
                        <JetInputError :message="error.valor" class="mt-2" />
                    </label>
                    <label v-if="form.tipo === 'Imagen'" class="block mb-1">
                        <JetLabel
                            for="valor"
                            value="Valor"
                            class="float-left"
                        />
                        <input
                            ref="photoInput"
                            type="file"
                            @change="updatePhotoPreview"
                            accept=".png, .PNG, .jpg, .JPG, .jpeg. .JPEG"
                            class="w-full border-gray-400 focus:border-slate-400 focus:ring focus:ring-slate-400 focus:ring-opacity-50 rounded-md shadow-sm disabled:text-gray-500 file:border-solid file:inline-flex file:items-center file:px-4 file:py-2 file:bg-gray-800 file:border file:border-transparent file:rounded-md file:font-semibold file:text-xs file:text-white file:uppercase file:tracking-widest hover:file:bg-gray-700 active:file:bg-gray-900 focus:file:outline-none focus:file:border-gray-900 focus:file:ring focus:file:ring-gray-300 file:disabled:opacity-25 file:shadow-sm hover:file:shadow-md focus:file:shadow-lg active:file:shadow-outline file:transition"
                            :disabled="form.processing"
                        />
                        <JetInputError :message="error.valor" class="mt-2" />
                        <div
                            class="w-full text-gray-500 text-center text-sm my-2"
                        >
                            Se sugiere una medida 500 x 500 píxeles.
                        </div>
                        <Progress
                            v-if="form.progress"
                            :value="form.progress.percentage"
                        />
                        <div v-show="photoPreview" class="mt-2">
                            <span
                                class="block rounded-md mx-auto w-40 h-40 bg-cover bg-no-repeat bg-center shadow-xl border-[1px] border-gray-500"
                                :style="
                                    'background-image: url(\'' +
                                    photoPreview +
                                    '\');'
                                "
                            />
                        </div>
                    </label>
                    <label v-if="form.tipo === 'Fecha'" class="block mb-1">
                        <JetLabel
                            for="valor"
                            value="Valor"
                            class="float-left"
                        />
                        <JetInput
                            id="valor"
                            v-model="form.valor"
                            placeholder="Valor de la configuración"
                            type="date"
                            class="mt-1 block w-full"
                            required
                            autofocus
                            :disabled="form.processing"
                        />
                        <JetInputError :message="error.valor" class="mt-2" />
                    </label>
                </template>
                <template #footer>
                    <div class="w-1/2">
                        <JetSecondaryButton
                            @click="estadoModalConfiguracion = false"
                            class="bg-opacity-95"
                            type="cancel"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            >Cancelar
                        </JetSecondaryButton>
                    </div>
                    <div class="w-1/2">
                        <JetButton
                            v-if="accion === 'new'"
                            @click="nuevoConfiguracionDo"
                            class="float-right"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            <template v-if="form.processing">
                                <font-awesome-icon
                                    icon="spinner"
                                    class="mr-2 animate-spin"
                                />Creando Configuración...
                            </template>
                            <template v-else>
                                <font-awesome-icon
                                    icon="plus"
                                    class="mr-2"
                                />Crear Configuración
                            </template>
                        </JetButton>

                        <JetButton
                            v-if="accion === 'edit'"
                            @click="editaConfiguracionDo"
                            class="float-right"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            <template v-if="form.processing">
                                <font-awesome-icon
                                    icon="spinner"
                                    class="mr-2 animate-spin"
                                />Guardando...
                            </template>
                            <template v-else>
                                <font-awesome-icon
                                    icon="floppy-disk"
                                    class="mr-2"
                                />Guardar Cambios
                            </template>
                        </JetButton>
                    </div>
                </template>
            </JetDialogModal>
        </form>
    </AppLayout>
</template>
