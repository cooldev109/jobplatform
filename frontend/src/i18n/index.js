import { createI18n } from 'vue-i18n'
import pt from './locales/pt.json'
import en from './locales/en.json'

export const SUPPORTED_LOCALES = ['pt', 'en']
export const DEFAULT_LOCALE = 'pt'

function detectLocale() {
  const stored = localStorage.getItem('locale')
  if (stored && SUPPORTED_LOCALES.includes(stored)) return stored

  const nav = (navigator.language || '').toLowerCase()
  if (nav.startsWith('pt')) return 'pt'
  if (nav.startsWith('en')) return 'en'
  return DEFAULT_LOCALE
}

const i18n = createI18n({
  legacy: false,
  locale: detectLocale(),
  fallbackLocale: DEFAULT_LOCALE,
  messages: { pt, en },
})

export function setLocale(lang) {
  if (!SUPPORTED_LOCALES.includes(lang)) return
  i18n.global.locale.value = lang
  localStorage.setItem('locale', lang)
  document.documentElement.lang = lang === 'pt' ? 'pt-BR' : 'en'
}

document.documentElement.lang = i18n.global.locale.value === 'pt' ? 'pt-BR' : 'en'

export default i18n
