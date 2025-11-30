<template>
  <div class="layout">
    <aside class="sidebar">
      <div class="profile-box">
        <div class="avatar">{{ user.name }}</div>
        <div class="name">Admin</div>
      </div>
      <nav class="menu" style="display: flex; flex-direction: column">
        <router-link
          :to="{ name: 'AdminDashboard' }"
          style="color: black; text-decoration: none; background-color: lightblue;"
          class="menu-item"
          active-class="active"
        >
          Dashboard
        </router-link>

        <router-link
          :to="{ name: 'ManageCategories' }"
          style="color: black; text-decoration: none"
          class="menu-item"
          active-class="active"
        >
          Categories
        </router-link>
      </nav>
      <div class="logout" @click="logout">Logout</div>

    </aside>

    <main class="content">
      <h2>Dashboard</h2>
      <p class="subtitle">Overview of posts by category</p>

      <div class="total-card">
        <div class="total-number" style="font-size: 15px;">Total Category : {{ categories?.length }}</div>
      </div>

      <div class="cards">
        <div class="cat-card" v-for="c in categories" :key="c.name">
          <div class="cat-name">{{ c.name }}</div>
          <div class="cat-count">{{ c.count }}</div>
          <div class="cat-posts">Posts</div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, computed, onMounted } from 'vue'
const active = ref('dashboard')

const categories = ref(null)
const user = JSON.parse(localStorage.getItem('user'))

const getCategories = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/category', {
    headers: {
      Accept: 'application/json',
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  const data = response.data
  categories.value = data.data
  console.log(categories.value)
}

const getAuthHeaders = () => ({
  Accept: "application/json",
  Authorization: `Bearer ${localStorage.getItem("token")}`,
});

const logout = async () => {
  try {
    await axios.post(
      "http://127.0.0.1:8000/api/auth/logout",
      {},
      { headers: getAuthHeaders() }
    );
  } catch (error) {
    console.error("Logout API error:", error);
  }

  localStorage.removeItem("token");
  localStorage.removeItem("user");
  localStorage.removeItem("role");

  window.location.href = "/login";
};


onMounted(() => {
  getCategories()
})
</script>

<style scoped>
:root {
  --sidebar-width: 220px;
  --bg-page: #f8f9fc;
  --sidebar-bg: #273142;
  --accent: #1f8bff;
}

* {
  box-sizing: border-box;
}

.layout {
  display: flex;
  min-height: 100vh;
  background: var(--bg-page);
  font-family: Arial, sans-serif;
}

.sidebar {
  width: var(--sidebar-width);
  background: var(--sidebar-bg);
  color: black;
  display: flex;
  flex-direction: column;
  padding: 20px;
}

.profile-box {
  text-align: center;
  margin-bottom: 40px;
}

.avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: #4c5a6e;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  margin: 0 auto 10px;
}

.name {
  font-size: 15px;
}

.menu {
  flex-grow: 1;
}

.menu-item {
  padding: 12px 15px;
  border-radius: 6px;
  cursor: pointer;
  margin-bottom: 8px;
  transition: background 0.15s ease;
}

.menu-item.active {
  background: var(--accent);
}

.menu-item:hover {
  background: var(--accent);
}

.logout {
  color: #ff6b6b;
  cursor: pointer;
  margin-top: auto;
}

.content {
  flex: 1;
  padding: 30px;
  overflow: auto;
}

h2 {
  margin: 0;
  font-size: 22px;
  color: #222;
}

.subtitle {
  color: gray;
  margin-top: 6px;
  margin-bottom: 22px;
}

.total-card {
  width: 120px;
  height: 120px;
  background: #fff;
  border-radius: 6px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.06);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-bottom: 30px;
}

.total-number {
  font-size: 32px;
  color: var(--accent);
  font-weight: 700;
}

.total-label {
  font-size: 14px;
  color: #333;
}

.cards {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  margin-top: 8px;
}

.cat-card {
  width: 150px;
  background: #fff;
  border-radius: 6px;
  padding: 15px;
  text-align: center;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.06);
  display: flex;
  flex-direction: column;
  align-items: center;
}

.dot {
  width: 14px;
  height: 14px;
  border-radius: 50%;
}

.cat-name {
  margin-top: 8px;
  font-size: 14px;
  color: #444;
}

.cat-count {
  font-size: 26px;
  font-weight: 700;
  margin-top: 6px;
  color: #222;
}

.cat-posts {
  font-size: 13px;
  color: gray;
  margin-top: 4px;
}

@media (max-width: 800px) {
  .layout {
    flex-direction: column;
  }
  .sidebar {
    width: 100%;
    flex-direction: row;
    align-items: center;
    padding: 12px;
  }
  .profile-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 0;
  }
  .menu {
    display: flex;
    gap: 8px;
    margin-left: 12px;
  }
  .content {
    padding: 16px;
  }
  .cards {
    gap: 12px;
  }
  .cat-card {
    width: 140px;
    padding: 12px;
  }
}
</style>
