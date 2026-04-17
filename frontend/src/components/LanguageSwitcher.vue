<script setup>
import { useI18n } from 'vue-i18n'
import { setLocale } from '../i18n'

const { locale } = useI18n()

function change(lang) {
  setLocale(lang)
}
</script>

<template>
  <div class="lang-switch" role="group" :aria-label="$t('language.label')">
    <button
      type="button"
      :class="{ active: locale === 'pt' }"
      :aria-pressed="locale === 'pt'"
      @click="change('pt')"
    >
      PT
    </button>
    <button
      type="button"
      :class="{ active: locale === 'en' }"
      :aria-pressed="locale === 'en'"
      @click="change('en')"
    >
      EN
    </button>
  </div>
</template>

<style scoped>
.lang-switch {
  display: inline-flex;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 3px;
  gap: 2px;
}
button {
  background: transparent;
  border: none;
  padding: 0.3rem 0.7rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: #6b7280;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.15s ease;
}
button:hover {
  color: #1f2937;
}
button.active {
  background: #fff;
  color: #2563eb;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
}
</style>
