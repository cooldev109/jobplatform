<script setup>
import { reactive, ref, onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter, useRoute } from 'vue-router'
import api from '../lib/api'
import AppLayout from '../layouts/AppLayout.vue'
import { BR_STATES } from '../constants/states'

const { t } = useI18n()
const router = useRouter()
const route = useRoute()

const companyId = computed(() => route.params.id ? Number(route.params.id) : null)
const isEdit = computed(() => companyId.value !== null)

const form = reactive({
  nome_empresa: '',
  nome_responsavel: '',
  documento: '',
  email: '',
  telefone: '',
  cidade: '',
  estado: '',
  segmento: '',
  descricao_curta: '',
})
const errors = ref({})
const saving = ref(false)
const loading = ref(isEdit.value)
const success = ref(false)
const generalError = ref('')

onMounted(async () => {
  if (!isEdit.value) return
  try {
    const { data } = await api.get(`/companies/${companyId.value}`)
    const c = data.data
    Object.assign(form, {
      nome_empresa: c.nome_empresa,
      nome_responsavel: c.nome_responsavel,
      documento: c.documento_formatado || c.documento,
      email: c.email,
      telefone: c.telefone,
      cidade: c.cidade,
      estado: c.estado,
      segmento: c.segmento,
      descricao_curta: c.descricao_curta || '',
    })
  } catch (e) {
    generalError.value = t('common.genericError')
  } finally {
    loading.value = false
  }
})

async function submit() {
  saving.value = true
  errors.value = {}
  generalError.value = ''
  success.value = false
  try {
    if (isEdit.value) {
      await api.put(`/companies/${companyId.value}`, form)
      success.value = true
      setTimeout(() => (success.value = false), 3500)
    } else {
      const { data } = await api.post('/companies', form)
      router.push({ name: 'company-edit', params: { id: data.data.id } })
    }
  } catch (e) {
    if (e.response?.status === 422) errors.value = e.response.data.errors || {}
    else generalError.value = t('common.genericError')
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <AppLayout>
    <div class="page-head">
      <div>
        <h1>{{ isEdit ? t('company.editTitle') : t('company.createTitle') }}</h1>
        <p class="subtitle">{{ t('company.subtitle') }}</p>
      </div>
      <button class="ghost-btn" @click="router.push({ name: 'dashboard' })">
        ← {{ t('common.back') }}
      </button>
    </div>

    <div v-if="loading" class="loading">{{ t('common.loading') }}</div>

    <div v-else class="card">
      <transition name="fade">
        <div v-if="success" class="alert alert--success">{{ t('common.savedSuccessfully') }}</div>
      </transition>
      <transition name="fade">
        <div v-if="generalError" class="alert alert--error">{{ generalError }}</div>
      </transition>

      <form @submit.prevent="submit" novalidate>
        <div class="field">
          <label>{{ t('company.fields.nome_empresa') }}</label>
          <input v-model="form.nome_empresa" type="text" :class="{ err: errors.nome_empresa }" />
          <span v-if="errors.nome_empresa" class="err-text">{{ errors.nome_empresa[0] }}</span>
        </div>

        <div class="field">
          <label>{{ t('company.fields.nome_responsavel') }}</label>
          <input v-model="form.nome_responsavel" type="text" :class="{ err: errors.nome_responsavel }" />
          <span v-if="errors.nome_responsavel" class="err-text">{{ errors.nome_responsavel[0] }}</span>
        </div>

        <div class="field">
          <label>{{ t('company.fields.documento') }}</label>
          <input v-model="form.documento" type="text" :class="{ err: errors.documento }" />
          <small>{{ t('company.documentoHint') }}</small>
          <span v-if="errors.documento" class="err-text">{{ errors.documento[0] }}</span>
        </div>

        <div class="row">
          <div class="field field--grow">
            <label>{{ t('company.fields.email') }}</label>
            <input v-model="form.email" type="email" :class="{ err: errors.email }" />
            <span v-if="errors.email" class="err-text">{{ errors.email[0] }}</span>
          </div>
          <div class="field field--grow">
            <label>{{ t('company.fields.telefone') }}</label>
            <input v-model="form.telefone" type="tel" :class="{ err: errors.telefone }" />
            <span v-if="errors.telefone" class="err-text">{{ errors.telefone[0] }}</span>
          </div>
        </div>

        <div class="row">
          <div class="field field--grow">
            <label>{{ t('company.fields.cidade') }}</label>
            <input v-model="form.cidade" type="text" :class="{ err: errors.cidade }" />
            <span v-if="errors.cidade" class="err-text">{{ errors.cidade[0] }}</span>
          </div>
          <div class="field field--state">
            <label>{{ t('company.fields.estado') }}</label>
            <select v-model="form.estado" :class="{ err: errors.estado }">
              <option value=""></option>
              <option v-for="uf in BR_STATES" :key="uf" :value="uf">{{ uf }}</option>
            </select>
            <span v-if="errors.estado" class="err-text">{{ errors.estado[0] }}</span>
          </div>
        </div>

        <div class="field">
          <label>{{ t('company.fields.segmento') }}</label>
          <input v-model="form.segmento" type="text" :class="{ err: errors.segmento }" />
          <span v-if="errors.segmento" class="err-text">{{ errors.segmento[0] }}</span>
        </div>

        <div class="field">
          <label>{{ t('company.fields.descricao_curta') }}</label>
          <textarea v-model="form.descricao_curta" rows="4" :class="{ err: errors.descricao_curta }"></textarea>
          <small>{{ t('company.descriptionHint') }}</small>
          <span v-if="errors.descricao_curta" class="err-text">{{ errors.descricao_curta[0] }}</span>
        </div>

        <div class="actions">
          <button class="primary" type="submit" :disabled="saving">
            <span v-if="saving" class="spinner" aria-hidden="true"></span>
            {{ saving ? t('common.saving') : t('common.save') }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<style scoped>
.page-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
}
h1 {
  margin: 0 0 0.25rem;
  font-size: 1.5rem;
  letter-spacing: -0.01em;
}
.subtitle {
  color: #6b7280;
  margin: 0;
  font-size: 0.92rem;
}
.ghost-btn {
  background: transparent;
  border: 1px solid #e5e7eb;
  padding: 0.5rem 0.9rem;
  border-radius: 8px;
  cursor: pointer;
  color: #374151;
  font-weight: 500;
  font-size: 0.88rem;
}
.ghost-btn:hover {
  background: #f3f4f6;
}
.loading {
  text-align: center;
  padding: 3rem;
  color: #6b7280;
}
.card {
  background: #fff;
  padding: 2rem;
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px -4px rgba(0, 0, 0, 0.06);
}
form {
  display: grid;
  gap: 1.1rem;
}
.field {
  display: grid;
  gap: 0.35rem;
}
.field label {
  font-size: 0.82rem;
  font-weight: 600;
  color: #374151;
}
.field small {
  font-size: 0.78rem;
  color: #6b7280;
}
input, select, textarea {
  padding: 0.7rem 0.85rem;
  border: 1.5px solid #e5e7eb;
  border-radius: 10px;
  font-size: 0.95rem;
  color: #111827;
  background: #fff;
  font-family: inherit;
  transition: border-color 0.15s, box-shadow 0.15s;
}
textarea { resize: vertical; }
input:focus, select:focus, textarea:focus {
  outline: none;
  border-color: #16a34a;
  box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.12);
}
input.err, select.err, textarea.err {
  border-color: #ef4444;
}
.err-text {
  color: #b91c1c;
  font-size: 0.78rem;
}
.row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.8rem;
}
.row:has(.field--state) {
  grid-template-columns: 1fr auto;
}
.field--state {
  min-width: 110px;
}
.actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 0.5rem;
}
.primary {
  padding: 0.75rem 1.3rem;
  background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
  transition: transform 0.1s, box-shadow 0.15s;
}
.primary:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(22, 163, 74, 0.32);
}
.primary:disabled {
  opacity: 0.7;
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
@keyframes spin { to { transform: rotate(360deg); } }
.alert {
  padding: 0.75rem 1rem;
  border-radius: 10px;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}
.alert--success {
  background: #ecfdf5;
  color: #15803d;
  border: 1px solid #a7f3d0;
}
.alert--error {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s, transform 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}

@media (max-width: 600px) {
  .row {
    grid-template-columns: 1fr !important;
  }
}
</style>
