<script setup lang="ts">
const menuOuvert = ref(false)

const utilisateur = {
  nom: 'Administrateur'
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">

    <Sidebar
      :mobile-open="menuOuvert"
      @close="menuOuvert = false"
    />

    <div class="min-h-screen lg:pl-64">

      <!-- Menu mobile -->
      <header class="sticky top-0 z-30 border-b border-gray-200 bg-white lg:hidden">
        <div class="flex h-16 items-center justify-between px-4">

          <button
            type="button"
            class="flex h-10 w-10 items-center justify-center rounded-lg text-gray-600 hover:bg-green-50 hover:text-green-700"
            @click="menuOuvert = true"
          >
            <span class="text-xl">☰</span>
          </button>

          <h1 class="text-lg font-bold text-green-700">
            Tontine
          </h1>

          <div class="h-10 w-10"></div>

        </div>
      </header>

      <!-- Contenu principal -->
      <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        <!-- Titre -->
        <section class="mb-6">
          <p class="text-sm text-gray-500">
            Tableau de bord
          </p>

          <h1 class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">
            Bienvenue, {{ utilisateur.nom }}
          </h1>

          <p class="mt-2 text-sm text-gray-500">
            Gérez les membres, les cotisations et les distributions de la tontine.
          </p>
        </section>

        <!-- Statistiques -->
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Membres
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-800">
              0
            </p>

            <p class="mt-1 text-xs text-gray-400">
              Membres enregistrés
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Cotisations
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-800">
              0 FCFA
            </p>

            <p class="mt-1 text-xs text-gray-400">
              Total collecté
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Distribution
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-800">
              0 FCFA
            </p>

            <p class="mt-1 text-xs text-gray-400">
              Montant du mois
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              État des paiements
            </p>

            <p class="mt-2 text-lg font-bold text-green-700">
              En attente
            </p>

            <p class="mt-1 text-xs text-gray-400">
              Vérification nécessaire
            </p>
          </div>

        </section>

        <!-- État de la tontine -->
        <section class="mt-8">

          <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-800">
              État de la tontine
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Suivez l'état du cycle actuel.
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

              <div>
                <p class="text-sm text-gray-500">
                  Membres ayant payé
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-800">
                  0 / 0
                </p>
              </div>

              <div>
                <p class="text-sm text-gray-500">
                  Prochaine distribution
                </p>

                <p class="mt-2 text-lg font-bold text-gray-800">
                  À définir
                </p>
              </div>

              <div>
                <p class="text-sm text-gray-500">
                  État de la distribution
                </p>

                <p class="mt-2 text-lg font-bold text-green-700">
                  Bloquée
                </p>
              </div>

            </div>

          </div>

        </section>

        <!-- Accès rapides -->
        <section class="mt-8">

          <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-800">
              Accès rapides
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Accédez aux principales fonctionnalités.
            </p>
          </div>

          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <NuxtLink
              to="/admin/membres"
              class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-green-700 hover:shadow-md"
            >
              <h3 class="font-semibold text-gray-800">
                Membres
              </h3>

              <p class="mt-2 text-sm text-gray-500">
                Ajouter et gérer les membres.
              </p>
            </NuxtLink>

            <NuxtLink
              to="/admin/cotisations"
              class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-green-700 hover:shadow-md"
            >
              <h3 class="font-semibold text-gray-800">
                Cotisations
              </h3>

              <p class="mt-2 text-sm text-gray-500">
                Enregistrer les paiements.
              </p>
            </NuxtLink>

            <NuxtLink
              to="/admin/distributions"
              class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-green-700 hover:shadow-md"
            >
              <h3 class="font-semibold text-gray-800">
                Distributions
              </h3>

              <p class="mt-2 text-sm text-gray-500">
                Gérer la distribution mensuelle.
              </p>
            </NuxtLink>

            <NuxtLink
              to="/admin/historique"
              class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-green-700 hover:shadow-md"
            >
              <h3 class="font-semibold text-gray-800">
                Historique
              </h3>

              <p class="mt-2 text-sm text-gray-500">
                Consulter les distributions passées.
              </p>
            </NuxtLink>

          </div>

        </section>

      </main>

    </div>

  </div>
</template>

