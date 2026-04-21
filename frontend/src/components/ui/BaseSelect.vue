<!--
  BaseSelect — native select with consistent styling.
  Props:
    modelValue, id, name, required, disabled, error
  Slots:
    default — <option> elements
-->
<script setup>
defineProps({
  modelValue: { type: [String, Number, null], default: '' },
  id: { type: String, default: null },
  name: { type: String, default: null },
  required: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  error: { type: Boolean, default: false },
})
defineEmits(['update:modelValue'])
</script>

<template>
  <select
    :id="id"
    :name="name"
    :required="required"
    :disabled="disabled"
    :class="{ 'has-error': error }"
    :value="modelValue"
    @change="$emit('update:modelValue', $event.target.value)"
  >
    <slot />
  </select>
</template>

<style scoped>
select {
  width: 100%;
  padding: 0.7rem 2rem 0.7rem 0.85rem;
  border: 1.5px solid var(--ink-200);
  border-radius: var(--radius-md);
  font-size: var(--text-base);
  font-family: inherit;
  color: var(--ink-900);
  background-color: var(--surface);
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  appearance: none;
  transition: border-color var(--t-fast), box-shadow var(--t-fast);
}
select:focus {
  outline: none;
  border-color: var(--brand-500);
  box-shadow: var(--ring-brand);
}
select:disabled {
  background-color: var(--ink-50);
  cursor: not-allowed;
  opacity: 0.7;
}
select.has-error {
  border-color: var(--danger);
}
select.has-error:focus {
  box-shadow: var(--ring-danger);
}
</style>
