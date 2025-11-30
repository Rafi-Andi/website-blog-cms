<template>
  <div class="auth-layout">
    <div class="auth-card surface-card">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
.auth-layout {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 24px;
  background: radial-gradient(circle at top, rgba(29, 155, 240, 0.15), transparent 60%),
    linear-gradient(180deg, #edf1f7, #e2e9f6 70%, #d9e1ef);
}

.auth-card {
  width: 100%;
  max-width: 420px;
  padding: 36px 32px;
  background: var(--color-bg-surface);
  border-radius: 18px;
  box-shadow: 0 25px 70px rgba(11, 17, 27, 0.25);
  border: 1px solid rgba(15, 23, 40, 0.08);
}

.auth-card .section-title {
  margin-top: 0;
  margin-bottom: 12px;
  font-size: 2rem;
}

.auth-card form {
  display: flex;
  flex-direction: column;
}

.auth-card input,
.auth-card textarea,
.auth-card select {
  border-radius: 12px;
}
</style>