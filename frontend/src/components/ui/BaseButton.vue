<!--
  BaseButton — the only button in the app.
  Props:
    variant: 'primary' | 'ghost' | 'danger'   (default 'primary')
    size:    'sm' | 'md' | 'lg'               (default 'md')
    type:    'button' | 'submit' | 'reset'    (default 'button')
    loading: boolean — shows spinner, disables click
    disabled: boolean
    fullWidth: boolean
  Slots:
    default — button label
    leading — icon before label
    trailing — icon after label
-->
<script setup>
defineProps({
  variant: { type: String, default: 'primary' },
  size: { type: String, default: 'md' },
  type: { type: String, default: 'button' },
  loading: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  fullWidth: { type: Boolean, default: false },
})
</script>

<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="['btn', `btn--${variant}`, `btn--${size}`, { 'btn--full': fullWidth, 'is-loading': loading }]"
  >
    <span v-if="loading" class="btn-spinner" aria-hidden="true"></span>
    <slot v-else name="leading" />
    <span class="btn-label"><slot /></span>
    <slot name="trailing" />
  </button>
</template>

<style scoped>
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-2);
  border: 1.5px solid transparent;
  border-radius: var(--radius-md);
  font-family: inherit;
  font-weight: 600;
  cursor: pointer;
  transition: transform var(--t-fast) var(--ease),
              box-shadow var(--t-fast) var(--ease),
              background-color var(--t-fast) var(--ease),
              border-color var(--t-fast) var(--ease),
              color var(--t-fast) var(--ease),
              opacity var(--t-fast);
  white-space: nowrap;
}
.btn:disabled { cursor: not-allowed; opacity: 0.65; }
.btn:focus-visible {
  outline: none;
  box-shadow: var(--ring-brand);
}

/* Variants */
.btn--primary {
  background: var(--gradient-brand);
  color: #fff;
  box-shadow: var(--shadow-brand);
}
.btn--primary:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: var(--shadow-brand-hover);
}
.btn--primary:active:not(:disabled) { transform: translateY(0); }

.btn--ghost {
  background: transparent;
  color: var(--ink-700);
  border-color: var(--ink-200);
}
.btn--ghost:hover:not(:disabled) {
  background: var(--ink-100);
  border-color: var(--ink-300);
}

.btn--danger {
  background: var(--danger);
  color: #fff;
}
.btn--danger:hover:not(:disabled) {
  background: #dc2626;
}

/* Sizes */
.btn--sm { padding: 0.4rem 0.75rem; font-size: var(--text-sm); }
.btn--md { padding: 0.68rem 1.1rem; font-size: var(--text-base); }
.btn--lg { padding: 0.85rem 1.4rem; font-size: var(--text-base); }

.btn--full { width: 100%; }

.btn-label { display: inline-flex; align-items: center; }

.btn-spinner {
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255, 255, 255, 0.45);
  border-top-color: currentColor;
  border-radius: 50%;
  animation: btn-spin 0.7s linear infinite;
}
.btn--ghost .btn-spinner { border-color: var(--ink-300); border-top-color: var(--ink-700); }
@keyframes btn-spin { to { transform: rotate(360deg); } }
</style>
