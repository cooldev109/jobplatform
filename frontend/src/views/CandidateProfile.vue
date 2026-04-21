<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
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
const auth = useAuthStore()

const form = reactive({
  nome_completo: '',
  telefone: '',
  cidade: '',
  estado: '',
  area_atuacao: '',
  descricao_breve: '',
})
const errors = ref({})
const saving = ref(false)
const loading = ref(true)
const success = ref(false)
const generalError = ref('')
const hasProfile = ref(false)

onMounted(async () => {
  try {
    const { data } = await api.get('/candidate/profile')
    if (data.profile) {
      hasProfile.value = true
      Object.assign(form, {
        nome_completo: data.profile.nome_completo,
        telefone: data.profile.telefone,
        cidade: data.profile.cidade,
        estado: data.profile.estado,
        area_atuacao: data.profile.area_atuacao,
        descricao_breve: data.profile.descricao_breve || '',
      })
    } else if (auth.user?.name) {
      form.nome_completo = auth.user.name
    }
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
    await api.put('/candidate/profile', form)
    hasProfile.value = true
    success.value = true
    setTimeout(() => (success.value = false), 3500)
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
      :title="hasProfile ? t('profile.editTitle') : t('profile.title')"
      :subtitle="t('profile.subtitle')"
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
        <FormField :label="t('profile.fields.nome_completo')" :error="errors.nome_completo?.[0]" required>
          <BaseInput v-model="form.nome_completo" type="text" :error="!!errors.nome_completo" />
        </FormField>

        <FormField :label="t('profile.fields.telefone')" :error="errors.telefone?.[0]" required>
          <BaseInput v-model="form.telefone" type="tel" :error="!!errors.telefone" />
        </FormField>

        <div class="row">
          <FormField :label="t('profile.fields.cidade')" :error="errors.cidade?.[0]" required class="grow">
            <BaseInput v-model="form.cidade" type="text" :error="!!errors.cidade" />
          </FormField>
          <FormField :label="t('profile.fields.estado')" :error="errors.estado?.[0]" required class="state">
            <BaseSelect v-model="form.estado" :error="!!errors.estado">
              <option value=""></option>
              <option v-for="uf in BR_STATES" :key="uf" :value="uf">{{ uf }}</option>
            </BaseSelect>
          </FormField>
        </div>

        <FormField :label="t('profile.fields.area_atuacao')" :error="errors.area_atuacao?.[0]" required>
          <BaseInput v-model="form.area_atuacao" type="text" :error="!!errors.area_atuacao" />
        </FormField>

        <FormField
          :label="t('profile.fields.descricao_breve')"
          :hint="t('profile.descriptionHint')"
          :error="errors.descricao_breve?.[0]"
        >
          <BaseTextarea v-model="form.descricao_breve" :rows="4" :error="!!errors.descricao_breve" />
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
  .row { grid-template-columns: 1fr; }
}
</style>
