<!--
  FormField — wraps label + input slot + optional hint + error message.
  Props:
    label, for (input id), hint, error (string from API), required
  Slots:
    default — the input/select/textarea component
-->
<script setup>
defineProps({
  label: { type: String, default: null },
  for: { type: String, default: null },
  hint: { type: String, default: null },
  error: { type: String, default: null },
  required: { type: Boolean, default: false },
})
</script>

<template>
  <div class="field">
    <label v-if="label" :for="for">
      {{ label }}
      <span v-if="required" class="req" aria-hidden="true">*</span>
    </label>
    <slot />
    <p v-if="hint && !error" class="hint">{{ hint }}</p>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<style scoped>
.field {
  display: grid;
  gap: 0.35rem;
}
label {
  font-size: var(--text-xs);
  font-weight: 600;
  color: var(--ink-700);
  letter-spacing: 0.01em;
}
.req {
  color: var(--danger);
  margin-left: 2px;
}
.hint {
  font-size: var(--text-xs);
  color: var(--ink-500);
  margin: 0;
}
.error {
  font-size: var(--text-xs);
  color: var(--danger-text);
  margin: 0;
}
</style>
