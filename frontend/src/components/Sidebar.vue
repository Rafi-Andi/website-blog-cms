<template>
  <div class="sidebar-content">
    
    <div class="profile-header">
      <div class="avatar">{{ userRole === 'Admin' ? 'A' : 'U' }}</div>
      <p class="username">{{ userRole === 'Admin' ? 'Admin' : 'User Budi' }}</p>
    </div>

    <nav class="sidebar-nav">
      
      <router-link 
        :to="userRole === 'Admin' ? { name: 'AdminDashboard' } : { name: 'UserDashboard' }" 
        active-class="active"
        class="nav-item">
        Dashboard
      </router-link>

      <template v-if="userRole === 'Admin'">
        <router-link 
          :to="{ name: 'ManageCategories' }" 
          active-class="active"
          class="nav-item">
          Categories
        </router-link>
      </template>

      <template v-else>
        <router-link 
          :to="{ name: 'MyPosts' }" 
          active-class="active"
          class="nav-item">
          My Posts
        </router-link>
        <router-link 
          :to="{ path: '/homepage' }" 
          active-class="active"
          class="nav-item">
          Homepage 
        </router-link>
      </template>
    </nav>
    
    <div class="sidebar-footer">
      <button class="btn-logout" @click="handleLogout">Logout</button>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Hardcode role untuk simulasi. 
// Ganti nilainya menjadi 'User' untuk melihat menu User.
const userRole = ref('Admin'); 

onMounted(() => {
    console.log(`Sidebar loaded for role: ${userRole.value}`);
});


/**
 * Fungsi untuk menangani proses logout.
 */
const handleLogout = () => {
    alert('Logging out...');

    // TODO: Tambahkan logika penghapusan token
    
    router.push({ name: 'Login' });
};
</script>

<style scoped>
/* ===================================================================
   Variabel CSS (Minimalis & Bersih)
   =================================================================== */
:root {
  --color-dark-bg: #29323c;         /* Warna latar belakang sidebar (Dark Slate Grey) */
  --color-accent-blue: #007bff;     /* Warna aksen untuk item aktif (Blue Primary) */
  --color-text-light: #ecf0f1;      /* Warna teks terang */
  --color-hover-bg: #3c4852;        /* Warna latar belakang saat hover, sedikit lebih gelap */
  --color-separator: #4a5568;       /* Warna garis pemisah yang lebih halus */
  --color-logout-danger: #dc3545;   /* Warna merah untuk logout */
}

/* ===================================================================
   Container Utama Sidebar
   =================================================================== */
.sidebar-content {
  background-color: var(--color-dark-bg); 
  color: var(--color-text-light);
  display: flex;
  flex-direction: column;
  height: 100vh; /* Mengisi seluruh tinggi viewport */
  width: 240px; 
  padding: 20px 0; 
  box-shadow: none; /* Desain Datar */
}

/* ===================================================================
   Header Profil (Avatar dan Nama)
   =================================================================== */
.profile-header {
  text-align: center;
  padding: 0 20px 20px; 
  border-bottom: 1px solid var(--color-separator);
  margin-bottom: 15px;
}
.avatar {
  width: 55px; 
  height: 55px;
  border-radius: 50%;
  background-color: var(--color-accent-blue);
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.6em; 
  font-weight: bold;
  margin: 0 auto 10px;
  color: white; 
  border: 2px solid var(--color-text-light); /* Garis luar tipis */
  box-shadow: none; 
}
.username {
  font-size: 1.1em; 
  font-weight: 500;
  color: var(--color-text-light);
}

/* ===================================================================
   Navigasi (Link Menu)
   =================================================================== */
.sidebar-nav {
  flex-grow: 1; 
  padding: 0 15px; 
}
.nav-item {
  display: flex;
  align-items: center; 
  padding: 10px 15px; 
  margin: 3px 0;
  text-decoration: none;
  color: var(--color-text-light);
  border-radius: 4px; /* Sudut minimal */
  transition: background-color 0.15s, color 0.15s;
  font-size: 0.95em;
}

.nav-item:hover {
  background-color: var(--color-hover-bg);
  color: white; 
}

/* Menu yang sedang aktif */
.nav-item.active {
  background-color: var(--color-accent-blue);
  color: white;
  font-weight: 600;
  box-shadow: none; 
}

/* ===================================================================
   Footer (Logout)
   =================================================================== */
.sidebar-footer {
  padding: 15px 15px 0; 
  border-top: 1px solid var(--color-separator);
  text-align: center;
}
.btn-logout {
  background: none;
  border: 1px solid var(--color-logout-danger); 
  color: var(--color-logout-danger); 
  cursor: pointer;
  padding: 8px; 
  font-size: 0.9em;
  width: 100%;
  border-radius: 4px;
  transition: background-color 0.2s, color 0.2s;
  font-weight: 500;
}
.btn-logout:hover {
  background-color: var(--color-logout-danger); 
  color: white; 
}
</style>