<!--
  BaseTextarea.
  Props:
    modelValue, id, name, rows (default 4), required, disabled, error, placeholder
-->
<script setup>
defineProps({
  modelValue: { type: String, default: '' },
  id: { type: String, default: null },
  name: { type: String, default: null },
  rows: { type: Number, default: 4 },
  placeholder: { type: String, default: null },
  required: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  error: { type: Boolean, default: false },
})
defineEmits(['update:modelValue'])
</script>

<template>
  <textarea
    :id="id"
    :name="name"
    :rows="rows"
    :placeholder="placeholder"
    :required="required"
    :disabled="disabled"
    :class="{ 'has-error': error }"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  ></textarea>
</template>

<style scoped>
textarea {
  width: 100%;
  padding: 0.7rem 0.85rem;
  border: 1.5px solid var(--ink-200);
  border-radius: var(--radius-md);
  font-size: var(--text-base);
  font-family: inherit;
  color: var(--ink-900);
  background: var(--surface);
  resize: vertical;
  min-height: 4.5rem;
  transition: border-color var(--t-fast), box-shadow var(--t-fast);
}
textarea:focus {
  outline: none;
  border-color: var(--brand-500);
  box-shadow: var(--ring-brand);
}
textarea.has-error {
  border-color: var(--danger);
}
textarea.has-error:focus {
  box-shadow: var(--ring-danger);
}
textarea:disabled {
  background: var(--ink-50);
  cursor: not-allowed;
  opacity: 0.7;
}
</style>
