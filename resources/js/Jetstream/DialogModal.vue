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
    padding: {
        type: String,
        default: "px-4 py-3",
    },
    paddingContent: {
        type: String,
        default: "p-4",
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
        <div>
            <div
                class="font-semibold text-xl text-slate-800 leading-tight border-slate-200/95 border-b-2 border-b-solid bg-slate-100 rounded-t-lg sticky -top-16 z-50"
                :class="padding"
            >
                <slot name="title" />
            </div>

            <div class="w-full" :class="paddingContent">
                <slot name="content" />
            </div>
        </div>

        <div
            class="flex flex-row justify-end px-4 py-2 border-b-2 border-slate-200/95 border-t-2 border-t-solid bg-slate-100 rounded-b-lg"
        >
            <slot name="footer" />
        </div>
    </Modal>
</template>
