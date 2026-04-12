<script setup>
import { computed, onMounted, onUnmounted, watch } from "vue";

const props = defineProps({
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

const emit = defineEmits(["close"]);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        emit("close");
    }
};

const closeOnEscape = (e) => {
    if (e.key === "Escape" && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));

onUnmounted(() => {
    document.removeEventListener("keydown", closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
        "3xl": "sm:max-w-3xl",
        "4xl": "sm:max-w-4xl",
        "5xl": "sm:max-w-5xl",
        "6xl": "sm:max-w-6xl",
    }[props.maxWidth];
});
</script>

<style>
.nested-enter-active,
.nested-leave-active {
    transition: all 0.2s ease-in-out;
}
/* delay leave of parent element */
.nested-leave-active {
    transition-delay: 0.15s;
}

.nested-enter-from,
.nested-leave-to {
    opacity: 0;
}

/* we can also transition nested elements using nested selectors */
.nested-enter-active .inner,
.nested-leave-active .inner {
    transition: all 0.2s ease-in-out;
}
/* delay enter of nested element */
.nested-enter-active .inner {
    transition-delay: 0.15s;
}

.nested-enter-from .inner,
.nested-leave-to .inner {
    transform: translateY(30px);
    /*
  	Hack around a Chrome 96 bug in handling nested opacity transitions.
    This is not needed in other browsers or Chrome 99+ where the bug
    has been fixed.
  */
    opacity: 0.001;
}
</style>

<template>
    <teleport to="body">
        <Transition name="nested">
            <div
                v-if="show"
                class="fixed inset-0 overflow-y-auto px-4 py-16 sm:px-0 z-50"
                scroll-region
            >
                <div class="fixed inset-0 transform transition-all outer">
                    <div
                        class="absolute inset-0 backdrop-blur-sm bg-gradient-radial from-slate-700/40 to-slate-800/80"
                    />
                </div>

                <div
                    class="mb-6 bg-white rounded-lg shadow-xl transform transition-all sm:w-full sm:mx-auto inner"
                    :class="maxWidthClass"
                >
                    <slot />
                </div>
            </div>
        </Transition>
    </teleport>
</template>
