<script setup lang="ts">
const menuOuvert = ref(false)

const distributions = ref([
  {
    id: 1,
    mois: 'Août 2026',
    beneficiaire: 'Administrateur',
    ordre: 1,
    montant: 90000,
    date: '31/08/2026'
  },
  {
    id: 2,
    mois: 'Juillet 2026',
    beneficiaire: 'Jean',
    ordre: 2,
    montant: 90000,
    date: '31/07/2026'
  }
])

const totalDistributions = computed(() => {
  return distributions.value.length
})

const montantTotal = computed(() => {
  return distributions.value.reduce(
    (total, distribution) => total + distribution.montant,
    0
  )
})
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

      <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        <!-- Titre -->
        <section class="mb-6">

          <p class="text-sm text-gray-500">
            Consultation
          </p>

          <h1 class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">
            Historique
          </h1>

          <p class="mt-2 text-sm text-gray-500">
            Consultez l'historique des distributions effectuées.
          </p>

        </section>

        <!-- Statistiques -->
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">

          <div class="rounded-xl border border-gray-200 bg-white p-5">

            <p class="text-sm font-medium text-gray-500">
              Nombre de distributions
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-800">
              {{ totalDistributions }}
            </p>

            <p class="mt-1 text-xs text-gray-400">
              Distributions effectuées
            </p>

          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">

            <p class="text-sm font-medium text-gray-500">
              Montant total distribué
            </p>

            <p class="mt-2 text-3xl font-bold text-green-700">
              {{ montantTotal.toLocaleString('fr-FR') }} FCFA
            </p>

            <p class="mt-1 text-xs text-gray-400">
              Total des sommes remises
            </p>

          </div>

        </section>

        <!-- Historique -->
        <section class="mt-8">

          <div class="mb-4">

            <h2 class="text-lg font-semibold text-gray-800">
              Distributions effectuées
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Retrouvez les bénéficiaires et les montants distribués pour chaque cycle.
            </p>

          </div>

          <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <!-- Tableau desktop -->
            <div class="hidden overflow-x-auto md:block">

              <table class="w-full text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500">

                  <tr>
                    <th class="px-5 py-4">
                      Cycle
                    </th>

                    <th class="px-5 py-4">
                      Bénéficiaire
                    </th>

                    <th class="px-5 py-4">
                      Ordre
                    </th>

                    <th class="px-5 py-4">
                      Montant
                    </th>

                    <th class="px-5 py-4">
                      Date de distribution
                    </th>

                    <th class="px-5 py-4">
                      État
                    </th>
                  </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                  <tr
                    v-for="distribution in distributions"
                    :key="distribution.id"
                    class="hover:bg-gray-50"
                  >

                    <td class="px-5 py-4 font-medium text-gray-800">
                      {{ distribution.mois }}
                    </td>

                    <td class="px-5 py-4 text-gray-700">
                      {{ distribution.beneficiaire }}
                    </td>

                    <td class="px-5 py-4 font-semibold text-green-700">
                      #{{ distribution.ordre }}
                    </td>

                    <td class="px-5 py-4 font-semibold text-gray-800">
                      {{ distribution.montant.toLocaleString('fr-FR') }}
                      FCFA
                    </td>

                    <td class="px-5 py-4 text-gray-600">
                      {{ distribution.date }}
                    </td>

                    <td class="px-5 py-4">

                      <span
                        class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700"
                      >
                        Effectuée
                      </span>

                    </td>

                  </tr>

                </tbody>

              </table>

            </div>

            <!-- Cartes mobile -->
            <div class="divide-y divide-gray-100 md:hidden">

              <div
                v-for="distribution in distributions"
                :key="distribution.id"
                class="p-5"
              >

                <div class="flex items-start justify-between gap-4">

                  <div>

                    <p class="font-semibold text-gray-800">
                      {{ distribution.mois }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                      {{ distribution.beneficiaire }}
                    </p>

                  </div>

                  <span
                    class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700"
                  >
                    Effectuée
                  </span>

                </div>

                <div class="mt-4 grid grid-cols-2 gap-4">

                  <div>
                    <p class="text-xs text-gray-400">
                      Ordre
                    </p>

                    <p class="mt-1 text-sm font-medium text-green-700">
                      #{{ distribution.ordre }}
                    </p>
                  </div>

                  <div>
                    <p class="text-xs text-gray-400">
                      Montant
                    </p>

                    <p class="mt-1 text-sm font-medium text-gray-700">
                      {{ distribution.montant.toLocaleString('fr-FR') }}
                      FCFA
                    </p>
                  </div>

                  <div class="col-span-2">

                    <p class="text-xs text-gray-400">
                      Date
                    </p>

                    <p class="mt-1 text-sm font-medium text-gray-700">
                      {{ distribution.date }}
                    </p>

                  </div>

                </div>

              </div>

            </div>

            <!-- Aucun historique -->
            <div
              v-if="distributions.length === 0"
              class="px-5 py-12 text-center"
            >

              <p class="font-medium text-gray-700">
                Aucun historique disponible
              </p>

              <p class="mt-1 text-sm text-gray-500">
                Les distributions effectuées apparaîtront ici.
              </p>

            </div>

          </div>

        </section>

      </main>

    </div>

  </div>
</template>