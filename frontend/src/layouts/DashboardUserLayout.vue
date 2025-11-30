<template>
  <div style="display: flex; min-height: 100vh; background: #f3f4f6">
    <aside
      style="
        width: 260px;
        background: #1f2937;
        color: white;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 24px;
      "
    >
      <div style="display: flex; align-items: center; gap: 12px">
        <div
          style="
            width: 48px;
            height: 48px;
            border-radius: 9999px;
            background: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
          "
        >
          U
        </div>
        <div>
          <p style="font-size: 14px">{{ user.name }}</p>
          <p style="font-size: 12px; color: #d1d5db">Dashboard</p>
        </div>
      </div>

      <nav style="display: flex; flex-direction: column; gap: 8px">
        <router-link :to="{ name: 'UserDashboardMain' }" class="nav-link" exact>Dashboard</router-link>

        <router-link :to="{ name: 'UserDashboardHome' }" class="nav-link">HomePage</router-link>

        <router-link :to="{ name: 'MyPosts' }" class="nav-link">MyPosts</router-link>
      </nav>

      <a
        @click="logout"
        style="cursor: pointer; color: #f87171; font-size: 14px; margin-top: auto"
      >
        Logout
      </a>
    </aside>

    <router-view></router-view>
  </div>
</template>

<script setup>
import axios from "axios";

const user = JSON.parse(localStorage.getItem("user"));

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
</script>

<style scoped>
.nav-link {
  padding: 8px 12px;
  background: #374151;
  border-radius: 6px;
  color: white;
  text-decoration: none;
  display: block;
}

.nav-link.router-link-active {
  background: #2563eb;
}

.nav-link.router-link-exact-active {
  background: #1d4ed8;
}

.nav-link.router-link-active {
  background: #374151;
}
.nav-link.router-link-exact-active {
  background: #1d4ed8;
}
</style>
