<script setup lang="ts">
const nom = ref('')
const motDePasse = ref('')
const erreur = ref('')

const seConnecter = () => {
  erreur.value = ''

  if (!nom.value || !motDePasse.value) {
    erreur.value = 'Veuillez remplir tous les champs.'
    return
  }

  console.log('Nom :', nom.value)
}
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-green-700 px-4">

    <div class="w-full max-w-sm rounded-2xl bg-white p-7 shadow-2xl">

      <!-- Logo et titre -->
      <div class="mb-6 text-center">

        <div
          class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-green-700"
        >
          <span class="text-2xl font-bold text-white">
            T
          </span>
        </div>

        <h1 class="text-2xl font-bold text-green-700">
          Tontine
        </h1>

        <p class="mt-1 text-sm text-gray-500">
          Connectez-vous à votre espace
        </p>

      </div>

      <!-- Message d'erreur -->
      <div
        v-if="erreur"
        class="mb-4 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600"
      >
        {{ erreur }}
      </div>

      <!-- Formulaire -->
      <form
        class="space-y-4"
        @submit.prevent="seConnecter"
      >

        <!-- Nom -->
        <div>

          <label
            for="nom"
            class="mb-1.5 block text-sm font-medium text-gray-700"
          >
            Nom
          </label>

          <input
            id="nom"
            v-model="nom"
            type="text"
            placeholder="Entrez votre nom"
            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-green-700 focus:ring-2 focus:ring-green-100"
          />

        </div>

        <!-- Mot de passe -->
        <div>

          <label
            for="motDePasse"
            class="mb-1.5 block text-sm font-medium text-gray-700"
          >
            Mot de passe
          </label>

          <input
            id="motDePasse"
            v-model="motDePasse"
            type="password"
            placeholder="Entrez votre mot de passe"
            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-green-700 focus:ring-2 focus:ring-green-100"
          />

        </div>

        <!-- Bouton -->
        <button
          type="submit"
          class="w-full rounded-lg bg-green-700 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800"
        >
          Se connecter
        </button>

      </form>

      <p class="mt-5 text-center text-xs text-gray-400">
        Accès sécurisé à votre espace Tontine
      </p>

    </div>

  </div>
</template>

