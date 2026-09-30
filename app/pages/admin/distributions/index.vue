<script setup lang="ts">
const menuOuvert = ref(false)

const membres = ref([
  {
    id: 1,
    nom: 'Administrateur',
    ordre: 1,
    montantAttendu: 30000,
    montantPaye: 30000
  },
  {
    id: 2,
    nom: 'Jean',
    ordre: 2,
    montantAttendu: 30000,
    montantPaye: 30000
  },
  {
    id: 3,
    nom: 'Marie',
    ordre: 3,
    montantAttendu: 30000,
    montantPaye: 15000
  }
])

const distributionEffectuee = ref(false)
const afficherConfirmation = ref(false)

const totalMembres = computed(() => membres.value.length)

const membresAyantPaye = computed(() => {
  return membres.value.filter(
    membre => membre.montantPaye >= membre.montantAttendu
  ).length
})

const totalCollecte = computed(() => {
  return membres.value.reduce(
    (total, membre) => total + membre.montantPaye,
    0
  )
})

const tousOntPaye = computed(() => {
  return membres.value.every(
    membre => membre.montantPaye >= membre.montantAttendu
  )
})

const prochainBeneficiaire = computed(() => {
  return [...membres.value]
    .sort((a, b) => a.ordre - b.ordre)
    .find(membre => !distributionEffectuee.value)
})

const effectuerDistribution = () => {
  if (!tousOntPaye.value) {
    return
  }

  afficherConfirmation.value = true
}

const confirmerDistribution = () => {
  distributionEffectuee.value = true
  afficherConfirmation.value = false
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

      <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        <!-- Titre -->
        <section class="mb-6">

          <p class="text-sm text-gray-500">
            Gestion
          </p>

          <h1 class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">
            Distributions
          </h1>

          <p class="mt-2 text-sm text-gray-500">
            Vérifiez les cotisations et effectuez la distribution du cycle actuel.
          </p>

        </section>

        <!-- Informations du cycle -->
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Cycle actuel
            </p>

            <p class="mt-2 text-xl font-bold text-gray-800">
              Septembre 2026
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Membres
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-800">
              {{ totalMembres }}
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Membres ayant payé
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-800">
              {{ membresAyantPaye }} / {{ totalMembres }}
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Total collecté
            </p>

            <p class="mt-2 text-2xl font-bold text-green-700">
              {{ totalCollecte.toLocaleString('fr-FR') }} FCFA
            </p>
          </div>

        </section>

        <!-- État de la distribution -->
        <section class="mt-8">

          <div class="rounded-xl border border-gray-200 bg-white p-6">

            <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

              <div>
                <p class="text-sm text-gray-500">
                  État de la distribution
                </p>

                <div class="mt-2 flex items-center gap-3">

                  <span
                    v-if="distributionEffectuee"
                    class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700"
                  >
                    Distribution effectuée
                  </span>

                  <span
                    v-else-if="tousOntPaye"
                    class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700"
                  >
                    Distribution disponible
                  </span>

                  <span
                    v-else
                    class="rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-600"
                  >
                    Distribution bloquée
                  </span>

                </div>

                <p
                  v-if="!tousOntPaye && !distributionEffectuee"
                  class="mt-3 text-sm text-gray-500"
                >
                  Tous les membres doivent avoir payé leur cotisation avant
                  d'effectuer la distribution.
                </p>

              </div>

              <button
                v-if="!distributionEffectuee"
                type="button"
                :disabled="!tousOntPaye"
                class="rounded-lg px-5 py-3 text-sm font-semibold transition"
                :class="
                  tousOntPaye
                    ? 'bg-green-700 text-white hover:bg-green-800'
                    : 'cursor-not-allowed bg-gray-200 text-gray-400'
                "
                @click="effectuerDistribution"
              >
                Effectuer la distribution
              </button>

            </div>

          </div>

        </section>

        <!-- Prochain bénéficiaire -->
        <section class="mt-8">

          <div class="mb-4">

            <h2 class="text-lg font-semibold text-gray-800">
              Prochain bénéficiaire
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Le bénéficiaire est déterminé automatiquement selon l'ordre de tour.
            </p>

          </div>

          <div
            v-if="!distributionEffectuee && prochainBeneficiaire"
            class="rounded-xl border border-green-200 bg-white p-6"
          >

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

              <div>

                <p class="text-sm text-gray-500">
                  Ordre de tour #{{ prochainBeneficiaire.ordre }}
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-800">
                  {{ prochainBeneficiaire.nom }}
                </h3>

              </div>

              <div class="sm:text-right">

                <p class="text-sm text-gray-500">
                  Montant à distribuer
                </p>

                <p class="mt-1 text-2xl font-bold text-green-700">
                  {{ totalCollecte.toLocaleString('fr-FR') }} FCFA
                </p>

              </div>

            </div>

          </div>

          <div
            v-else
            class="rounded-xl border border-gray-200 bg-white p-6"
          >

            <p class="text-sm text-gray-500">
              La distribution du cycle actuel a été effectuée.
            </p>

          </div>

        </section>

        <!-- Vérification des paiements -->
        <section class="mt-8">

          <div class="mb-4">

            <h2 class="text-lg font-semibold text-gray-800">
              Vérification des cotisations
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Vérifiez que chaque membre a atteint le montant attendu.
            </p>

          </div>

          <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <!-- Tableau desktop -->
            <div class="hidden overflow-x-auto md:block">

              <table class="w-full text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500">

                  <tr>
                    <th class="px-5 py-4">
                      Ordre
                    </th>

                    <th class="px-5 py-4">
                      Membre
                    </th>

                    <th class="px-5 py-4">
                      Montant attendu
                    </th>

                    <th class="px-5 py-4">
                      Montant payé
                    </th>

                    <th class="px-5 py-4">
                      État
                    </th>
                  </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                  <tr
                    v-for="membre in membres"
                    :key="membre.id"
                    class="hover:bg-gray-50"
                  >

                    <td class="px-5 py-4 font-semibold text-green-700">
                      {{ membre.ordre }}
                    </td>

                    <td class="px-5 py-4 font-medium text-gray-800">
                      {{ membre.nom }}
                    </td>

                    <td class="px-5 py-4 text-gray-600">
                      {{ membre.montantAttendu.toLocaleString('fr-FR') }}
                      FCFA
                    </td>

                    <td class="px-5 py-4 font-medium text-gray-800">
                      {{ membre.montantPaye.toLocaleString('fr-FR') }}
                      FCFA
                    </td>

                    <td class="px-5 py-4">

                      <span
                        v-if="membre.montantPaye >= membre.montantAttendu"
                        class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700"
                      >
                        Payé
                      </span>

                      <span
                        v-else
                        class="rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-600"
                      >
                        Non complet
                      </span>

                    </td>

                  </tr>

                </tbody>

              </table>

            </div>

            <!-- Cartes mobile -->
            <div class="divide-y divide-gray-100 md:hidden">

              <div
                v-for="membre in membres"
                :key="membre.id"
                class="p-5"
              >

                <div class="flex items-start justify-between gap-4">

                  <div>

                    <p class="font-semibold text-gray-800">
                      {{ membre.nom }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                      Ordre #{{ membre.ordre }}
                    </p>

                  </div>

                  <span
                    v-if="membre.montantPaye >= membre.montantAttendu"
                    class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700"
                  >
                    Payé
                  </span>

                  <span
                    v-else
                    class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600"
                  >
                    Non complet
                  </span>

                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">

                  <div>
                    <p class="text-xs text-gray-400">
                      Attendu
                    </p>

                    <p class="mt-1 text-sm font-medium text-gray-700">
                      {{ membre.montantAttendu.toLocaleString('fr-FR') }}
                      FCFA
                    </p>
                  </div>

                  <div>
                    <p class="text-xs text-gray-400">
                      Payé
                    </p>

                    <p class="mt-1 text-sm font-medium text-gray-700">
                      {{ membre.montantPaye.toLocaleString('fr-FR') }}
                      FCFA
                    </p>
                  </div>

                </div>

              </div>

            </div>

          </div>

        </section>

      </main>

    </div>

    <!-- Modal confirmation -->
    <div
      v-if="afficherConfirmation"
      class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40 px-4"
    >

      <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">

        <h2 class="text-xl font-bold text-gray-800">
          Confirmer la distribution
        </h2>

        <p class="mt-3 text-sm leading-6 text-gray-500">
          Vous êtes sur le point de distribuer
          <strong class="text-gray-800">
            {{ totalCollecte.toLocaleString('fr-FR') }} FCFA
          </strong>
          à
          <strong class="text-gray-800">
            {{ prochainBeneficiaire?.nom }}
          </strong>.
        </p>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

          <button
            type="button"
            class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
            @click="afficherConfirmation = false"
          >
            Annuler
          </button>

          <button
            type="button"
            class="rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-800"
            @click="confirmerDistribution"
          >
            Confirmer la distribution
          </button>

        </div>

      </div>

    </div>

  </div>
</template>