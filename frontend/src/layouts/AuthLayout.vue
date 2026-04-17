<script setup>
import LanguageSwitcher from '../components/LanguageSwitcher.vue'
</script>

<template>
  <div class="auth-bg">
    <div class="auth-lang">
      <LanguageSwitcher />
    </div>

    <div class="auth-card" role="main">
      <aside class="auth-hero">
        <div class="auth-hero__logo" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2a10 10 0 0 1 10 10c-4 0-7 2-10 5-3-3-6-5-10-5A10 10 0 0 1 12 2Z" />
            <path d="M12 2v15" />
          </svg>
        </div>
        <div class="auth-hero__content">
          <h1 class="auth-hero__brand">{{ $t('common.brand') }}</h1>
          <p class="auth-hero__tagline">{{ $t('common.tagline') }}</p>
        </div>
        <ul class="auth-hero__list">
          <li>
            <span class="dot"></span>
            {{ $t('common.bullets.connect') }}
          </li>
          <li>
            <span class="dot"></span>
            {{ $t('common.bullets.jobs') }}
          </li>
          <li>
            <span class="dot"></span>
            {{ $t('common.bullets.grow') }}
          </li>
        </ul>
      </aside>

      <section class="auth-body">
        <slot />
      </section>
    </div>
  </div>
</template>

<style scoped>
.auth-bg {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
  background:
    radial-gradient(ellipse at top left, rgba(22, 163, 74, 0.15), transparent 55%),
    radial-gradient(ellipse at bottom right, rgba(5, 150, 105, 0.12), transparent 55%),
    linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
  position: relative;
}

.auth-lang {
  position: fixed;
  top: 1.25rem;
  right: 1.25rem;
  z-index: 10;
}

.auth-card {
  display: grid;
  grid-template-columns: 1.05fr 1fr;
  width: 100%;
  max-width: 960px;
  min-height: 560px;
  background: #fff;
  border-radius: 20px;
  box-shadow:
    0 1px 2px rgba(15, 23, 42, 0.04),
    0 20px 60px -20px rgba(15, 23, 42, 0.18);
  overflow: hidden;
}

.auth-hero {
  position: relative;
  padding: 3rem 2.5rem;
  color: #fff;
  background: linear-gradient(160deg, #15803d 0%, #16a34a 55%, #22c55e 100%);
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  overflow: hidden;
}

.auth-hero::before,
.auth-hero::after {
  content: '';
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}
.auth-hero::before {
  width: 280px;
  height: 280px;
  right: -80px;
  top: -80px;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.18), transparent 70%);
}
.auth-hero::after {
  width: 220px;
  height: 220px;
  left: -60px;
  bottom: -60px;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent 70%);
}

.auth-hero__logo {
  width: 48px;
  height: 48px;
  display: grid;
  place-items: center;
  background: rgba(255, 255, 255, 0.18);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 12px;
  color: #fff;
  position: relative;
  z-index: 1;
}
.auth-hero__logo svg {
  width: 26px;
  height: 26px;
}

.auth-hero__content {
  position: relative;
  z-index: 1;
}
.auth-hero__brand {
  font-size: 2rem;
  font-weight: 700;
  margin: 0 0 0.5rem;
  letter-spacing: -0.02em;
}
.auth-hero__tagline {
  margin: 0;
  opacity: 0.92;
  line-height: 1.5;
  font-size: 0.98rem;
}

.auth-hero__list {
  list-style: none;
  padding: 0;
  margin: auto 0 0;
  display: grid;
  gap: 0.85rem;
  position: relative;
  z-index: 1;
}
.auth-hero__list li {
  display: flex;
  align-items: center;
  gap: 0.7rem;
  font-size: 0.92rem;
  opacity: 0.94;
}
.dot {
  display: inline-block;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.9);
  flex-shrink: 0;
}

.auth-body {
  padding: 3rem 2.5rem;
  display: flex;
  align-items: center;
}

@media (max-width: 780px) {
  .auth-bg {
    padding: 0;
  }
  .auth-card {
    grid-template-columns: 1fr;
    border-radius: 0;
    min-height: 100vh;
  }
  .auth-hero {
    padding: 2rem 1.5rem;
    gap: 1rem;
  }
  .auth-hero__brand {
    font-size: 1.5rem;
  }
  .auth-hero__list {
    display: none;
  }
  .auth-body {
    padding: 2rem 1.5rem;
  }
  .auth-lang {
    top: 1rem;
    right: 1rem;
  }
}
</style>
