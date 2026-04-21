<!--
  Card — padded surface with soft shadow.
  Props:
    padding: 'sm' | 'md' | 'lg'   (default 'md')
    highlighted: boolean — adds a subtle brand-tinted border
-->
<script setup>
defineProps({
  padding: { type: String, default: 'md' },
  highlighted: { type: Boolean, default: false },
})
</script>

<template>
  <section :class="['card', `card--pad-${padding}`, { 'card--highlighted': highlighted }]">
    <slot />
  </section>
</template>

<style scoped>
.card {
  background: var(--surface);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-md);
}
.card--pad-sm { padding: var(--space-4); }
.card--pad-md { padding: var(--space-6); }
.card--pad-lg { padding: var(--space-8); }

.card--highlighted {
  border: 1px solid rgba(22, 163, 74, 0.1);
}
</style>
