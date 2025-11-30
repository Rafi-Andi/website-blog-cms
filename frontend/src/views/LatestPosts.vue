<template>
  <div style="flex: 1; padding: 32px">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px">
      <h2 style="font-size: 20px; font-weight: bold">My Posts</h2>

      <router-link
        :to="{ name: 'CreatePost' }"
        style="
          padding: 8px 14px;
          background: #3b82f6;
          color: white;
          border-radius: 6px;
          text-decoration: none;
        "
      >
        Create Post
      </router-link>
    </div>

    <table
      style="
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
      "
    >
      <thead style="background: #e5e7eb">
        <tr>
          <th style="padding: 12px; text-align: left; font-size: 14px">Title</th>
          <th style="padding: 12px; text-align: left; font-size: 14px">Category</th>
          <th style="padding: 12px; text-align: left; font-size: 14px">Excerpt</th>
          <th style="padding: 12px; text-align: left; font-size: 14px">Date</th>
          <th style="padding: 12px; text-align: left; font-size: 14px">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="post in posts"
          :key="post.id"
          style="border-bottom: 1px solid #e5e7eb"
        >
          <td style="padding: 12px">{{ post.title }}</td>
          <td style="padding: 12px">{{ post.category }}</td>
          <td style="padding: 12px">{{ post.excerpt }}</td>
          <td style="padding: 12px">{{ post.date }}</td>

          <td style="padding: 12px; display: flex; gap: 8px">
            <router-link
              :to="{ name: 'EditPost', params: { id: post.id } }"
              style="
                padding: 6px 10px;
                background: #10b981;
                color: white;
                border-radius: 6px;
                text-decoration: none;
                font-size: 13px;
              "
            >
              Edit
            </router-link>

            <button
              @click="deletePost(post.id)"
              style="
                padding: 6px 10px;
                background: #ef4444;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 13px;
                cursor: pointer;
              "
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const posts = ref([])

const getPosts = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/myposts', {
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
}

const deletePost = async (id) => {
  await axios.delete(`http://127.0.0.1:8000/api/post/${id}`, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  posts.value = posts.value.filter((p) => p.id !== id)
}

onMounted(() => {
  getPosts()
})
</script>

<style scoped></style>
