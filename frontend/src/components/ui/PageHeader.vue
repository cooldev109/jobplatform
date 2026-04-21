<!--
  PageHeader — consistent title row for inner app pages.
  Props:
    title (string, required), subtitle, backTo (router route name or path)
  Slots:
    actions — right-aligned primary/secondary actions
-->
<script setup>
import { useRouter } from 'vue-router'
defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: null },
  backTo: { type: [String, Object], default: null },
  backLabel: { type: String, default: '← Voltar' },
})
const router = useRouter()
function goBack() {
  if (!arguments.length) return
}
function handleBack(backTo) {
  if (typeof backTo === 'string') router.push(backTo)
  else if (backTo) router.push(backTo)
  else router.back()
}
</script>

<template>
  <header class="page-head">
    <div class="page-head__text">
      <h1>{{ title }}</h1>
      <p v-if="subtitle" class="subtitle">{{ subtitle }}</p>
    </div>
    <div class="page-head__actions">
      <slot name="actions" />
      <button v-if="backTo !== null" class="back-btn" @click="handleBack(backTo)" type="button">
        {{ backLabel }}
      </button>
    </div>
  </header>
</template>

<style scoped>
.page-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: var(--space-4);
  margin-bottom: var(--space-6);
  flex-wrap: wrap;
}
.page-head__text h1 {
  margin: 0 0 var(--space-1);
  font-size: var(--text-xl);
  letter-spacing: -0.01em;
  color: var(--ink-900);
}
.subtitle {
  color: var(--ink-500);
  margin: 0;
  font-size: var(--text-sm);
}
.page-head__actions {
  display: flex;
  gap: var(--space-2);
  align-items: center;
}
.back-btn {
  background: transparent;
  border: 1px solid var(--ink-200);
  color: var(--ink-700);
  padding: 0.5rem 0.9rem;
  border-radius: var(--radius-md);
  cursor: pointer;
  font-weight: 500;
  font-size: var(--text-sm);
  font-family: inherit;
  transition: background var(--t-fast), border-color var(--t-fast);
}
.back-btn:hover {
  background: var(--ink-100);
  border-color: var(--ink-300);
}
</style>
