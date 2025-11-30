<template>
  <div class="main-layout">
    <aside class="sidebar">
      <Sidebar />
    </aside>

    <main class="content-view">
      <div class="content-inner">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import Sidebar from '../components/Sidebar.vue';
// Asumsikan Sidebar.vue berisi logika menu dan styling item aktif 
</script>

<style scoped>
.main-layout {
  display: grid;
  grid-template-columns: var(--sidebar-width) 1fr;
  min-height: 100vh;
}

.sidebar {
  background: linear-gradient(180deg, #0c111c, #111a29 60%, #0a0d14);
  color: #f0f0f0;
  position: fixed;
  height: 100vh;
  width: var(--sidebar-width);
  overflow-y: auto;
  padding: 30px 24px;
  border-right: 1px solid rgba(255, 255, 255, 0.08);
}

.content-view {
  grid-column: 2 / 3;
  background: linear-gradient(180deg, rgba(255, 255, 255, 0.8), #eef2fb 45%, #e2e9f6);
  padding: 40px;
  min-height: 100vh;
  overflow-x: hidden;
}

.content-inner {
  max-width: 1200px;
  margin: 0 auto;
  width: 100%;
}
</style>