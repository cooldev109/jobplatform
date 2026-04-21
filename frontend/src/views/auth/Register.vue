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

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  user_type: 'candidate',
})
const errors = ref({})
const submitting = ref(false)
const generalError = ref('')
const showPassword = ref(false)

async function submit() {
  submitting.value = true
  errors.value = {}
  generalError.value = ''
  try {
    await auth.register({ ...form })
    router.push({ name: 'dashboard' })
  } catch (e) {
    if (e.response?.status === 422) errors.value = e.response.data.errors || {}
    else generalError.value = t('auth.register.genericError')
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <AuthLayout>
    <div class="form-wrap">
      <header class="form-header">
        <h2>{{ t('auth.register.title') }}</h2>
      </header>

      <transition name="fade">
        <AlertBanner v-if="generalError" variant="error">{{ generalError }}</AlertBanner>
      </transition>

      <form @submit.prevent="submit" novalidate>
        <div class="type-picker" role="radiogroup">
          <label :class="{ active: form.user_type === 'candidate' }">
            <input type="radio" v-model="form.user_type" value="candidate" />
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <span>{{ t('auth.register.asCandidate') }}</span>
          </label>
          <label :class="{ active: form.user_type === 'company_owner' }">
            <input type="radio" v-model="form.user_type" value="company_owner" />
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 21h18" />
              <path d="M5 21V7l8-4v18" />
              <path d="M19 21V11l-6-4" />
            </svg>
            <span>{{ t('auth.register.asCompany') }}</span>
          </label>
        </div>

        <FormField :label="t('auth.register.name')" for="reg-name" :error="errors.name?.[0]">
          <BaseInput id="reg-name" v-model="form.name" type="text" autocomplete="name" required :error="!!errors.name">
            <template #leading>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
            </template>
          </BaseInput>
        </FormField>

        <FormField :label="t('auth.register.email')" for="reg-email" :error="errors.email?.[0]">
          <BaseInput id="reg-email" v-model="form.email" type="email" autocomplete="email" required :error="!!errors.email">
            <template #leading>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z" />
                <polyline points="22,6 12,13 2,6" />
              </svg>
            </template>
          </BaseInput>
        </FormField>

        <FormField :label="t('auth.register.password')" for="reg-password" :error="errors.password?.[0]">
          <BaseInput
            id="reg-password"
            v-model="form.password"
            :type="showPassword ? 'text' : 'password'"
            autocomplete="new-password"
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

        <FormField :label="t('auth.register.passwordConfirm')" for="reg-password-confirm">
          <BaseInput
            id="reg-password-confirm"
            v-model="form.password_confirmation"
            :type="showPassword ? 'text' : 'password'"
            autocomplete="new-password"
            required
          >
            <template #leading>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
              </svg>
            </template>
          </BaseInput>
        </FormField>

        <BaseButton type="submit" :loading="submitting" full-width size="lg">
          {{ submitting ? t('auth.register.submitting') : t('auth.register.submit') }}
        </BaseButton>
      </form>

      <p class="switch">
        {{ t('auth.register.hasAccount') }}
        <RouterLink to="/login">{{ t('auth.register.loginLink') }}</RouterLink>
      </p>
    </div>
  </AuthLayout>
</template>

<style scoped>
.form-wrap {
  width: 100%;
  max-width: 420px;
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
  gap: 0.9rem;
}
.type-picker {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-2);
  margin-bottom: var(--space-1);
}
.type-picker label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: var(--space-2);
  padding: 0.9rem 0.5rem;
  border: 1.5px solid var(--ink-200);
  border-radius: var(--radius-md);
  cursor: pointer;
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--ink-500);
  background: var(--surface);
  transition: all var(--t-fast);
}
.type-picker label svg { width: 22px; height: 22px; }
.type-picker label:hover {
  border-color: var(--ink-300);
  color: var(--ink-700);
}
.type-picker label.active {
  border-color: var(--brand-500);
  background: var(--brand-50);
  color: var(--brand-600);
  box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}
.type-picker input { display: none; }

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
  margin-top: var(--space-5);
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
