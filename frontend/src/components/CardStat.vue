<template>
  <div class="stat-card">
    <div class="card-header">
      <span class="dot" :style="{ backgroundColor: dotColor }"></span>
      <p class="category-name">{{ category }}</p>
    </div>
    <span class="post-count">{{ count }}</span>
    <p class="post-label">Posts</p>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  category: {
    type: String,
    required: true,
  },
  count: {
    type: Number,
    required: true,
  },
  dotColor: {
    type: String,
    default: '#ccc',
  }
});
</script>

<style scoped>
.stat-card {
  width: 100%;
  padding: 15px;
  background-color: var(--color-bg-light);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
}

.card-header {
  display: flex;
  align-items: center;
  margin-bottom: 5px;
}

.dot {
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  margin-right: 5px;
}
.category-name {
  font-size: 0.9em;
  font-weight: 500;
}
.post-count {
  font-size: 2em;
  font-weight: 600;
  display: block;
  margin-top: 5px;
}
.post-label {
  font-size: 0.85em;
  color: #888;
}
</style>