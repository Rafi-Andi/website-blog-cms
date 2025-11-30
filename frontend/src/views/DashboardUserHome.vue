<template>
  <main style="flex: 1; padding: 32px">
    <div
      style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
      "
    >
      <h2 style="font-size: 20px; font-weight: 600">Latest Posts</h2>
      <router-link
        :to="{ name: 'CreatePost' }"
        style="
          font-size: 14px;
          color: white;
          background: #3b82f6;
          padding: 8px 16px;
          border-radius: 6px;
          text-decoration: none;
        "
        >Create New Post</router-link
      >
    </div>

    <div
      style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px"
    >
      <div
        v-for="post in posts"
        :key="post.id"
        style="
          background: white;
          padding: 20px;
          border-radius: 8px;
          box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        "
      >
        <span
          style="
            font-size: 12px;
            padding: 4px 8px;
            background: #dbeafe;
            color: #2563eb;
            border-radius: 4px;
            display: inline-block;
          "
          >{{ post.category }}</span
        >
        <h3 style="margin-top: 12px; font-size: 18px; font-weight: 600">{{ post.title }}</h3>
        <p style="font-size: 14px; color: #6b7280; margin-top: 4px">{{ post.excerpt }}</p>
        <div
          style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
          "
        >
          <p style="font-size: 12px; color: #9ca3af">{{ post.date }}</p>
          <router-link
            :to="{ name: 'PostDetail', params: { id: post.id } }"
            style="font-size: 14px; color: #2563eb; background: none; border: none; cursor: pointer"
            >Read More</router-link
          >
        </div>
      </div>
    </div>
  </main> 
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

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
