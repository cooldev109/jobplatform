<!--
  AlertBanner — inline notification above a form or in a page.
  Props:
    variant: 'success' | 'error' | 'info' | 'warning'   (default 'info')
  Slots:
    default — message
-->
<script setup>
defineProps({
  variant: { type: String, default: 'info' },
})
</script>

<template>
  <div :class="['alert', `alert--${variant}`]" role="alert">
    <span class="alert__icon" aria-hidden="true">
      <!-- error/warning/info: circle with !   |   success: check -->
      <svg v-if="variant === 'success'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12" />
      </svg>
      <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10" />
        <line x1="12" y1="8" x2="12" y2="12" />
        <line x1="12" y1="16" x2="12.01" y2="16" />
      </svg>
    </span>
    <span class="alert__msg"><slot /></span>
  </div>
</template>

<style scoped>
.alert {
  display: flex;
  align-items: flex-start;
  gap: 0.6rem;
  padding: 0.7rem 0.85rem;
  border-radius: var(--radius-md);
  font-size: var(--text-sm);
  border: 1px solid;
}
.alert__icon {
  flex-shrink: 0;
  margin-top: 1px;
  width: 18px;
  height: 18px;
  display: grid;
  place-items: center;
}
.alert__icon :deep(svg) { width: 18px; height: 18px; }

.alert--success {
  background: var(--success-bg);
  color: var(--brand-600);
  border-color: var(--success-border);
}
.alert--error {
  background: var(--danger-bg);
  color: var(--danger-text);
  border-color: var(--danger-border);
}
.alert--info {
  background: var(--info-bg);
  color: var(--info-text);
  border-color: var(--info-border);
}
.alert--warning {
  background: var(--warning-bg);
  color: var(--warning-text);
  border-color: var(--warning-border);
}
</style>
