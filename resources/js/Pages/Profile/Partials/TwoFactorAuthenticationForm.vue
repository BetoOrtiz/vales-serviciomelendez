<script setup>
import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { useForm, usePage } from "@inertiajs/vue3";
import JetActionSection from "@/Jetstream/ActionSection.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetConfirmsPassword from "@/Jetstream/ConfirmsPassword.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

const props = defineProps({
    requiresConfirmation: Boolean,
});

const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: "",
});

const twoFactorEnabled = computed(
    () => !enabling.value && usePage().props.user?.two_factor_enabled
);

watch(twoFactorEnabled, () => {
    if (!twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(
        "/user/two-factor-authentication",
        {},
        {
            preserveScroll: true,
            onSuccess: () =>
                Promise.all([
                    showQrCode(),
                    showSetupKey(),
                    showRecoveryCodes(),
                ]),
            onFinish: () => {
                enabling.value = false;
                confirming.value = props.requiresConfirmation;
            },
        }
    );
};

const showQrCode = () => {
    return axios.get("/user/two-factor-qr-code").then((response) => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get("/user/two-factor-secret-key").then((response) => {
        setupKey.value = response.data.secretKey;
    });
};

const showRecoveryCodes = () => {
    return axios.get("/user/two-factor-recovery-codes").then((response) => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post("/user/confirmed-two-factor-authentication", {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios
        .post("/user/two-factor-recovery-codes")
        .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete("/user/two-factor-authentication", {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <JetActionSection>
        <template #title> Autenticación de dos factores </template>

        <template #description>
            Agregue seguridad adicional a su cuenta mediante la autenticación de
            dos factores.
        </template>

        <template #content>
            <h3
                v-if="twoFactorEnabled && !confirming"
                class="text-lg font-medium text-gray-900"
            >
                Ha habilitado la autenticación de dos factores.
            </h3>

            <h3
                v-else-if="twoFactorEnabled && confirming"
                class="text-lg font-medium text-gray-900"
            >
                Terminar de habilitar la autenticación de dos factores.
            </h3>

            <h3 v-else class="text-lg font-medium text-gray-900">
                No ha habilitado la autenticación de dos factores.
            </h3>

            <div class="mt-3 max-w-xl text-sm text-gray-600">
                <p>
                    Cuando la autenticación de dos factores está habilitada, se
                    le solicitará un token seguro y aleatorio durante la
                    autenticación. Puede recuperar este token de la aplicación
                    Google Authenticator de su teléfono.
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p v-if="confirming" class="font-semibold">
                            Para terminar de habilitar la autenticación de dos
                            factores, escanee el siguiente código QR usando la
                            aplicación de autenticación de su teléfono o ingrese
                            la clave de configuración y proporcione el código
                            OTP generado.
                        </p>

                        <p v-else>
                            La autenticación de dos factores ahora está
                            habilitada. Escanee el siguiente código QR usando la
                            aplicación de autenticación de su teléfono o ingrese
                            la clave de configuración.
                        </p>
                    </div>

                    <div class="mt-4" v-html="qrCode" />

                    <div
                        class="mt-4 max-w-xl text-sm text-gray-600"
                        v-if="setupKey"
                    >
                        <p class="font-semibold">
                            Clave de configuración:
                            <span v-html="setupKey"></span>
                        </p>
                    </div>

                    <div v-if="confirming" class="mt-4">
                        <JetLabel for="code" value="Código" />

                        <JetInput
                            id="code"
                            v-model="confirmationForm.code"
                            type="text"
                            name="code"
                            class="block mt-1 w-1/2"
                            inputmode="numeric"
                            autofocus
                            autocomplete="one-time-code"
                            @keyup.enter="confirmTwoFactorAuthentication"
                        />

                        <JetInputError
                            :message="confirmationForm.errors.code"
                            class="mt-2"
                        />
                    </div>
                </div>

                <div v-if="recoveryCodes.length > 0 && !confirming">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            Guarde estos códigos de recuperación en un
                            administrador de contraseñas seguro. Se pueden usar
                            para recuperar el acceso a su cuenta si se pierde su
                            dispositivo de autenticación de dos factores.
                        </p>
                    </div>

                    <div
                        class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg"
                    >
                        <div v-for="code in recoveryCodes" :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div v-if="!twoFactorEnabled">
                    <JetConfirmsPassword
                        @confirmed="enableTwoFactorAuthentication"
                    >
                        <JetButton
                            type="button"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                        >
                            Habilitar
                        </JetButton>
                    </JetConfirmsPassword>
                </div>

                <div v-else>
                    <JetConfirmsPassword
                        @confirmed="confirmTwoFactorAuthentication"
                    >
                        <JetButton
                            v-if="confirming"
                            type="button"
                            class="mr-3"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                        >
                            Confirmar
                        </JetButton>
                    </JetConfirmsPassword>

                    <JetConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <JetSecondaryButton
                            v-if="recoveryCodes.length > 0 && !confirming"
                            class="mr-3"
                        >
                            Regenerar códigos de recuperación
                        </JetSecondaryButton>
                    </JetConfirmsPassword>

                    <JetConfirmsPassword @confirmed="showRecoveryCodes">
                        <JetSecondaryButton
                            v-if="recoveryCodes.length === 0 && !confirming"
                            class="mr-3"
                        >
                            Mostrar códigos de recuperación
                        </JetSecondaryButton>
                    </JetConfirmsPassword>

                    <JetConfirmsPassword
                        @confirmed="disableTwoFactorAuthentication"
                    >
                        <JetSecondaryButton
                            v-if="confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                        >
                            Cancelar
                        </JetSecondaryButton>
                    </JetConfirmsPassword>

                    <JetConfirmsPassword
                        @confirmed="disableTwoFactorAuthentication"
                    >
                        <JetDangerButton
                            v-if="!confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                        >
                            Deshabilitar
                        </JetDangerButton>
                    </JetConfirmsPassword>
                </div>
            </div>
        </template>
    </JetActionSection>
</template>
