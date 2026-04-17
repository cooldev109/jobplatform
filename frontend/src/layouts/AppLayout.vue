<script setup>
import { useRouter, RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../stores/auth'
import LanguageSwitcher from '../components/LanguageSwitcher.vue'

const auth = useAuthStore()
const router = useRouter()
const { t } = useI18n()

async function handleLogout() {
  await auth.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <div class="app-shell">
    <header class="app-header">
      <div class="app-header__inner">
        <RouterLink :to="{ name: 'dashboard' }" class="brand">
          <span class="brand__logo" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2a10 10 0 0 1 10 10c-4 0-7 2-10 5-3-3-6-5-10-5A10 10 0 0 1 12 2Z" />
              <path d="M12 2v15" />
            </svg>
          </span>
          <span class="brand__name">{{ t('common.brand') }}</span>
        </RouterLink>

        <div class="app-header__user">
          <span class="badge">{{ auth.isCandidate ? t('userType.candidate') : t('userType.company_owner') }}</span>
          <span class="user-name">{{ auth.user?.name }}</span>
          <LanguageSwitcher />
          <button class="logout-btn" @click="handleLogout">{{ t('common.logout') }}</button>
        </div>
      </div>
    </header>

    <main class="app-main">
      <slot />
    </main>
  </div>
</template>

<style scoped>
.app-shell {
  min-height: 100vh;
  background: #f8fafc;
}
.app-header {
  background: #fff;
  border-bottom: 1px solid #e5e7eb;
  position: sticky;
  top: 0;
  z-index: 20;
}
.app-header__inner {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0.85rem 1.25rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}
.brand {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  text-decoration: none;
  color: inherit;
}
.brand__logo {
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  background: linear-gradient(135deg, #16a34a, #15803d);
  border-radius: 9px;
  color: #fff;
}
.brand__logo svg {
  width: 18px;
  height: 18px;
}
.brand__name {
  font-weight: 700;
  color: #111827;
  font-size: 1.05rem;
  letter-spacing: -0.01em;
}
.app-header__user {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  flex-wrap: wrap;
}
.badge {
  background: #ecfdf5;
  color: #15803d;
  padding: 0.25rem 0.6rem;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.02em;
  text-transform: uppercase;
}
.user-name {
  color: #374151;
  font-weight: 500;
  font-size: 0.9rem;
}
.logout-btn {
  background: transparent;
  border: 1px solid #d1d5db;
  color: #374151;
  padding: 0.45rem 0.9rem;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 500;
  font-size: 0.88rem;
  transition: background 0.15s, border-color 0.15s;
}
.logout-btn:hover {
  background: #f3f4f6;
  border-color: #9ca3af;
}
.app-main {
  max-width: 1100px;
  margin: 0 auto;
  padding: 2rem 1.25rem;
}

@media (max-width: 640px) {
  .user-name {
    display: none;
  }
  .app-main {
    padding: 1.5rem 1rem;
  }
}
</style>
