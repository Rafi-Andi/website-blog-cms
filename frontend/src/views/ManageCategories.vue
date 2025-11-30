<template>
  <div class="layout">
    <aside class="sidebar">
      <div class="profile-box">
        <div class="avatar">{{ user.name }}</div>
        <div class="name">Admin</div>
      </div>
      <nav class="menu">
        <nav class="menu" style="display: flex; flex-direction: column">
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
            style="color: black; text-decoration: none; background-color: lightblue"
            class="menu-item"
            active-class="active"
          >
            Categories
          </router-link>
        </nav>
      </nav>
      <div class="logout" @click="logout">Logout</div>
    </aside>

    <main class="content">
      <div class="header-row">
        <h2>Manage Categories</h2>
        <button class="create-btn" @click="addCategory">Create Category</button>
      </div>
      <router-link style="text-decoration: none; color: black" :to="{ name: 'CreateCategory' }"
        >Tambah Kategori +</router-link
      >

      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(c, i) in categories" :key="c.slug">
            <td>{{ i + 1 }}</td>
            <td>{{ c.name }}</td>
            <td>{{ c.slug }}</td>
            <td class="action-col">
              <button class="edit-btn" @click="editCategory(c.id)">Edit</button>
              <button class="delete-btn" @click="deleteCategory(c.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </main>
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue'

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
  Accept: 'application/json',
  Authorization: `Bearer ${localStorage.getItem('token')}`,
})

const logout = async () => {
  try {
    await axios.post('http://127.0.0.1:8000/api/auth/logout', {}, { headers: getAuthHeaders() })
  } catch (error) {
    console.error('Logout API error:', error)
  }

  localStorage.removeItem('token')
  localStorage.removeItem('user')

  window.location.href = '/login'
}

onMounted(() => {
  getCategories()
})
const addCategory = () => {
  const name = prompt('Category name')
  if (!name) return
  categories.value.push({
    name,
    slug: name.toLowerCase().replace(/\s+/g, '-'),
  })
}

const editCategory = async (id) => {
  const name = prompt('Edit category')
  if (!name) return

  const index = categories.value.findIndex((c) => c.id === id)
  if (index === -1) {
    alert('Category tidak ditemukan')
    return
  }

  categories.value[index].name = name
  categories.value[index].slug = name.toLowerCase().replace(/\s+/g, '-')

  await axios.put(
    `http://127.0.0.1:8000/api/category/${id}`,
    {
      name: categories.value[index].name,
      slug: categories.value[index].slug,
    },
    {
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    },
  )

  alert('Category updated!')
}

const deleteCategory = async (id) => {
  if (confirm('Delete this category?')) {
    categories.value = categories.value.filter((c) => c.id !== id)
    const response = await axios.delete(`http://127.0.0.1:8000/api/category/${id}`, {
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
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

.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.create-btn {
  background: var(--accent);
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
}

.create-btn:hover {
  opacity: 0.8;
}

.table {
  width: 100%;
  margin-top: 25px;
  border-collapse: collapse;
  background: white;
}

.table th {
  text-align: left;
  background: #f2f2f2;
  padding: 12px;
  font-size: 14px;
}

.table td {
  padding: 12px;
  border-bottom: 1px solid #ececec;
}

.action-col {
  display: flex;
  gap: 10px;
}

.edit-btn {
  background: #f1c40f;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}

.delete-btn {
  background: #e74c3c;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}

.edit-btn:hover,
.delete-btn:hover {
  opacity: 0.8;
}
</style>
