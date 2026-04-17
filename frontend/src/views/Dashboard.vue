<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()

async function handleLogout() {
  await auth.logout()
  router.push({ name: 'login' })
}

const typeLabel = auth.isCandidate ? 'Candidato' : 'Empresa'
</script>

<template>
  <div class="dashboard">
    <header>
      <div>
        <strong>Vagas Agro</strong>
        <span class="badge">{{ typeLabel }}</span>
      </div>
      <button @click="handleLogout" class="logout">Sair</button>
    </header>

    <main>
      <h1>Olá, {{ auth.user?.name }}</h1>
      <p class="subtitle">Bem-vindo ao MVP. Aqui você verá seus dados e próximos passos.</p>

      <section class="card">
        <h2>Próximos passos</h2>
        <ul v-if="auth.isCandidate">
          <li>Completar perfil profissional <em>(Sprint 2)</em></li>
          <li>Explorar vagas publicadas <em>(Sprint 4)</em></li>
          <li>Candidatar-se a vagas <em>(Sprint 5)</em></li>
        </ul>
        <ul v-else>
          <li>Criar perfil da empresa <em>(Sprint 2)</em></li>
          <li>Publicar vagas <em>(Sprint 3)</em></li>
          <li>Receber candidaturas <em>(Sprint 6)</em></li>
        </ul>
      </section>
    </main>
  </div>
</template>

<style scoped>
.dashboard {
  min-height: 100vh;
}
header {
  background: #fff;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.badge {
  margin-left: 0.75rem;
  background: #eff6ff;
  color: #1d4ed8;
  padding: 0.2rem 0.6rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
}
.logout {
  background: transparent;
  border: 1px solid #d1d5db;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 500;
}
.logout:hover {
  background: #f3f4f6;
}
main {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem 1.5rem;
}
h1 {
  margin: 0 0 0.25rem;
}
.subtitle {
  color: #6b7280;
  margin-bottom: 2rem;
}
.card {
  background: #fff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}
.card h2 {
  margin-top: 0;
  font-size: 1.1rem;
}
.card ul {
  margin: 0;
  padding-left: 1.25rem;
}
.card li {
  margin-bottom: 0.4rem;
}
.card em {
  color: #9ca3af;
  font-size: 0.85rem;
}
</style>
