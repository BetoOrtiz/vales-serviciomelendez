<template>
    <input
        ref="inputRef"
        type="text"
        class="border-gray-400 focus:border-slate-400 focus:ring focus:ring-slate-400 focus:ring-opacity-50 rounded-md disabled:text-gray-500 shadow-sm"
        :placeholder="placeholder"
    />
</template>

<script>
import { useCurrencyInput } from "vue-currency-input";

export default {
    name: "NumberInput",
    props: {
        modelValue: Number,
        placeholder: String,
    },
    setup(props) {
        const { inputRef } = useCurrencyInput({
            currency: "MXN",
            precision: 0,
            currencyDisplay: "hidden",
        });

        return { inputRef };
    },
};
</script>
