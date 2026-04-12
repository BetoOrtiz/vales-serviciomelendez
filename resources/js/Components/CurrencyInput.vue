<template>
    <input ref="inputRef" type="text" class="border-gray-400
      focus:border-slate-400
      focus:ring
      focus:ring-slate-400
      focus:ring-opacity-50
      rounded-md
      shadow-sm" :placeholder="placeholder" />
</template>

<script>
import { useCurrencyInput } from "vue-currency-input";

export default {
    name: "CurrencyInput",
    props: {
        modelValue: Number,
        placeholder: String,
    },
    setup(props) {
        const { inputRef } = useCurrencyInput({ currency: 'MXN', "precision": 2, });

        return { inputRef };
    },
};
</script>