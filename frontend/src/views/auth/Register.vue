<script setup>
import { reactive, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../../stores/auth'
import AuthLayout from '../../layouts/AuthLayout.vue'

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
        <h2>{{ $t('auth.register.title') }}</h2>
      </header>

      <transition name="fade">
        <div v-if="generalError" class="alert" role="alert">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          <span>{{ generalError }}</span>
        </div>
      </transition>

      <form @submit.prevent="submit" novalidate>
        <div class="type-picker" role="radiogroup">
          <label :class="{ active: form.user_type === 'candidate' }">
            <input type="radio" v-model="form.user_type" value="candidate" />
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <span>{{ $t('auth.register.asCandidate') }}</span>
          </label>
          <label :class="{ active: form.user_type === 'company_owner' }">
            <input type="radio" v-model="form.user_type" value="company_owner" />
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 21h18" />
              <path d="M5 21V7l8-4v18" />
              <path d="M19 21V11l-6-4" />
              <path d="M9 9v.01" />
              <path d="M9 12v.01" />
              <path d="M9 15v.01" />
              <path d="M9 18v.01" />
            </svg>
            <span>{{ $t('auth.register.asCompany') }}</span>
          </label>
        </div>

        <div class="field">
          <label for="reg-name">{{ $t('auth.register.name') }}</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <input
              id="reg-name"
              v-model="form.name"
              type="text"
              autocomplete="name"
              required
              :class="{ 'has-error': errors.name }"
            />
          </div>
          <span v-if="errors.name" class="field-error">{{ errors.name[0] }}</span>
        </div>

        <div class="field">
          <label for="reg-email">{{ $t('auth.register.email') }}</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z" />
              <polyline points="22,6 12,13 2,6" />
            </svg>
            <input
              id="reg-email"
              v-model="form.email"
              type="email"
              autocomplete="email"
              required
              :class="{ 'has-error': errors.email }"
            />
          </div>
          <span v-if="errors.email" class="field-error">{{ errors.email[0] }}</span>
        </div>

        <div class="field">
          <label for="reg-password">{{ $t('auth.register.password') }}</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            <input
              id="reg-password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="new-password"
              required
              :class="{ 'has-error': errors.password }"
            />
            <button
              type="button"
              class="input-action"
              @click="showPassword = !showPassword"
              :aria-label="showPassword ? $t('auth.common.hidePassword') : $t('auth.common.showPassword')"
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
          </div>
          <span v-if="errors.password" class="field-error">{{ errors.password[0] }}</span>
        </div>

        <div class="field">
          <label for="reg-password-confirm">{{ $t('auth.register.passwordConfirm') }}</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            <input
              id="reg-password-confirm"
              v-model="form.password_confirmation"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="new-password"
              required
            />
          </div>
        </div>

        <button class="submit-btn" :disabled="submitting" type="submit">
          <span v-if="submitting" class="spinner" aria-hidden="true"></span>
          <span>{{ submitting ? $t('auth.register.submitting') : $t('auth.register.submit') }}</span>
        </button>
      </form>

      <p class="switch">
        {{ $t('auth.register.hasAccount') }}
        <RouterLink to="/login">{{ $t('auth.register.loginLink') }}</RouterLink>
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
  margin-bottom: 1.5rem;
}
.form-header h2 {
  margin: 0;
  font-size: 1.4rem;
  color: #111827;
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
  gap: 0.6rem;
  margin-bottom: 0.3rem;
}
.type-picker label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  padding: 0.9rem 0.5rem;
  border: 1.5px solid #e5e7eb;
  border-radius: 10px;
  cursor: pointer;
  font-size: 0.85rem;
  font-weight: 600;
  color: #6b7280;
  background: #fff;
  transition: all 0.15s;
}
.type-picker label svg {
  width: 22px;
  height: 22px;
}
.type-picker label:hover {
  border-color: #d1d5db;
  color: #374151;
}
.type-picker label.active {
  border-color: #16a34a;
  background: #f0fdf4;
  color: #15803d;
  box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}
.type-picker input {
  display: none;
}

.field {
  display: grid;
  gap: 0.35rem;
}
.field > label {
  font-size: 0.82rem;
  font-weight: 600;
  color: #374151;
}

.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
.input-icon {
  position: absolute;
  left: 0.85rem;
  width: 18px;
  height: 18px;
  color: #9ca3af;
  pointer-events: none;
  transition: color 0.15s;
}
.input-wrap input {
  flex: 1;
  width: 100%;
  padding: 0.72rem 0.8rem 0.72rem 2.5rem;
  border: 1.5px solid #e5e7eb;
  border-radius: 10px;
  font-size: 0.95rem;
  color: #111827;
  background: #fff;
  transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
}
.input-wrap input:focus {
  outline: none;
  border-color: #16a34a;
  box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.12);
}
.input-wrap:focus-within .input-icon {
  color: #16a34a;
}
.input-wrap input.has-error {
  border-color: #ef4444;
}
.input-wrap input.has-error:focus {
  box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
}

.input-action {
  position: absolute;
  right: 0.6rem;
  width: 28px;
  height: 28px;
  display: grid;
  place-items: center;
  background: transparent;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  border-radius: 6px;
  transition: color 0.15s, background 0.15s;
}
.input-action svg {
  width: 18px;
  height: 18px;
}
.input-action:hover {
  color: #374151;
  background: #f3f4f6;
}

.field-error {
  color: #b91c1c;
  font-size: 0.78rem;
}

.submit-btn {
  margin-top: 0.4rem;
  padding: 0.82rem 1rem;
  background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: transform 0.1s, box-shadow 0.15s, opacity 0.15s;
  box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
}
.submit-btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(22, 163, 74, 0.32);
}
.submit-btn:disabled {
  opacity: 0.75;
  cursor: not-allowed;
}

.spinner {
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255, 255, 255, 0.45);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin {
  to { transform: rotate(360deg); }
}

.switch {
  margin-top: 1.3rem;
  font-size: 0.88rem;
  text-align: center;
  color: #6b7280;
}
.switch a {
  color: #16a34a;
  font-weight: 600;
  text-decoration: none;
}
.switch a:hover {
  text-decoration: underline;
}

.alert {
  display: flex;
  align-items: flex-start;
  gap: 0.6rem;
  background: #fef2f2;
  color: #991b1b;
  padding: 0.7rem 0.85rem;
  border-radius: 10px;
  margin-bottom: 1rem;
  font-size: 0.88rem;
  border: 1px solid #fecaca;
}
.alert svg {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  margin-top: 1px;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s, transform 0.2s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>
