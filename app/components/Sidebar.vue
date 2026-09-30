<script setup lang="ts">
defineProps<{
  mobileOpen?: boolean
}>()

const emit = defineEmits<{
  close: []
}>()

const seDeconnecter = async () => {
  emit('close')
  await navigateTo('/connexion')
}
</script>

<template>
  <!-- Fond sombre sur mobile -->
  <div
    v-if="mobileOpen"
    class="fixed inset-0 z-40 bg-black/40 lg:hidden"
    @click="emit('close')"
  ></div>

  <!-- Sidebar -->
  <aside
    class="fixed inset-y-0 left-0 z-50 h-screen w-64 border-r border-gray-200 bg-white transition-transform duration-300 lg:translate-x-0"
    :class="mobileOpen ? 'translate-x-0' : '-translate-x-full'"
  >

    <!-- Logo -->
    <div class="flex h-20 items-center border-b border-gray-100 px-6">
      <div
        class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-700"
      >
        <span class="text-lg font-bold text-white">
          T
        </span>
      </div>

      <span class="ml-3 text-xl font-bold text-green-700">
        Tontine
      </span>

      <button
        type="button"
        class="ml-auto text-lg text-gray-500 hover:text-green-700 lg:hidden"
        @click="emit('close')"
      >
        ×
      </button>
    </div>

    <!-- Menu -->
    <div class="p-4">
      <p
        class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400"
      >
        Menu
      </p>

      <NuxtLink
        to="/admin"
        class="mb-1 block rounded-lg px-3 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700"
        @click="emit('close')"
      >
        Tableau de bord
      </NuxtLink>

      <NuxtLink
        to="/admin/membres"
        class="mb-1 block rounded-lg px-3 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700"
        @click="emit('close')"
      >
        Membres
      </NuxtLink>

      <NuxtLink
        to="/admin/cotisations"
        class="mb-1 block rounded-lg px-3 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700"
        @click="emit('close')"
      >
        Cotisations
      </NuxtLink>

      <NuxtLink
        to="/admin/distributions"
        class="mb-1 block rounded-lg px-3 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700"
        @click="emit('close')"
      >
        Distributions
      </NuxtLink>

      <NuxtLink
        to="/admin/historique"
        class="mb-1 block rounded-lg px-3 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700"
        @click="emit('close')"
      >
        Historique
      </NuxtLink>
    </div>

    <!-- Déconnexion FIXÉE EN BAS -->
    <div
      class="fixed bottom-0 left-0 z-[60] w-64 border-t border-gray-200 bg-white p-4"
    >
      <button
        type="button"
        class="w-full rounded-lg px-3 py-3 text-left font-medium text-gray-700 hover:bg-red-50 hover:text-red-600"
        @click="seDeconnecter"
      >
        Déconnexion
      </button>
    </div>

  </aside>
</template>

