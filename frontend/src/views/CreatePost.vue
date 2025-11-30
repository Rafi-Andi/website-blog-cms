<template>
  <div class="container">
    <main class="content">
      <h2 class="title">Create New Post</h2>

      <div class="form-group">
        <label>Post Title</label>
        <input type="text" v-model="form.title" @input="makeSlug" placeholder="Enter post title" />
      </div>

      <div class="form-group">
        <label>Slug</label>
        <input type="text" v-model="form.slug" placeholder="Auto generated" />
      </div>

      <div class="form-group">
        <label>Category</label>
        <select v-model="form.category_id">
          <option disabled value="">Select a category</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">
            {{ c.name }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Content</label>
        <textarea
          v-model="form.content"
          rows="12"
          placeholder="Write your post content here..."
        ></textarea>
      </div>

      <div class="actions">
        <button class="btn-publish" @click="submitPost">Publish</button>
      </div>
    </main>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import axios from 'axios'

const categories = ref(null)

const getCategories = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/category', {
    headers: {
      Accept: 'application/json',
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  categories.value = response.data.data
}

onMounted(() => {
  getCategories()
})

const form = reactive({
  title: '',
  slug: '',
  category_id: '',
  content: '',
})

function makeSlug() {
  form.slug = form.title
    .toLowerCase()
    .replace(/ /g, '-')
    .replace(/[^\w-]+/g, '')
}

async function submitPost() {
  try {
    const response = await axios.post(
      'http://127.0.0.1:8000/api/post',
      {
        title: form.title,
        slug: form.slug,
        category_id: form.category_id,
        content: form.content,
      },
      {
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      }
    )

    console.log('Post created:', response.data)
    alert('Post berhasil dibuat')

  } catch (error) {
    console.log(error.response?.data)
    alert('Gagal membuat post')
  }
}
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
