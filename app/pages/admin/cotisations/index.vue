<script setup lang="ts">
const menuOuvert = ref(false)
const afficherFormulaire = ref(false)
const erreur = ref('')

const membres = ref([
  {
    id: 1,
    nom: 'Administrateur',
    frequence: 'mois',
    montantAttendu: 30000,
    montantPaye: 30000
  },
  {
    id: 2,
    nom: 'Jean',
    frequence: 'mois',
    montantAttendu: 30000,
    montantPaye: 15000
  },
  {
    id: 3,
    nom: 'Marie',
    frequence: 'mois',
    montantAttendu: 30000,
    montantPaye: 0
  }
])

const nouveauPaiement = ref({
  membreId: '',
  montant: ''
})

const membreSelectionne = computed(() => {
  return membres.value.find(
    membre => membre.id === Number(nouveauPaiement.value.membreId)
  )
})

const totalAttendu = computed(() => {
  return membres.value.reduce(
    (total, membre) => total + membre.montantAttendu,
    0
  )
})

const totalPaye = computed(() => {
  return membres.value.reduce(
    (total, membre) => total + membre.montantPaye,
    0
  )
})

const membresPayes = computed(() => {
  return membres.value.filter(
    membre => membre.montantPaye >= membre.montantAttendu
  ).length
})

const enregistrerPaiement = () => {
  erreur.value = ''

  if (!nouveauPaiement.value.membreId || !nouveauPaiement.value.montant) {
    erreur.value = 'Veuillez sélectionner un membre et saisir un montant.'
    return
  }

  const montant = Number(nouveauPaiement.value.montant)

  if (montant <= 0) {
    erreur.value = 'Le montant doit être supérieur à 0.'
    return
  }

  if (!membreSelectionne.value) {
    erreur.value = 'Membre introuvable.'
    return
  }

  membreSelectionne.value.montantPaye += montant

  nouveauPaiement.value = {
    membreId: '',
    montant: ''
  }

  afficherFormulaire.value = false
}

const ouvrirFormulaire = () => {
  erreur.value = ''
  nouveauPaiement.value = {
    membreId: '',
    montant: ''
  }
  afficherFormulaire.value = true
}

const fermerFormulaire = () => {
  afficherFormulaire.value = false
  erreur.value = ''
}

const formatMontant = (montant: number) => {
  return montant.toLocaleString('fr-FR')
}

const obtenirEtat = (membre: {
  montantAttendu: number
  montantPaye: number
}) => {
  if (membre.montantPaye >= membre.montantAttendu) {
    return 'Payé'
  }

  if (membre.montantPaye > 0) {
    return 'Partiel'
  }

  return 'Non payé'
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">

    <!-- Sidebar -->
    <Sidebar
      :mobile-open="menuOuvert"
      @close="menuOuvert = false"
    />

    <div class="min-h-screen lg:pl-64">

      <!-- Barre mobile -->
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

        <!-- En-tête -->
        <section class="mb-6">

          <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
              <p class="text-sm text-gray-500">
                Gestion
              </p>

              <h1 class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">
                Cotisations
              </h1>

              <p class="mt-2 text-sm text-gray-500">
                Suivez les paiements des membres pour le cycle actuel.
              </p>
            </div>

            <button
              type="button"
              class="rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800"
              @click="ouvrirFormulaire"
            >
              Enregistrer un paiement
            </button>

          </div>

        </section>

        <!-- Résumé -->
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Total attendu
            </p>

            <p class="mt-2 text-2xl font-bold text-gray-800">
              {{ formatMontant(totalAttendu) }} FCFA
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Total payé
            </p>

            <p class="mt-2 text-2xl font-bold text-green-700">
              {{ formatMontant(totalPaye) }} FCFA
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              Membres ayant payé
            </p>

            <p class="mt-2 text-2xl font-bold text-gray-800">
              {{ membresPayes }} / {{ membres.length }}
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">
              État
            </p>

            <p
              class="mt-2 text-lg font-bold"
              :class="
                membresPayes === membres.length
                  ? 'text-green-700'
                  : 'text-gray-700'
              "
            >
              {{
                membresPayes === membres.length
                  ? 'Complet'
                  : 'En attente'
              }}
            </p>
          </div>

        </section>

        <!-- Tableau -->
        <section class="mt-8 overflow-hidden rounded-xl border border-gray-200 bg-white">

          <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="font-semibold text-gray-800">
              Suivi des cotisations
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              État des paiements pour le cycle actuel.
            </p>
          </div>

          <!-- Ordinateur -->
          <div class="hidden overflow-x-auto md:block">

            <table class="w-full text-left text-sm">

              <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>

                  <th class="px-5 py-4">
                    Membre
                  </th>

                  <th class="px-5 py-4">
                    Fréquence
                  </th>

                  <th class="px-5 py-4">
                    Montant attendu
                  </th>

                  <th class="px-5 py-4">
                    Montant payé
                  </th>

                  <th class="px-5 py-4">
                    Restant
                  </th>

                  <th class="px-5 py-4">
                    État
                  </th>

                  <th class="px-5 py-4 text-right">
                    Action
                  </th>

                </tr>
              </thead>

              <tbody class="divide-y divide-gray-100">

                <tr
                  v-for="membre in membres"
                  :key="membre.id"
                  class="hover:bg-gray-50"
                >

                  <td class="px-5 py-4 font-medium text-gray-800">
                    {{ membre.nom }}
                  </td>

                  <td class="px-5 py-4 capitalize text-gray-600">
                    {{ membre.frequence }}
                  </td>

                  <td class="px-5 py-4 text-gray-700">
                    {{ formatMontant(membre.montantAttendu) }} FCFA
                  </td>

                  <td class="px-5 py-4 font-medium text-green-700">
                    {{ formatMontant(membre.montantPaye) }} FCFA
                  </td>

                  <td class="px-5 py-4 text-gray-700">
                    {{
                      formatMontant(
                        Math.max(
                          membre.montantAttendu - membre.montantPaye,
                          0
                        )
                      )
                    }}
                    FCFA
                  </td>

                  <td class="px-5 py-4">

                    <span
                      v-if="obtenirEtat(membre) === 'Payé'"
                      class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700"
                    >
                      Payé
                    </span>

                    <span
                      v-else-if="obtenirEtat(membre) === 'Partiel'"
                      class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-700"
                    >
                      Partiel
                    </span>

                    <span
                      v-else
                      class="rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-600"
                    >
                      Non payé
                    </span>

                  </td>

                  <td class="px-5 py-4 text-right">

                    <button
                      type="button"
                      class="text-sm font-medium text-green-700 hover:text-green-900"
                      @click="
                        nouveauPaiement.membreId = String(membre.id);
                        afficherFormulaire = true
                      "
                    >
                      Ajouter
                    </button>

                  </td>

                </tr>

              </tbody>

            </table>

          </div>

          <!-- Téléphone -->
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

                  <p class="mt-1 text-sm capitalize text-gray-500">
                    {{ membre.frequence }}
                  </p>
                </div>

                <span
                  v-if="obtenirEtat(membre) === 'Payé'"
                  class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700"
                >
                  Payé
                </span>

                <span
                  v-else-if="obtenirEtat(membre) === 'Partiel'"
                  class="rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-medium text-yellow-700"
                >
                  Partiel
                </span>

                <span
                  v-else
                  class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600"
                >
                  Non payé
                </span>

              </div>

              <div class="mt-4 grid grid-cols-2 gap-4">

                <div>
                  <p class="text-xs text-gray-400">
                    Attendu
                  </p>

                  <p class="mt-1 text-sm font-medium text-gray-700">
                    {{ formatMontant(membre.montantAttendu) }} FCFA
                  </p>
                </div>

                <div>
                  <p class="text-xs text-gray-400">
                    Payé
                  </p>

                  <p class="mt-1 text-sm font-medium text-green-700">
                    {{ formatMontant(membre.montantPaye) }} FCFA
                  </p>
                </div>

                <div>
                  <p class="text-xs text-gray-400">
                    Restant
                  </p>

                  <p class="mt-1 text-sm font-medium text-gray-700">
                    {{
                      formatMontant(
                        Math.max(
                          membre.montantAttendu - membre.montantPaye,
                          0
                        )
                      )
                    }}
                    FCFA
                  </p>
                </div>

              </div>

              <button
                type="button"
                class="mt-4 w-full rounded-lg border border-green-700 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-700 hover:text-white"
                @click="
                  nouveauPaiement.membreId = String(membre.id);
                  afficherFormulaire = true
                "
              >
                Ajouter un paiement
              </button>

            </div>

          </div>

        </section>

      </main>

    </div>

    <!-- Modal paiement -->
    <div
      v-if="afficherFormulaire"
      class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40 px-4 py-6"
    >

      <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">

        <div class="flex items-center justify-between">

          <div>
            <h2 class="text-xl font-bold text-gray-800">
              Enregistrer un paiement
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Ajoutez un versement pour un membre.
            </p>
          </div>

          <button
            type="button"
            class="text-2xl text-gray-400 hover:text-gray-700"
            @click="fermerFormulaire"
          >
            ×
          </button>

        </div>

        <!-- Erreur -->
        <div
          v-if="erreur"
          class="mt-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-600"
        >
          {{ erreur }}
        </div>

        <form
          class="mt-6 space-y-4"
          @submit.prevent="enregistrerPaiement"
        >

          <!-- Membre -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700">
              Membre
            </label>

            <select
              v-model="nouveauPaiement.membreId"
              class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-green-700 focus:ring-2 focus:ring-green-100"
            >

              <option value="">
                Sélectionner un membre
              </option>

              <option
                v-for="membre in membres"
                :key="membre.id"
                :value="membre.id"
              >
                {{ membre.nom }}
              </option>

            </select>
          </div>

          <!-- Montant -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700">
              Montant versé
            </label>

            <div class="relative">

              <input
                v-model="nouveauPaiement.montant"
                type="number"
                min="1"
                placeholder="Exemple : 10000"
                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 pr-16 text-sm outline-none focus:border-green-700 focus:ring-2 focus:ring-green-100"
              />

              <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                FCFA
              </span>

            </div>
          </div>

          <!-- Information -->
          <div
            v-if="membreSelectionne"
            class="rounded-lg bg-green-50 p-4"
          >

            <p class="text-sm text-green-800">
              Montant attendu :
              <strong>
                {{ formatMontant(membreSelectionne.montantAttendu) }} FCFA
              </strong>
            </p>

            <p class="mt-1 text-sm text-green-800">
              Déjà payé :
              <strong>
                {{ formatMontant(membreSelectionne.montantPaye) }} FCFA
              </strong>
            </p>

          </div>

          <!-- Boutons -->
          <div class="flex flex-col-reverse gap-3 pt-3 sm:flex-row sm:justify-end">

            <button
              type="button"
              class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
              @click="fermerFormulaire"
            >
              Annuler
            </button>

            <button
              type="submit"
              class="rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-800"
            >
              Enregistrer
            </button>

          </div>

        </form>

      </div>

    </div>

  </div>
</template>

