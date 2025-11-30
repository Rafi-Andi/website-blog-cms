<template>
  <main style="display: flex; flex: 1; align-items: center; justify-content: center; padding: 32px">
    <div style="background-color: lightgray; padding: 20px; border-radius: 10px;">Halo {{ name }}, silahkan kelola postinganmu melalui menu MyPosts.</div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const name = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')).name : 'User'

const posts = ref([])

const getPosts = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/post', {
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })

    posts.value = response.data.data.map((item) => ({
      id: item.id,
      title: item.title,
      excerpt: item.excerpt,
      category: item.category?.name || 'No Category',
      date: item.created_at.slice(0, 10),
    }))
  } catch (error) {
    console.error(error)
  }
}

onMounted(() => {
  getPosts()
})
</script>

<style scoped></style>
