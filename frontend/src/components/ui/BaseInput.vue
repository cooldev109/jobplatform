<!--
  BaseInput — text-like inputs (text, email, password, tel, number, url).
  Use inside FormField for label/error/hint.
  Props:
    modelValue: string
    type: input type (default 'text')
    id, name, placeholder, autocomplete, required, disabled
    error: boolean — applies error styling
  Slots:
    leading  — icon before input
    trailing — icon/button after input (e.g., password toggle)
-->
<script setup>
defineProps({
  modelValue: { type: [String, Number], default: '' },
  type: { type: String, default: 'text' },
  id: { type: String, default: null },
  name: { type: String, default: null },
  placeholder: { type: String, default: null },
  autocomplete: { type: String, default: null },
  required: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  error: { type: Boolean, default: false },
})
defineEmits(['update:modelValue'])
</script>

<template>
  <div :class="['input-wrap', { 'has-leading': !!$slots.leading, 'has-trailing': !!$slots.trailing, 'has-error': error }]">
    <span v-if="$slots.leading" class="input-icon input-icon--leading" aria-hidden="true">
      <slot name="leading" />
    </span>
    <input
      :type="type"
      :id="id"
      :name="name"
      :placeholder="placeholder"
      :autocomplete="autocomplete"
      :required="required"
      :disabled="disabled"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <span v-if="$slots.trailing" class="input-icon input-icon--trailing">
      <slot name="trailing" />
    </span>
  </div>
</template>

<style scoped>
.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
input {
  width: 100%;
  padding: 0.7rem 0.85rem;
  border: 1.5px solid var(--ink-200);
  border-radius: var(--radius-md);
  font-size: var(--text-base);
  font-family: inherit;
  color: var(--ink-900);
  background: var(--surface);
  transition: border-color var(--t-fast), box-shadow var(--t-fast);
}
.has-leading input { padding-left: 2.5rem; }
.has-trailing input { padding-right: 2.5rem; }

input:focus {
  outline: none;
  border-color: var(--brand-500);
  box-shadow: var(--ring-brand);
}
input:disabled {
  background: var(--ink-50);
  cursor: not-allowed;
  opacity: 0.7;
}

.has-error input {
  border-color: var(--danger);
}
.has-error input:focus {
  box-shadow: var(--ring-danger);
}

.input-icon {
  position: absolute;
  display: grid;
  place-items: center;
  color: var(--ink-400);
  pointer-events: none;
  transition: color var(--t-fast);
}
.input-icon--leading {
  left: 0.85rem;
  width: 18px;
  height: 18px;
}
.input-icon--trailing {
  right: 0.6rem;
  pointer-events: auto;
}
.input-icon :deep(svg) {
  width: 18px;
  height: 18px;
}

.input-wrap:focus-within .input-icon--leading {
  color: var(--brand-500);
}
</style>
