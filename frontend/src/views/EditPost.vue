<template>
  <div class="container">
    <main class="content">
      <h2 class="title">Edit Post</h2>

      <div class="form-group">
        <label>Title</label>
        <input type="text" v-model="form.title" @input="makeSlug" />
      </div>

      <div class="form-group">
        <label>Slug</label>
        <input type="text" v-model="form.slug" />
      </div>

      <div class="form-group">
        <label>Category</label>
        <select v-model="form.category_id">
          <option v-for="c in categories" :key="c.id" :value="c.id">
            {{ c.name }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Content</label>
        <textarea v-model="form.content" rows="10"></textarea>
      </div>

      <button class="btn-publish" @click="updatePost">Update Post</button>
    </main>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const id = route.params.id

const categories = ref([])
const form = reactive({
  title: '',
  slug: '',
  category_id: '',
  content: '',
})

const getAuthHeaders = () => ({
  Accept: 'application/json',
  Authorization: `Bearer ${localStorage.getItem('token')}`,
})

async function loadPost() {
  const res = await axios.get(`http://127.0.0.1:8000/api/post/${id}`, {
    headers: getAuthHeaders()
  })

  const data = res.data.data
  form.title = data.title
  form.slug = data.slug
  form.category_id = data.category_id
  form.content = data.content
}

async function loadCategories() {
  const res = await axios.get(`http://127.0.0.1:8000/api/category`, {
    headers: getAuthHeaders()
  })

  categories.value = res.data.data
}

function makeSlug() {
  form.slug = form.title
    .toLowerCase()
    .replace(/ /g, '-')
    .replace(/[^\w-]+/g, '')
}

async function updatePost() {
  await axios.put(
    `http://127.0.0.1:8000/api/post/${id}`,
    {
      title: form.title,
      slug: form.slug,
      category_id: form.category_id,
      content: form.content,
    },
    { headers: getAuthHeaders() }
  )

  alert("Post updated")
  router.push({ name: 'MyPosts' })
}

onMounted(() => {
  loadCategories()
  loadPost()
})
</script>

<style scoped>
.container {
  display: flex;
  width: 100%;
  min-height: 100vh;
  background: #f5f5f5;
}

.sidebar {
  width: 230px;
  background: #1f2a40;
  color: #fff;
  padding-top: 20px;
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100%;
}

.profile {
  text-align: center;
  margin-bottom: 40px;
}

.avatar {
  width: 55px;
  height: 55px;
  border-radius: 50%;
  background: #4a77ff;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 5px;
  font-size: 22px;
}

.name {
  font-size: 14px;
}

.logout {
  margin-top: auto;
  padding: 12px 20px;
  color: #ff6b6b;
  text-decoration: none;
}

.content {
  padding: 40px;
  width: calc(100% - 230px);
}

.title {
  font-size: 22px;
  margin-bottom: 25px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 6px;
  font-size: 14px;
}

input,
select,
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #cfcfcf;
  border-radius: 4px;
  font-size: 14px;
  background: #fafafa;
}

textarea {
  resize: none;
}

.actions {
  margin-top: 15px;
}

.btn-publish {
  padding: 10px 22px;
  background: #28a745;
  border: none;
  color: white;
  border-radius: 4px;
  margin-right: 10px;
  cursor: pointer;
}

.btn-cancel {
  padding: 10px 22px;
  background: #d9d9d9;
  border: none;
  color: black;
  border-radius: 4px;
  cursor: pointer;
}
</style>
