<template>
  <section class="auth-panel">
    <header class="auth-panel__header">
      <p class="section-title">Welcome</p>
      <p class="subtitle">Register to your Blog CMS account</p>
    </header>

    <form @submit.prevent="handleRegister" class="auth-form">
      <label for="name">Name</label>
      <input type="text" id="name" placeholder="Enter your name" v-model="name" required />
      <span v-if="nameError" class="text-error" style="color: red; font-size: 18px">
        <ul>
          <li v-for="(name, index) in nameError" :key="index">
            {{ name }}
          </li>
        </ul>
      </span>
      <label for="email">Email</label>
      <input type="text" id="email" placeholder="Enter your email" v-model="email" required />
      <span v-if="emailError" class="text-error" style="color: red; font-size: 18px">
        <ul>
          <li v-for="(email, index) in emailError" :key="index">
            {{ email }}
          </li>
        </ul>
      </span>
      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        placeholder="Enter your password"
        v-model="password"
        required
      />
      <span v-if="passwordError" class="text-error" style="color: red; font-size: 18px">
        <ul>
          <li v-for="(password, index) in passwordError" :key="index">
            {{ password }}
          </li>
        </ul>
      </span>

      <button type="submit" class="btn btn-primary">
        <span v-if="isLoading">Proses..</span><span v-else>Register</span>
      </button>
    </form>

    <p class="register-link">
      have an account?
      <router-link :to="{ name: 'Login' }">Login here</router-link>
    </p>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import router from '@/router'

const name = ref('')
const email = ref('')
const password = ref('')
const emailError = ref(null)
const nameError = ref(null)
const passwordError = ref(null)
const isLoading = ref(false)

const handleRegister = async () => {
  try {
    isLoading.value = true

    const response = await axios.post('http://127.0.0.1:8000/api/auth/register', {
      name: name.value,
      email: email.value,
      password: password.value,
    })

    const data = response.data
    console.log('Registration successful:', data)
    alert('Registration successful!')
    router.push({ name: 'UserDashboard' })
    localStorage.setItem('token', data.data.token)
    localStorage.setItem('role', data.data.role)
    localStorage.setItem('user', JSON.stringify(data.data))
  } catch (error) {
    console.log('Registration failed:', error)
    emailError.value = error.response.data.errors.email
    passwordError.value = error.response.data.errors.password
    nameError.value = error.response.data.errors.name

    console.log('Email Error:', emailError.value)
    console.log('Password Error:', passwordError.value)
    console.log('Name Error:', nameError.value)
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
:root {
  --color-primary: #1e88e5;
  --color-accent-blue: #1e88e5;
  --color-text-primary: #333;
  --color-text-muted: #666;
  --color-border: #ddd;
  --color-error: #e53935;
}

.auth-panel {
  display: flex;
  flex-direction: column;
  gap: 32px;
  padding: 40px;
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  margin: 40px auto;
  border: 1px solid var(--color-border);
}

.auth-panel__header {
  text-align: center;
  margin-bottom: 16px;
}

.auth-panel__header .section-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--color-text-primary);
  margin-bottom: 4px;
}

.auth-panel__header .subtitle {
  font-size: 1rem;
  color: var(--color-text-muted);
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.auth-form label {
  font-weight: 600;
  font-size: 0.9rem;
  color: var(--color-text-primary);
  margin-bottom: 4px;
}

.auth-form input[type='email'],
.auth-form input[type='text'],
.auth-form input[type='password'] {
  padding: 12px 15px;
  border: 1px solid var(--color-border);
  border-radius: 8px;
  font-size: 1rem;
  transition:
    border-color 0.3s ease,
    box-shadow 0.3s ease;
  width: 100%;
}

.auth-form input:focus {
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.2);
  outline: none;
}

.text-error {
  color: var(--color-error);
  font-size: 0.85rem;
  margin-top: -8px;
  display: block;
}

.btn-primary {
  background-color: rgb(20, 0, 109);
  color: white;
  padding: 12px;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition:
    background-color 0.3s ease,
    transform 0.1s ease;
  margin-top: 8px;
  width: 100%;
}

.btn-primary:hover {
  background-color: #1565c0;
}

.btn-primary:active {
  transform: scale(0.99);
}

.register-link {
  text-align: center;
  font-size: 0.9rem;
  color: var(--color-text-muted);
  margin-top: 8px;
}

.register-link a {
  color: var(--color-accent-blue);
  font-weight: 600;
  text-decoration: none;
}

.register-link a:hover {
  text-decoration: underline;
}
</style>
