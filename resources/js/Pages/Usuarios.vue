<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { reactive, ref, toRefs } from "vue";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import Tabla from "@/Components/Tabla.vue";
import Paginador from "@/Components/Paginador.vue";
import { notify } from "notiwind";
import JetInputError from "@/Jetstream/InputError.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import Select from "@/Components/Select.vue";
import Buscador from "@/Components/Buscador.vue";
import Ordena from "@/Components/Ordena.vue";

const props = defineProps({
    usuarios: Object,
    usuario: Object,
    filtro: String,
    orderBy: Object,
    auth: Object,
    filtros: Array,
});

const form = useForm({
    name: "",
    titulo: "",
    email: "",
    password: "",
    rol: "",
});

const error = reactive({
    name: "",
    titulo: "",
    email: "",
    password: "",
});

const usuarios = toRefs(props).usuarios;
const buscar = ref(props.filtro ? props.filtro : "");
const filtros = ref(props.filtros ? props.filtros : []);
const estadoModalUsuario = ref(false);
const estadoModalEliminaUsuario = ref(false);
const accion = ref("new");
const idActual = ref(null);
const orderByObject = ref(
    props.orderBy
        ? props.orderBy
        : {
              field: "name",
              sort: "asc",
          }
);

const nuevoUsuario = () => {
    accion.value = "new";
    form.reset("name", "titulo", "email", "password", "rol");
    form.rol = "Usuario";
    error.name = "";
    error.titulo = "";
    error.email = "";
    error.password = "";
    estadoModalUsuario.value = true;
};

const editaUsuario = (usuario) => {
    accion.value = "edit";
    idActual.value = usuario.id;
    form.name = usuario.name;
    form.titulo = usuario.titulo;
    form.email = usuario.email;
    form.rol = usuario.roles.length > 0 ? usuario.roles[0].name : "";
    form.password = "";
    error.name = "";
    error.titulo = "";
    error.email = "";
    error.password = "";
    estadoModalUsuario.value = true;
};

const isAdmin = () => {
    if (props.auth.roles.length > 0) {
        if (props.auth.roles[0] === "Administrador") {
            return true;
        }
    }
};

let roles = [
    {
        name: "Administrador",
    },
    {
        name: "Usuario",
    },
];

const campos = {
    id: {
        label: "ID",
    },
    name: {
        label: "Nombre",
    },
    titulo: {
        label: "Título",
    },
    email: {
        label: "Email",
    },
    rol: {
        label: "Rol",
    },
};

const bg_color = {
    Administrador: "bg-red-600",
    Usuario: "bg-orange-600",
};

const nuevoUsuarioDo = () => {
    error.name = "";
    error.titulo = "";
    error.email = "";
    error.password = "";
    idActual.value = null;
    form.post(route("usuarios.store"), {
        preserveScroll: true,
        onSuccess: (rest) => {
            notify(
                {
                    group: "main",
                    title: "Usuario creado",
                    text: "El usuario se ha creado exitosamente",
                },
                3000
            );
            estadoModalUsuario.value = false;
            form.reset("name", "titulo", "email", "password", "rol");
        },
        onError: (errors) => {
            if (
                !errors.name &&
                !errors.titulo &&
                !errors.email &&
                !errors.password
            ) {
                notify(
                    {
                        group: "error",
                        title: "Ocurrió un error",
                        text: "Error al registrar usuario",
                    },
                    3000
                );
            }

            error.name = errors.name
                ? errors.name.replace("name", "Nombre")
                : null;
            error.email = errors.email
                ? errors.email.replace("email", "Email")
                : null;
            error.password = errors.password
                ? errors.password.replace("password", "Contraseña")
                : null;
        },
    });
};

const editaUsuarioDo = () => {
    form.post(route("usuarios.update", idActual.value), {
        preserveScroll: true,
        onSuccess: (rest) => {
            notify(
                {
                    group: "main",
                    title: "Usuario editado",
                    text: "El usuario se ha editado exitosamente",
                },
                3000
            );
            estadoModalUsuario.value = false;
            form.reset("name", "titulo", "email", "password", "rol");
        },
        onError: (errors) => {
            if (
                !errors.name &&
                !errors.titulo &&
                !errors.email &&
                !errors.password
            ) {
                notify(
                    {
                        group: "error",
                        title: "Ocurrió un error",
                        text: "Error al editar usuario",
                    },
                    3000
                );
            }

            error.name = errors.name
                ? errors.name.replace("name", "Nombre")
                : null;
            error.email = errors.email
                ? errors.email.replace("email", "Email")
                : null;
            error.password = errors.password
                ? errors.password.replace("password", "Contraseña")
                : null;
        },
    });
};

const borraUsuarioDo = () => {
    form.post(route("usuarios.destroy", idActual.value), {
        preserveScroll: true,
        onSuccess: (rest) => {
            notify(
                {
                    group: "main",
                    title: "Usuario eliminado",
                    text: "El usuario se ha eliminado exitosamente",
                },
                3000
            );
            estadoModalUsuario.value = false;
            estadoModalEliminaUsuario.value = false;
            form.reset("name", "titulo", "email", "password", "rol");
        },
        onError: (errors) => {
            if (
                !errors.name &&
                !errors.titulo &&
                !errors.email &&
                !errors.password
            ) {
                notify(
                    {
                        group: "error",
                        title: "Ocurrió un error",
                        text: "Error al registrar usuario",
                    },
                    3000
                );
            }
        },
    });
};
</script>
<template>
    <AppLayout title="Usuarios">
        <div class="max-w-full mx-auto px-0 sm:px-8 py-4 pt-0 sm:pt-4">
            <div
                class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-slate-300/90"
            >
                <Buscador
                    v-model="buscar"
                    :orderByObject="orderByObject"
                    ruta="usuarios"
                    :filtros="filtros"
                    :campos="campos"
                    autofocus
                />
                <Tabla v-if="usuarios.data.length > 0">
                    <template #col>
                        <th class="px-4 py-1 w-20">
                            <Ordena
                                v-model="orderByObject"
                                ruta="usuarios"
                                :buscar="buscar"
                                :filtros="filtros"
                                titulo="ID"
                                campo="id"
                            />
                        </th>
                        <th class="px-4 py-1">
                            <Ordena
                                v-model="orderByObject"
                                ruta="usuarios"
                                :buscar="buscar"
                                :filtros="filtros"
                                titulo="Nombre"
                                campo="name"
                            />
                        </th>
                        <th class="px-4 py-1">
                            <Ordena
                                v-model="orderByObject"
                                ruta="usuarios"
                                :buscar="buscar"
                                :filtros="filtros"
                                titulo="Titulo"
                                campo="titulo"
                            />
                        </th>
                        <th class="px-4 py-1 w-72">
                            <Ordena
                                v-model="orderByObject"
                                ruta="usuarios"
                                :buscar="buscar"
                                :filtros="filtros"
                                titulo="Email"
                                campo="email"
                            />
                        </th>
                        <th class="px-4 py-1 w-36">Rol</th>
                        <th
                            class="px-4 py-1 w-5 text-center sticky right-0 bg-slate-200/50 border-l border-slate-200"
                        >
                            <JetButton @click="nuevoUsuario">
                                <font-awesome-icon icon="add" />
                            </JetButton>
                        </th>
                    </template>
                    <template #row>
                        <tr
                            class="text-slate-700 hover:bg-slate-300 border-b border-slate-200"
                            v-for="usuario in usuarios.data"
                            :key="usuario.id"
                        >
                            <td class="px-4 py-1">{{ usuario.id }}</td>
                            <td class="px-4 py-1">{{ usuario.name }}</td>
                            <td class="px-4 py-1">{{ usuario.titulo }}</td>
                            <td class="px-4 py-1">{{ usuario.email }}</td>
                            <td class="px-4 py-1">
                                <div v-for="p in usuario.roles" :key="p.id">
                                    <span
                                        class="text-gray-200 mr-1 mb-1 px-1 rounded nowrap"
                                        :class="bg_color[p.name]"
                                    >
                                        {{ p.name }}
                                    </span>
                                </div>
                            </td>
                            <td
                                class="px-4 py-1 text-center sticky right-0 bg-slate-200/50 border-l border-slate-200"
                            >
                                <JetButton
                                    class="bg-blue-500 hover:bg-blue-700 text-white"
                                    @click="editaUsuario(usuario)"
                                >
                                    <font-awesome-icon icon="user-edit" />
                                </JetButton>
                            </td>
                        </tr>
                    </template>
                    <template #pagination>
                        <Paginador
                            :links="usuarios.links"
                            :total="usuarios.total"
                            :to="usuarios.to"
                            :from="usuarios.from"
                        />
                    </template>
                </Tabla>
                <div v-else class="text-center p-5 text-slate-500 text-md">
                    Sin Usuarios
                    <br />
                    <JetButton @click="nuevoUsuario" class="mt-2">
                        <font-awesome-icon icon="add" class="mr-2" />Agregar
                        Nuevo Usuario
                    </JetButton>
                </div>
            </div>
        </div>

        <form
            class="flex flex-col"
            @submit.prevent="accion === 'new' ? nuevoUsuarioDo : editaUsuarioDo"
        >
            <JetDialogModal
                :show="estadoModalUsuario"
                @close="estadoModalUsuario = false"
                max-width="md"
            >
                <template #title
                    >{{
                        accion === "new"
                            ? "Crear nuevo usuario"
                            : "Editar usuario"
                    }}
                    <JetDangerButton
                        v-if="accion === 'edit'"
                        @click="estadoModalEliminaUsuario = true"
                        class="-mt-1 float-right"
                        :disabled="idActual === 1 || form.processing"
                    >
                        <font-awesome-icon icon="trash" class="mr-2" />
                        Eliminar Usuario
                    </JetDangerButton>
                </template>
                <template #content>
                    <label class="block mb-1">
                        <JetLabel
                            for="name"
                            value="Nombre"
                            class="float-left"
                        />

                        <JetInput
                            id="name"
                            v-model="form.name"
                            placeholder="Juan Perez"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autofocus
                        />

                        <JetInputError :message="error.name" class="mt-2" />
                    </label>
                    <label class="block mb-1">
                        <JetLabel
                            for="titulo"
                            value="Titulo"
                            class="float-left"
                        />

                        <JetInput
                            id="titulo"
                            v-model="form.titulo"
                            placeholder="Ing."
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />

                        <JetInputError :message="error.titulo" class="mt-2" />
                    </label>
                    <label class="block mb-1">
                        <JetLabel
                            for="email"
                            value="Email"
                            class="float-left"
                        />
                        <JetInput
                            id="email"
                            placeholder="correo@dominio.com"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                        />
                        <JetInputError :message="error.email" class="mt-2" />
                    </label>
                    <label class="block">
                        <JetLabel
                            for="password"
                            value="Contraseña"
                            class="float-left"
                        />
                        <JetInput
                            id="password"
                            placeholder="C0n7Ras3ña.Se6uR4"
                            v-model="form.password"
                            type="text"
                            class="mt-1 block w-full font-mono"
                            required
                        />
                        <JetInputError :message="error.password" class="mt-2" />
                    </label>

                    <div class="pt-2 pb-1">
                        <div class="border-t border-gray-200" />
                    </div>
                    <label class="block">
                        <JetLabel for="rol" value="Rol" class="float-left" />
                        <Select
                            id="rol"
                            placeholder="Rol"
                            v-model="form.rol"
                            :value="form.rol"
                            class="mt-1 block w-full"
                            required
                            :options="roles"
                            :disabled="idActual === 1"
                        />
                        <JetInputError :message="error.rol" class="mt-2" />
                    </label>
                </template>
                <template #footer>
                    <div class="w-1/2">
                        <JetSecondaryButton
                            @click="estadoModalUsuario = false"
                            class="bg-opacity-95"
                            type="cancel"
                            :disabled="form.processing"
                            :class="{ 'opacity-25': form.processing }"
                            >Cancelar</JetSecondaryButton
                        >
                    </div>
                    <div class="w-1/2">
                        <JetButton
                            v-if="accion === 'new'"
                            @click="nuevoUsuarioDo"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            class="float-right"
                        >
                            <template v-if="form.processing">
                                <font-awesome-icon
                                    icon="spinner"
                                    class="mr-2 animate-spin"
                                />Creando...
                            </template>
                            <template v-else>
                                <font-awesome-icon
                                    icon="plus"
                                    class="mr-2"
                                />Crear Usuario
                            </template>
                        </JetButton>

                        <JetButton
                            v-if="accion === 'edit'"
                            @click="editaUsuarioDo"
                            :class="{ 'opacity-25': form.processing }"
                            class="float-right"
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
        <JetConfirmationModal
            :show="estadoModalEliminaUsuario"
            @close="estadoModalEliminaUsuario = false"
        >
            <template #title>Eliminar usuario</template>
            <template #content>
                <h4 class="block mb-1 text-lg">
                    ¿Realmente desea eliminar este usuario?
                </h4>
                <span class="text-red-600"
                    >Esta acción no se puede deshacer.</span
                >
            </template>
            <template #footer>
                <div class="w-1/2">
                    <JetSecondaryButton
                        @click="estadoModalEliminaUsuario = false"
                        class="bg-opacity-95"
                        type="cancel"
                        :disabled="form.processing"
                        :class="{ 'opacity-25': form.processing }"
                        >Cancelar</JetSecondaryButton
                    >
                </div>
                <div class="w-1/2">
                    <JetDangerButton
                        @click="borraUsuarioDo"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="float-right"
                    >
                        <font-awesome-icon icon="trash" class="mr-2" />Eliminar
                        Usuario
                    </JetDangerButton>
                </div>
            </template>
        </JetConfirmationModal>
    </AppLayout>
</template>
