<script setup>
import Modal from "./Modal.vue";

const emit = defineEmits(["close"]);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit("close");
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-lg">
            <div class="sm:flex sm:items-start">
                <div
                    class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-20 sm:w-20"
                >
                    <svg
                        class="h-full w-full text-red-600 p-2 sm:p-4"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                </div>

                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left pl-4">
                    <h3 class="text-lg font-bold">
                        <slot name="title" />
                    </h3>

                    <div class="mt-2 text-lg">
                        <slot name="content" />
                    </div>
                </div>
            </div>
        </div>

        <div
            class="flex flex-row justify-end px-4 py-2 border-b-2 border-slate-200/95 border-t-2 border-t-solid bg-slate-100 rounded-b-lg"
        >
            <slot name="footer" />
        </div>
    </Modal>
</template>
