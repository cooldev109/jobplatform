<script setup>
import { reactive, ref, onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter, useRoute } from 'vue-router'
import api from '../lib/api'
import AppLayout from '../layouts/AppLayout.vue'
import PageHeader from '../components/ui/PageHeader.vue'
import Card from '../components/ui/Card.vue'
import BaseButton from '../components/ui/BaseButton.vue'
import BaseInput from '../components/ui/BaseInput.vue'
import BaseSelect from '../components/ui/BaseSelect.vue'
import BaseTextarea from '../components/ui/BaseTextarea.vue'
import FormField from '../components/ui/FormField.vue'
import AlertBanner from '../components/ui/AlertBanner.vue'
import { BR_STATES } from '../constants/states'

const { t } = useI18n()
const router = useRouter()
const route = useRoute()

const companyId = computed(() => (route.params.id ? Number(route.params.id) : null))
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
  } catch {
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
    <PageHeader
      :title="isEdit ? t('company.editTitle') : t('company.createTitle')"
      :subtitle="t('company.subtitle')"
      back-to="/dashboard"
      :back-label="'← ' + t('common.back')"
    />

    <div v-if="loading" class="loading">{{ t('common.loading') }}</div>

    <Card v-else padding="lg">
      <transition name="fade">
        <AlertBanner v-if="success" variant="success" class="mb">{{ t('common.savedSuccessfully') }}</AlertBanner>
      </transition>
      <transition name="fade">
        <AlertBanner v-if="generalError" variant="error" class="mb">{{ generalError }}</AlertBanner>
      </transition>

      <form @submit.prevent="submit" novalidate>
        <FormField :label="t('company.fields.nome_empresa')" :error="errors.nome_empresa?.[0]" required>
          <BaseInput v-model="form.nome_empresa" type="text" :error="!!errors.nome_empresa" />
        </FormField>

        <FormField :label="t('company.fields.nome_responsavel')" :error="errors.nome_responsavel?.[0]" required>
          <BaseInput v-model="form.nome_responsavel" type="text" :error="!!errors.nome_responsavel" />
        </FormField>

        <FormField
          :label="t('company.fields.documento')"
          :hint="t('company.documentoHint')"
          :error="errors.documento?.[0]"
          required
        >
          <BaseInput v-model="form.documento" type="text" :error="!!errors.documento" />
        </FormField>

        <div class="row-2">
          <FormField :label="t('company.fields.email')" :error="errors.email?.[0]" required>
            <BaseInput v-model="form.email" type="email" :error="!!errors.email" />
          </FormField>
          <FormField :label="t('company.fields.telefone')" :error="errors.telefone?.[0]" required>
            <BaseInput v-model="form.telefone" type="tel" :error="!!errors.telefone" />
          </FormField>
        </div>

        <div class="row">
          <FormField :label="t('company.fields.cidade')" :error="errors.cidade?.[0]" required class="grow">
            <BaseInput v-model="form.cidade" type="text" :error="!!errors.cidade" />
          </FormField>
          <FormField :label="t('company.fields.estado')" :error="errors.estado?.[0]" required class="state">
            <BaseSelect v-model="form.estado" :error="!!errors.estado">
              <option value=""></option>
              <option v-for="uf in BR_STATES" :key="uf" :value="uf">{{ uf }}</option>
            </BaseSelect>
          </FormField>
        </div>

        <FormField :label="t('company.fields.segmento')" :error="errors.segmento?.[0]" required>
          <BaseInput v-model="form.segmento" type="text" :error="!!errors.segmento" />
        </FormField>

        <FormField
          :label="t('company.fields.descricao_curta')"
          :hint="t('company.descriptionHint')"
          :error="errors.descricao_curta?.[0]"
        >
          <BaseTextarea v-model="form.descricao_curta" :rows="4" :error="!!errors.descricao_curta" />
        </FormField>

        <div class="actions">
          <BaseButton type="submit" :loading="saving" size="lg">
            {{ saving ? t('common.saving') : t('common.save') }}
          </BaseButton>
        </div>
      </form>
    </Card>
  </AppLayout>
</template>

<style scoped>
.loading {
  text-align: center;
  padding: var(--space-12);
  color: var(--ink-500);
}
form {
  display: grid;
  gap: 1.1rem;
}
.row-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-3);
}
.row {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: var(--space-3);
}
.state { min-width: 110px; }
.actions {
  display: flex;
  justify-content: flex-end;
  margin-top: var(--space-2);
}
.mb { margin-bottom: var(--space-4); }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-4px); }

@media (max-width: 600px) {
  .row, .row-2 { grid-template-columns: 1fr; }
}
</style>
