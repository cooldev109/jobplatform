<script setup>
import { reactive, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../../stores/auth'
import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseButton from '../../components/ui/BaseButton.vue'
import BaseInput from '../../components/ui/BaseInput.vue'
import FormField from '../../components/ui/FormField.vue'
import AlertBanner from '../../components/ui/AlertBanner.vue'

const router = useRouter()
const auth = useAuthStore()
const { t } = useI18n()

const form = reactive({ email: '', password: '' })
const errors = ref({})
const submitting = ref(false)
const generalError = ref('')
const showPassword = ref(false)

async function submit() {
  submitting.value = true
  errors.value = {}
  generalError.value = ''
  try {
    await auth.login(form.email, form.password)
    router.push({ name: 'dashboard' })
  } catch (e) {
    if (e.response?.status === 422) errors.value = e.response.data.errors || {}
    else if (e.response?.status === 401) generalError.value = t('auth.login.invalidCredentials')
    else generalError.value = t('auth.login.genericError')
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <AuthLayout>
    <div class="form-wrap">
      <header class="form-header">
        <h2>{{ t('auth.login.title') }}</h2>
      </header>

      <transition name="fade">
        <AlertBanner v-if="generalError" variant="error">{{ generalError }}</AlertBanner>
      </transition>

      <form @submit.prevent="submit" novalidate>
        <FormField :label="t('auth.login.email')" for="login-email" :error="errors.email?.[0]">
          <BaseInput
            id="login-email"
            v-model="form.email"
            type="email"
            autocomplete="email"
            required
            :error="!!errors.email"
          >
            <template #leading>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z" />
                <polyline points="22,6 12,13 2,6" />
              </svg>
            </template>
          </BaseInput>
        </FormField>

        <FormField :label="t('auth.login.password')" for="login-password" :error="errors.password?.[0]">
          <BaseInput
            id="login-password"
            v-model="form.password"
            :type="showPassword ? 'text' : 'password'"
            autocomplete="current-password"
            required
            :error="!!errors.password"
          >
            <template #leading>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
              </svg>
            </template>
            <template #trailing>
              <button
                type="button"
                class="pw-toggle"
                @click="showPassword = !showPassword"
                :aria-label="showPassword ? t('auth.common.hidePassword') : t('auth.common.showPassword')"
              >
                <svg v-if="showPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                  <line x1="1" y1="1" x2="23" y2="23" />
                </svg>
                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </template>
          </BaseInput>
        </FormField>

        <BaseButton type="submit" :loading="submitting" full-width size="lg">
          {{ submitting ? t('auth.login.submitting') : t('auth.login.submit') }}
        </BaseButton>
      </form>

      <p class="switch">
        {{ t('auth.login.noAccount') }}
        <RouterLink to="/register">{{ t('auth.login.registerLink') }}</RouterLink>
      </p>
    </div>
  </AuthLayout>
</template>

<style scoped>
.form-wrap {
  width: 100%;
  max-width: 380px;
  margin: 0 auto;
}
.form-header {
  margin-bottom: var(--space-6);
}
.form-header h2 {
  margin: 0;
  font-size: var(--text-xl);
  color: var(--ink-900);
  font-weight: 700;
  letter-spacing: -0.01em;
}
form {
  display: grid;
  gap: 1.1rem;
}
.pw-toggle {
  width: 28px;
  height: 28px;
  display: grid;
  place-items: center;
  background: transparent;
  border: none;
  color: var(--ink-400);
  cursor: pointer;
  border-radius: var(--radius-sm);
  transition: color var(--t-fast), background var(--t-fast);
}
.pw-toggle svg { width: 18px; height: 18px; }
.pw-toggle:hover {
  color: var(--ink-700);
  background: var(--ink-100);
}
.switch {
  margin-top: var(--space-6);
  font-size: var(--text-sm);
  text-align: center;
  color: var(--ink-500);
}
.switch a {
  color: var(--brand-500);
  font-weight: 600;
  text-decoration: none;
}
.switch a:hover { text-decoration: underline; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-4px); }
</style>
