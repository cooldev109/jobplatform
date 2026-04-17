<script setup>
import { reactive, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../../stores/auth'
import AuthLayout from '../../layouts/AuthLayout.vue'

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
        <h2>{{ $t('auth.login.title') }}</h2>
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
        <div class="field">
          <label for="login-email">{{ $t('auth.login.email') }}</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z" />
              <polyline points="22,6 12,13 2,6" />
            </svg>
            <input
              id="login-email"
              v-model="form.email"
              type="email"
              autocomplete="email"
              required
              :class="{ 'has-error': errors.email }"
              :placeholder="$t('auth.login.email')"
            />
          </div>
          <span v-if="errors.email" class="field-error">{{ errors.email[0] }}</span>
        </div>

        <div class="field">
          <label for="login-password">{{ $t('auth.login.password') }}</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            <input
              id="login-password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="current-password"
              required
              :class="{ 'has-error': errors.password }"
              :placeholder="$t('auth.login.password')"
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

        <button class="submit-btn" :disabled="submitting" type="submit">
          <span v-if="submitting" class="spinner" aria-hidden="true"></span>
          <span>{{ submitting ? $t('auth.login.submitting') : $t('auth.login.submit') }}</span>
        </button>
      </form>

      <p class="switch">
        {{ $t('auth.login.noAccount') }}
        <RouterLink to="/register">{{ $t('auth.login.registerLink') }}</RouterLink>
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
  margin-bottom: 1.75rem;
}
.form-header h2 {
  margin: 0;
  font-size: 1.5rem;
  color: #111827;
  font-weight: 700;
  letter-spacing: -0.01em;
}

form {
  display: grid;
  gap: 1.1rem;
}

.field {
  display: grid;
  gap: 0.4rem;
}
.field label {
  font-size: 0.82rem;
  font-weight: 600;
  color: #374151;
  letter-spacing: 0.01em;
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
  padding: 0.75rem 0.8rem 0.75rem 2.5rem;
  border: 1.5px solid #e5e7eb;
  border-radius: 10px;
  font-size: 0.95rem;
  color: #111827;
  background: #fff;
  transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
}
.input-wrap input::placeholder {
  color: transparent;
}
.input-wrap input:focus {
  outline: none;
  border-color: #16a34a;
  box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.12);
}
.input-wrap input:focus + .input-action,
.input-wrap input:focus ~ .input-icon,
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
  font-size: 0.8rem;
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
.submit-btn:active:not(:disabled) {
  transform: translateY(0);
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
  margin-top: 1.5rem;
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
