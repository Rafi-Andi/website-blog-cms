<template>
  <div class="auth-card-content">
    <slot></slot>
  </div>
</template>

<script setup>
</script>

<style scoped>
.auth-card-content {
  width: 100%;
  padding: 40px;
  background-color: var(--color-bg-light);
  border-radius: 12px;
  box-shadow: var(--card-shadow);
  border: 1px solid var(--color-border);
}
</style>