<script setup lang="ts">

const menuOuvert = ref(false)

const afficherFormulaire = ref(false)

const membres = ref<any[]>([])

const nouveauMembre = ref({
  nom: '',
  telephone: '',
  ordre: '',
  frequence: 'mois',
  montant: ''
})

const erreur = ref('')
const chargement = ref(false)

// ========================================
// CHARGER LES MEMBRES DEPUIS LARAVEL
// ========================================
const chargerMembres = async () => {
  try {
    const response: any = await $fetch(
      'http://127.0.0.1:8000/api/membres',
      {
        method: 'GET',
        headers: {
          Accept: 'application/json'
        }
      }
    )

    if (response.success) {
      membres.value = response.data.map((membre: any) => ({
        id: membre.id_memb,
        nom: membre.nom_memb,
        telephone: membre.telephone,
        ordre: membre.ordre_tour,
        frequence: membre.frequence_cotisation,
        montant: Number(membre.montant_cotisation)
      }))
    }

  } catch (error) {
    console.error('Erreur chargement membres :', error)
    erreur.value = 'Impossible de charger les membres.'
  }
}


// ========================================
// AJOUTER UN MEMBRE
// ========================================
const ajouterMembre = async () => {

  erreur.value = ''

  if (
    !nouveauMembre.value.nom ||
    !nouveauMembre.value.telephone ||
    !nouveauMembre.value.ordre ||
    !nouveauMembre.value.montant
  ) {
    erreur.value = 'Veuillez remplir tous les champs.'
    return
  }

  chargement.value = true

  try {

    const response: any = await $fetch(
      'http://127.0.0.1:8000/api/membres',
      {
        method: 'POST',

        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },

        body: {
          nom_memb: nouveauMembre.value.nom,
          telephone: nouveauMembre.value.telephone,
          ordre_tour: Number(nouveauMembre.value.ordre),
          frequence_cotisation: nouveauMembre.value.frequence,
          montant_cotisation: Number(nouveauMembre.value.montant)
        }
      }
    )

    console.log('Membre enregistré :', response)

    if (response.success) {

      // Recharger la liste depuis la base
      await chargerMembres()

      // Réinitialiser le formulaire
      nouveauMembre.value = {
        nom: '',
        telephone: '',
        ordre: '',
        frequence: 'mois',
        montant: ''
      }

      afficherFormulaire.value = false
    }

  } catch (error: any) {
  console.error('Erreur ajout membre :', error)

  const responseData = error?.response?._data || error?.data

  if (responseData?.errors) {
    // Affiche la liste des erreurs de validation (ex: "L'ordre de tour a déjà été pris.")
    erreur.value = Object.values(responseData.errors).flat().join(' ')
  } else if (responseData?.message) {
    erreur.value = responseData.message
  } else {
    erreur.value = 'Impossible d’enregistrer le membre.'
  }
} finally {
    chargement.value = false
  }
}


// ========================================
// SUPPRIMER UN MEMBRE
// ========================================
const supprimerMembre = async (id: number) => {

  if (!confirm('Voulez-vous vraiment supprimer ce membre ?')) {
    return
  }

  try {

    await $fetch(
      `http://127.0.0.1:8000/api/membres/${id}`,
      {
        method: 'DELETE',

        headers: {
          Accept: 'application/json'
        }
      }
    )

    // Recharger depuis la base
    await chargerMembres()

  } catch (error: any) {

    console.error('Erreur suppression :', error)

    if (error?.data?.message) {
      erreur.value = error.data.message
    } else {
      erreur.value = 'Impossible de supprimer le membre.'
    }
  }
}


// ========================================
// FERMER LE FORMULAIRE
// ========================================
const fermerFormulaire = () => {
  afficherFormulaire.value = false
  erreur.value = ''
}


// ========================================
// CHARGEMENT INITIAL
// ========================================
onMounted(() => {
  chargerMembres()
})

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

      <!-- Contenu -->
      <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        <!-- En-tête -->
        <section class="mb-6">

          <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
              <p class="text-sm text-gray-500">
                Gestion
              </p>

              <h1 class="mt-1 text-2xl font-bold text-gray-800 sm:text-3xl">
                Membres
              </h1>

              <p class="mt-2 text-sm text-gray-500">
                Ajoutez et gérez les membres de la tontine.
              </p>
            </div>

            <button
              type="button"
              class="rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800"
              @click="afficherFormulaire = true"
            >
              Ajouter un membre
            </button>

          </div>

        </section>

        <!-- Nombre de membres -->
        <section class="mb-6">
          <div class="rounded-xl border border-gray-200 bg-white p-5">

            <p class="text-sm font-medium text-gray-500">
              Nombre de membres
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-800">
              {{ membres.length }}
            </p>

          </div>
        </section>

        <!-- Tableau -->
        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">

          <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="font-semibold text-gray-800">
              Liste des membres
            </h2>
          </div>

          <!-- Version ordinateur -->
          <div class="hidden overflow-x-auto md:block">

            <table class="w-full text-left text-sm">

              <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                  <th class="px-5 py-4">
                    Ordre
                  </th>

                  <th class="px-5 py-4">
                    Nom
                  </th>

                  <th class="px-5 py-4">
                    Téléphone
                  </th>

                  <th class="px-5 py-4">
                    Fréquence
                  </th>

                  <th class="px-5 py-4">
                    Montant
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

                  <td class="px-5 py-4 font-semibold text-green-700">
                    {{ membre.ordre }}
                  </td>

                  <td class="px-5 py-4 font-medium text-gray-800">
                    {{ membre.nom }}
                  </td>

                  <td class="px-5 py-4 text-gray-600">
                    {{ membre.telephone }}
                  </td>

                  <td class="px-5 py-4">
                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                      {{ membre.frequence }}
                    </span>
                  </td>

                  <td class="px-5 py-4 font-medium text-gray-800">
                    {{ membre.montant.toLocaleString('fr-FR') }} FCFA
                  </td>

                  <td class="px-5 py-4 text-right">
                    <button
                      type="button"
                      class="text-sm font-medium text-red-600 hover:text-red-800"
                      @click="supprimerMembre(membre.id)"
                    >
                      Supprimer
                    </button>
                  </td>

                </tr>

              </tbody>

            </table>

          </div>

          <!-- Version téléphone -->
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
                    {{ membre.telephone }}
                  </p>
                </div>

                <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                  #{{ membre.ordre }}
                </span>

              </div>

              <div class="mt-4 grid grid-cols-2 gap-3">

                <div>
                  <p class="text-xs text-gray-400">
                    Fréquence
                  </p>

                  <p class="mt-1 text-sm font-medium text-gray-700">
                    {{ membre.frequence }}
                  </p>
                </div>

                <div>
                  <p class="text-xs text-gray-400">
                    Montant
                  </p>

                  <p class="mt-1 text-sm font-medium text-gray-700">
                    {{ membre.montant.toLocaleString('fr-FR') }} FCFA
                  </p>
                </div>

              </div>

              <button
                type="button"
                class="mt-4 text-sm font-medium text-red-600"
                @click="supprimerMembre(membre.id)"
              >
                Supprimer
              </button>

            </div>

          </div>

        </section>

      </main>

    </div>

    <!-- Modal ajout membre -->
    <div
      v-if="afficherFormulaire"
      class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40 px-4 py-6"
    >

      <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">

        <div class="flex items-center justify-between">

          <div>
            <h2 class="text-xl font-bold text-gray-800">
              Ajouter un membre
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Renseignez les informations du membre.
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
          @submit.prevent="ajouterMembre"
        >

          <!-- Nom -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700">
              Nom
            </label>

            <input
              v-model="nouveauMembre.nom"
              type="text"
              placeholder="Nom du membre"
              class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-green-700 focus:ring-2 focus:ring-green-100"
            />
          </div>

          <!-- Téléphone -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700">
              Téléphone
            </label>

            <input
              v-model="nouveauMembre.telephone"
              type="tel"
              placeholder="Numéro de téléphone"
              class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-green-700 focus:ring-2 focus:ring-green-100"
            />
          </div>

          <!-- Ordre -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700">
              Ordre de tour
            </label>

            <input
              v-model="nouveauMembre.ordre"
              type="number"
              min="1"
              placeholder="Exemple : 2"
              class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-green-700 focus:ring-2 focus:ring-green-100"
            />

            <p class="mt-1 text-xs text-gray-400">
              Chaque membre doit avoir un ordre différent.
            </p>
          </div>

          <!-- Fréquence -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700">
              Fréquence de cotisation
            </label>

            <select
              v-model="nouveauMembre.frequence"
              class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-green-700 focus:ring-2 focus:ring-green-100"
            >
              <option value="jour">
                Jour
              </option>

              <option value="semaine">
                Semaine
              </option>

              <option value="mois">
                Mois
              </option>
            </select>

            <p class="mt-1 text-xs text-gray-400">
              La fréquence est définie par l'administrateur.
            </p>
          </div>

          <!-- Montant -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700">
              Montant de cotisation
            </label>

            <div class="relative">
              <input
                v-model="nouveauMembre.montant"
                type="number"
                min="0"
                placeholder="Exemple : 30000"
                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 pr-16 text-sm outline-none focus:border-green-700 focus:ring-2 focus:ring-green-100"
              />

              <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                FCFA
              </span>
            </div>

            <p class="mt-1 text-xs text-gray-400">
              Le montant est défini directement pour ce membre.
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

