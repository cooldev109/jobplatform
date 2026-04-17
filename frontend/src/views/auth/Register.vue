<script setup>
import { reactive, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../../stores/auth'
import LanguageSwitcher from '../../components/LanguageSwitcher.vue'

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
  <div class="auth-shell">
    <div class="auth-card">
      <div class="auth-card__top">
        <h1>{{ $t('auth.register.title') }}</h1>
        <LanguageSwitcher />
      </div>

      <p v-if="generalError" class="error-banner">{{ generalError }}</p>

      <form @submit.prevent="submit" novalidate>
        <div class="type-picker" role="radiogroup" :aria-label="$t('auth.register.asCandidate')">
          <label :class="{ active: form.user_type === 'candidate' }">
            <input type="radio" v-model="form.user_type" value="candidate" />
            <span>{{ $t('auth.register.asCandidate') }}</span>
          </label>
          <label :class="{ active: form.user_type === 'company_owner' }">
            <input type="radio" v-model="form.user_type" value="company_owner" />
            <span>{{ $t('auth.register.asCompany') }}</span>
          </label>
        </div>

        <label>
          {{ $t('auth.register.name') }}
          <input v-model="form.name" type="text" autocomplete="name" required />
          <span v-if="errors.name" class="field-error">{{ errors.name[0] }}</span>
        </label>

        <label>
          {{ $t('auth.register.email') }}
          <input v-model="form.email" type="email" autocomplete="email" required />
          <span v-if="errors.email" class="field-error">{{ errors.email[0] }}</span>
        </label>

        <label>
          {{ $t('auth.register.password') }}
          <input v-model="form.password" type="password" autocomplete="new-password" required />
          <span v-if="errors.password" class="field-error">{{ errors.password[0] }}</span>
        </label>

        <label>
          {{ $t('auth.register.passwordConfirm') }}
          <input v-model="form.password_confirmation" type="password" autocomplete="new-password" required />
        </label>

        <button :disabled="submitting" type="submit">
          {{ submitting ? $t('auth.register.submitting') : $t('auth.register.submit') }}
        </button>
      </form>

      <p class="switch">
        {{ $t('auth.register.hasAccount') }}
        <RouterLink to="/login">{{ $t('auth.register.loginLink') }}</RouterLink>
      </p>
    </div>
  </div>
</template>

<style scoped>
.auth-shell {
  min-height: 100vh;
  display: grid;
  place-items: center;
  padding: 1rem;
}
.auth-card {
  background: #fff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  width: 100%;
  max-width: 440px;
}
.auth-card__top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1.5rem;
}
h1 {
  margin: 0;
  font-size: 1.5rem;
  line-height: 1.2;
}
form {
  display: grid;
  gap: 1rem;
}
label {
  display: grid;
  gap: 0.35rem;
  font-size: 0.9rem;
  font-weight: 500;
}
small {
  font-weight: 400;
  color: #6b7280;
  font-size: 0.8rem;
}
input[type='text'],
input[type='email'],
input[type='password'] {
  padding: 0.65rem 0.8rem;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
}
input:focus {
  outline: 2px solid #2563eb;
  outline-offset: 1px;
}
.type-picker {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.5rem;
}
.type-picker label {
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  padding: 0.75rem;
  text-align: center;
  cursor: pointer;
  font-weight: 500;
}
.type-picker label.active {
  border-color: #2563eb;
  background: #eff6ff;
  color: #1d4ed8;
}
.type-picker input {
  display: none;
}
button[type='submit'] {
  padding: 0.75rem 1rem;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
}
button[type='submit']:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.switch {
  margin-top: 1.5rem;
  font-size: 0.9rem;
  text-align: center;
}
.error-banner {
  background: #fee2e2;
  color: #991b1b;
  padding: 0.75rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}
.field-error {
  color: #b91c1c;
  font-size: 0.8rem;
  font-weight: 400;
}
</style>
