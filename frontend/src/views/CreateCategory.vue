<template>
  <div class="layout">
    <aside class="sidebar">
      <div class="profile-box">
        <div class="avatar">A</div>
        <div class="name">Admin</div>
      </div>
      <nav class="menu" style="display: flex; flex-direction: column; ">
        <router-link
          :to="{ name: 'AdminDashboard' }"
          style="color: black; text-decoration: none"
          class="menu-item"
          active-class="active"
        >
          Dashboard
        </router-link>

        <router-link
          :to="{ name: 'ManageCategories' }"
          style="color: black; text-decoration: none;"
          class="menu-item"
          active-class="active"
        >
          Categories
        </router-link>
      </nav>
      <div class="logout">Logout</div>
    </aside>

    <main class="content">
      <form class="form-box" @submit.prevent="submitForm">
        <label>Category Name</label>
        <input type="text" v-model="name" placeholder="Enter category name" @input="generateSlug" />
        <p v-if="errors.name" class="error-msg">{{ errors.name[0] }}</p>

        <label>Slug</label>
        <input type="text" v-model="slug" placeholder="category-slug" readonly />
        <p v-if="errors.slug" class="error-msg">{{ errors.slug[0] }}</p>

        <div class="btn-row">
          <button type="submit" style="color: white; background-color: blue" class="create-btn">
            Create
          </button>
        </div>
      </form>
    </main>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref } from 'vue'

const name = ref('')
const slug = ref('')
const errors = ref({})

const generateSlug = () => {
  slug.value = name.value
    .toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^\w-]/g, '')
  errors.value = {}
}

const submitForm = async () => {
  errors.value = {}
  try {
    const response = await axios.post(
      'http://127.0.0.1:8000/api/category',
      {
        name: name.value,
        slug: slug.value,
      },
      {
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      },
    )

    console.log('Category created:', response.data)
    alert('Category created!')
    name.value = ''
    slug.value = ''
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      alert('Unexpected error')
    }
  }
}
</script>

<style scoped>
:root {
  --sidebar-bg: #273142;
  --accent: #1f8bff;
}

* {
  box-sizing: border-box;
}

.layout {
  display: flex;
  min-height: 100vh;
  background: #f8f9fc;
  font-family: Arial, sans-serif;
}

.sidebar {
  width: 220px;
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

.menu-item {
  padding: 12px 15px;
  border-radius: 6px;
  margin-bottom: 8px;
  cursor: pointer;
}

.menu-item.active,
.menu-item:hover {
  background: var(--accent);
}

.logout {
  color: #ff6b6b;
  margin-top: auto;
  cursor: pointer;
}

.content {
  flex: 1;
  padding: 30px;
}

.form-box {
  background: white;
  padding: 25px 20px;
  border-radius: 8px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.08);
  width: 60%;
}

label {
  display: block;
  font-size: 14px;
  margin-top: 15px;
  margin-bottom: 6px;
  color: #333;
}

input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #dcdcdc;
  border-radius: 6px;
  font-size: 14px;
}

.error-msg {
  color: red;
  font-size: 13px;
  margin-top: 5px;
}

.btn-row {
  margin-top: 25px;
  display: flex;
}

.create-btn {
  padding: 10px 18px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
</style>
