<template>
  <div style="width: 100%; min-height: 100vh; background: #f3f4f6">
    <section class="post-detail surface-card">
      <header class="thread-header">
        <h1>{{ post?.title }}</h1>
        <p class="thread-meta">
          Oleh {{ post?.user?.name }} | {{ post?.category?.name }} |
          {{ new Date(post?.created_at).toLocaleDateString() }}
        </p>
      </header>

      <div class="post-content">
        <div class="post-content">
          <p>{{ post?.content }}</p>
        </div>
      </div>

      <div class="thread-divider"></div>
      <div class="comments-section">
        <h2 class="section-title">Komentar ({{ comments.length }})</h2>

        <article v-for="comment in comments" :key="comment.id" class="comment-item">
          <div class="user-avatar" style="background: #1d9bf0">
            {{ comment.user?.name?.charAt(0).toUpperCase() }}
          </div>

          <div class="comment-body">
            <span class="comment-author">{{ comment.user?.name }}</span>

            <p>{{ comment.content }}</p>

            <div class="comment-actions">
              <button
                @click="toggleCommentLike(comment)"
                class="like-btn"
                :class="{ liked: comment.is_liked }"
              >
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                  <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                  />
                </svg>
                <span class="like-count">{{ comment.likes_count ?? 0 }}</span>
              </button>
            </div>
            <div v-if="comment.user?.id === user.id">
              <div style="display: flex">
                <div
                  @click="handleEdit(comment.id, comment.content)"
                  style="
                    cursor: pointer;
                    background-color: lightgreen;
                    padding: 2px 20px;
                    border-radius: 20px;
                    color: white;
                  "
                >
                  Edit
                </div>
                <div
                @click="deleteComment(comment.id)"
                  style="
                    cursor: pointer;
                    background-color: red;
                    padding: 2px 20px;
                    border-radius: 20px;
                    color: white;
                  "
                >
                  Delete
                </div>
              </div>
              <div v-if="editId === comment.id">
                <textarea
                  placeholder="Ketik Komentar Anda"
                  v-model="commentEdit"
                  name=""
                  id=""
                  style="
                    margin-top: 10px;
                    border: 1px solid #ccc;
                    width: 100%;
                    height: 100px;
                    margin-bottom: 10px;
                    padding: 10px;
                    border-radius: 8px;
                  "
                >
                </textarea>
                <button
                  @click="updateComment"
                  class="post-comment-btn"
                  style="
                    cursor: pointer;
                    background-color: #3b82f6;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 999px;
                  "
                >
                  Update Komentar
                </button>
              </div>
            </div>
          </div>
        </article>

        <div v-if="comments.length === 0" style="padding: 20px; opacity: 0.6">
          Belum ada komentar.
        </div>
      </div>
      <div>
        <h1>Tulis Komentar</h1>
        <textarea
          v-model="comment"
          placeholder="Ketik Komentar Anda"
          name=""
          id=""
          style="
            border: 1px solid #ccc;
            width: 100%;
            height: 150px;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
          "
        >
        </textarea>
        <button
          @click="submitComment"
          class="post-comment-btn"
          style="
            cursor: pointer;
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 999px;
          "
        >
          Kirim Komentar
        </button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const user = JSON.parse(localStorage.getItem('user'))
const route = useRoute()
const comment = ref('')

const post = ref(null)
const comments = ref([])

const editId = ref(null)
const commentEdit = ref('')
const handleEdit = (id, comment) => {
  editId.value = id
  commentEdit.value = comment
}
const getAuthHeaders = () => ({
  Accept: 'application/json',
  Authorization: `Bearer ${localStorage.getItem('token')}`,
})
const updateComment = async () => {
  const response = await axios.put(
    `http://127.0.0.1:8000/api/comment/${editId.value}`,
    {
      content: commentEdit.value,
    },
    {
      headers: getAuthHeaders(),
    },
  )

    const target = comments.value.find((c) => c.id === editId.value)
    if (target) target.content = commentEdit.value

    editId.value = null
    commentEdit.value = ''
  console.log(response)
  alert(response.data.message)
}

const deleteComment = async (id) => {
  const response = axios.delete(`http://127.0.0.1:8000/api/comment/${id}`, {
    headers: getAuthHeaders()
  })
   comments.value = comments.value.filter(c => c.id !== id)
}
const toggleCommentLike = async (commentItem) => {
  const isLikedBeforeToggle = commentItem.is_liked
  const likesCountBeforeToggle = commentItem.likes_count

  commentItem.is_liked = !isLikedBeforeToggle
  commentItem.likes_count = (commentItem.likes_count ?? 0) + (isLikedBeforeToggle ? -1 : 1)

  try {
    if (isLikedBeforeToggle) {
      await axios.delete(`http://127.0.0.1:8000/api/comment/${commentItem.id}/unlike`, {
        headers: getAuthHeaders(),
      })
    } else {
      await axios.post(
        `http://127.0.0.1:8000/api/comment/${commentItem.id}/like`,
        {},
        {
          headers: getAuthHeaders(),
        },
      )
    }
  } catch (error) {
    console.error('Gagal menyimpan status like/unlike di server:', error)

    commentItem.is_liked = isLikedBeforeToggle
    commentItem.likes_count = likesCountBeforeToggle

    alert('Gagal memproses aksi Anda. Silakan coba lagi.')
  }
}

const submitComment = async () => {
  try {
    const id = route.params.id

    const response = await axios.post(
      'http://127.0.0.1:8000/api/comment',
      {
        post_id: id,
        content: comment.value,
      },
      { headers: getAuthHeaders() },
    )

    const newComment = {
      ...response.data.data,
    }
    comments.value.push(newComment)
    comment.value = ''
  } catch (error) {
    console.error(error)
  }
}

const getPostDetail = async () => {
  try {
    const id = route.params.id

    const res = await axios.get(`http://127.0.0.1:8000/api/post/${id}`, {
      headers: getAuthHeaders(),
    })

    const postData = res.data.data
    postData.is_liked = postData.is_liked ?? false
    postData.likes_count = postData.likes_count ?? 0

    const commentsData = (res.data.data.comments || []).map((c) => ({
      ...c,
      is_liked: c.is_liked ?? false,
      likes_count: c.likes_count ?? 0,
    }))

    post.value = postData
    comments.value = commentsData
  } catch (error) {
    console.error(error)
  }
}

onMounted(() => {
  getPostDetail()
})
</script>

<style scoped>
.post-detail {
  max-width: 900px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 30px;
  padding: 24px;
  background: white;
  box-shadow:
    0 4px 6px -1px rgb(0 0 0 / 0.1),
    0 2px 4px -2px rgb(0 0 0 / 0.1);
}

.thread-header h1 {
  font-size: 2.6em;
  margin-bottom: 6px;
}

.thread-meta {
  color: #6b7280;
  font-size: 0.9em;
}

.post-content {
  line-height: 1.7;
  color: #1f2937;
  font-size: 1.05em;
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.comments-section {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.comment-item {
  display: flex;
  gap: 16px;
  padding: 18px;
  border-radius: 16px;
  border: 1px solid rgba(11, 17, 27, 0.08);
  background: #fff;
  box-shadow: 0 15px 40px rgba(11, 17, 27, 0.08);
}

.comment-body {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.user-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: 700;
  color: white;
  flex-shrink: 0;
}

.comment-author {
  font-weight: 700;
  font-size: 1em;
}

.comment-actions {
  display: flex;
  align-items: center;
  gap: 16px;
  font-size: 0.9em;
  color: #6b7280;
}

.write-comment {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.like-btn {
  display: flex;
  align-items: center;
  gap: 4px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  color: #a0aec0;
  transition: color 0.2s;
  font-size: 0.9em;
}

.like-btn svg {
  fill: currentColor;
}

.like-btn:hover {
  color: #e53e3e;
}

.like-btn.liked {
  color: #e53e3e;
}

.post-actions {
  margin-top: 15px;
  padding-top: 15px;
  border-top: 1px solid rgba(11, 17, 27, 0.08);
  display: flex;
  justify-content: flex-start;
}
.post-actions .like-btn {
  font-size: 1em;
  padding: 8px 12px;
  border-radius: 999px;
  background-color: #f7fafc;
  border: 1px solid #e2e8f0;
}
.post-actions .like-btn:hover {
  background-color: #edf2f7;
}

.post-comment-btn {
  align-self: flex-end;
  border-radius: 999px;
}
</style>
