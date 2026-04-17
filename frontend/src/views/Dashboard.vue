<script setup>
import { ref, onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import api from '../lib/api'
import AppLayout from '../layouts/AppLayout.vue'

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
    // swallow — empty states handle it
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
      <!-- Candidate: profile card + next steps -->
      <template v-if="auth.isCandidate">
        <section class="card primary-card">
          <div class="card__head">
            <h2>{{ t('profile.title') }}</h2>
            <span v-if="profileComplete" class="pill pill--ok">✓</span>
          </div>
          <p v-if="!profileComplete" class="muted">{{ t('profile.empty') }}</p>
          <p v-else class="muted">{{ profile.area_atuacao }} · {{ profile.cidade }} / {{ profile.estado }}</p>
          <button class="cta" @click="router.push({ name: 'candidate-profile' })">
            {{ profileComplete ? t('common.edit') : t('dashboard.profileCta') }}
          </button>
        </section>

        <section class="card">
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
        </section>
      </template>

      <!-- Company owner: companies list + next steps -->
      <template v-else>
        <section class="card primary-card">
          <div class="card__head">
            <h2>{{ t('dashboard.yourCompanies') }}</h2>
            <button class="cta-small" @click="router.push({ name: 'company-create' })">
              + {{ t('dashboard.companyCta') }}
            </button>
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
              <span class="chevron">›</span>
            </li>
          </ul>
        </section>

        <section class="card">
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
        </section>
      </template>
    </div>
  </AppLayout>
</template>

<style scoped>
.hero {
  margin-bottom: 2rem;
}
.hero h1 {
  margin: 0 0 0.35rem;
  font-size: 1.75rem;
  letter-spacing: -0.01em;
  color: #111827;
}
.hero p {
  margin: 0;
  color: #6b7280;
  font-size: 0.98rem;
}
.loading {
  text-align: center;
  padding: 3rem;
  color: #6b7280;
}
.grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 1.25rem;
}
.card {
  background: #fff;
  padding: 1.5rem;
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px -4px rgba(0, 0, 0, 0.06);
}
.card__head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.5rem;
}
.card h2 {
  margin: 0 0 0.5rem;
  font-size: 1.05rem;
  color: #111827;
}
.primary-card {
  border: 1px solid rgba(22, 163, 74, 0.1);
}
.muted {
  color: #6b7280;
  font-size: 0.92rem;
  margin: 0 0 1rem;
}
.cta {
  display: inline-block;
  background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
  color: #fff;
  border: none;
  padding: 0.65rem 1.1rem;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  box-shadow: 0 3px 10px rgba(22, 163, 74, 0.22);
  transition: transform 0.1s, box-shadow 0.15s;
}
.cta:hover {
  transform: translateY(-1px);
  box-shadow: 0 5px 14px rgba(22, 163, 74, 0.3);
}
.cta-small {
  background: transparent;
  color: #15803d;
  border: 1px solid #16a34a;
  padding: 0.4rem 0.85rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.85rem;
  cursor: pointer;
}
.cta-small:hover {
  background: #f0fdf4;
}
.pill {
  font-size: 0.72rem;
  padding: 0.18rem 0.55rem;
  border-radius: 999px;
  font-weight: 700;
}
.pill--ok {
  background: #ecfdf5;
  color: #15803d;
}
.company-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 0.4rem;
}
.company-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.85rem 1rem;
  border: 1px solid #f3f4f6;
  border-radius: 10px;
  cursor: pointer;
  transition: border-color 0.15s, background 0.15s;
}
.company-list li:hover {
  border-color: #d1fae5;
  background: #f0fdf4;
}
.company-list li > div {
  display: flex;
  flex-direction: column;
  gap: 0.15rem;
}
.company-list strong {
  color: #111827;
  font-size: 0.95rem;
}
.chevron {
  color: #9ca3af;
  font-size: 1.3rem;
  font-weight: 300;
}
.steps {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 0.65rem;
}
.steps li {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.92rem;
  color: #374151;
}
.step-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #d1d5db;
  flex-shrink: 0;
}
.steps li.done .step-dot {
  background: #16a34a;
}
.steps em {
  color: #9ca3af;
  font-size: 0.8rem;
  font-style: normal;
  margin-left: 0.2rem;
}

@media (max-width: 860px) {
  .grid {
    grid-template-columns: 1fr;
  }
}
</style>
