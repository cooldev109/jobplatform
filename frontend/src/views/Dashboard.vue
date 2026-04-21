<script setup>
import { ref, onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import api from '../lib/api'
import AppLayout from '../layouts/AppLayout.vue'
import Card from '../components/ui/Card.vue'
import BaseButton from '../components/ui/BaseButton.vue'

const auth = useAuthStore()
const router = useRouter()
const { t } = useI18n()

const profile = ref(null)
const companies = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    if (auth.isCandidate) {
      const { data } = await api.get('/candidate/profile')
      profile.value = data.profile
    } else if (auth.isCompanyOwner) {
      const { data } = await api.get('/companies')
      companies.value = data.data
    }
  } catch {
    /* empty states handle it */
  } finally {
    loading.value = false
  }
})

const profileComplete = computed(() => profile.value !== null)
</script>

<template>
  <AppLayout>
    <section class="hero">
      <h1>{{ t('dashboard.greeting', { name: auth.user?.name }) }}</h1>
      <p>{{ t('dashboard.subtitle') }}</p>
    </section>

    <div v-if="loading" class="loading">{{ t('common.loading') }}</div>

    <div v-else class="grid">
      <template v-if="auth.isCandidate">
        <Card highlighted>
          <div class="card-head">
            <h2>{{ t('profile.title') }}</h2>
            <span v-if="profileComplete" class="pill pill--ok">✓</span>
          </div>
          <p v-if="!profileComplete" class="muted">{{ t('profile.empty') }}</p>
          <p v-else class="muted">{{ profile.area_atuacao }} · {{ profile.cidade }} / {{ profile.estado }}</p>
          <BaseButton @click="router.push({ name: 'candidate-profile' })">
            {{ profileComplete ? t('common.edit') : t('dashboard.profileCta') }}
          </BaseButton>
        </Card>

        <Card>
          <h2>{{ t('dashboard.nextStepsTitle') }}</h2>
          <ul class="steps">
            <li :class="{ done: profileComplete }">
              <span class="step-dot"></span>
              {{ t('dashboard.candidate.profile') }}
            </li>
            <li>
              <span class="step-dot"></span>
              {{ t('dashboard.candidate.browse') }} <em>(Sprint 4)</em>
            </li>
            <li>
              <span class="step-dot"></span>
              {{ t('dashboard.candidate.apply') }} <em>(Sprint 5)</em>
            </li>
          </ul>
        </Card>
      </template>

      <template v-else>
        <Card highlighted>
          <div class="card-head">
            <h2>{{ t('dashboard.yourCompanies') }}</h2>
            <BaseButton variant="ghost" size="sm" @click="router.push({ name: 'company-create' })">
              + {{ t('dashboard.companyCta') }}
            </BaseButton>
          </div>
          <p v-if="companies.length === 0" class="muted">{{ t('dashboard.noCompaniesYet') }}</p>
          <ul v-else class="company-list">
            <li
              v-for="c in companies"
              :key="c.id"
              @click="router.push({ name: 'company-edit', params: { id: c.id } })"
            >
              <div>
                <strong>{{ c.nome_empresa }}</strong>
                <span class="muted">{{ c.segmento }} · {{ c.cidade }}/{{ c.estado }}</span>
              </div>
              <span class="chevron" aria-hidden="true">›</span>
            </li>
          </ul>
        </Card>

        <Card>
          <h2>{{ t('dashboard.nextStepsTitle') }}</h2>
          <ul class="steps">
            <li :class="{ done: companies.length > 0 }">
              <span class="step-dot"></span>
              {{ t('dashboard.company.profile') }}
            </li>
            <li>
              <span class="step-dot"></span>
              {{ t('dashboard.company.publish') }} <em>(Sprint 3)</em>
            </li>
            <li>
              <span class="step-dot"></span>
              {{ t('dashboard.company.receive') }} <em>(Sprint 6)</em>
            </li>
          </ul>
        </Card>
      </template>
    </div>
  </AppLayout>
</template>

<style scoped>
.hero {
  margin-bottom: var(--space-8);
}
.hero h1 {
  margin: 0 0 var(--space-1);
  font-size: 1.75rem;
  letter-spacing: -0.01em;
  color: var(--ink-900);
}
.hero p {
  margin: 0;
  color: var(--ink-500);
  font-size: var(--text-base);
}
.loading {
  text-align: center;
  padding: var(--space-12);
  color: var(--ink-500);
}
.grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: var(--space-5);
}
.card-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: var(--space-3);
  margin-bottom: var(--space-2);
}
h2 {
  margin: 0 0 var(--space-2);
  font-size: var(--text-lg);
  color: var(--ink-900);
}
.muted {
  color: var(--ink-500);
  font-size: var(--text-sm);
  margin: 0 0 var(--space-4);
}
.pill {
  font-size: var(--text-xs);
  padding: 0.18rem 0.55rem;
  border-radius: var(--radius-full);
  font-weight: 700;
}
.pill--ok {
  background: var(--success-bg);
  color: var(--brand-600);
}
.company-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: var(--space-1);
}
.company-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.85rem 1rem;
  border: 1px solid var(--ink-100);
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: border-color var(--t-fast), background var(--t-fast);
}
.company-list li:hover {
  border-color: var(--brand-200);
  background: var(--brand-50);
}
.company-list li > div {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.company-list strong {
  color: var(--ink-900);
  font-size: var(--text-base);
}
.chevron {
  color: var(--ink-400);
  font-size: 1.3rem;
  font-weight: 300;
}
.steps {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: var(--space-2);
}
.steps li {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  font-size: var(--text-sm);
  color: var(--ink-700);
}
.step-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--ink-300);
  flex-shrink: 0;
}
.steps li.done .step-dot {
  background: var(--brand-500);
}
.steps em {
  color: var(--ink-400);
  font-size: var(--text-xs);
  font-style: normal;
  margin-left: 0.2rem;
}

@media (max-width: 860px) {
  .grid { grid-template-columns: 1fr; }
}
</style>
