export default defineNuxtRouteMiddleware(() => {
  const { estConnecte, estAdmin } = useAuth()

  if (!estConnecte.value) {
    return navigateTo('/connexion')
  }

  if (!estAdmin.value) {
    return navigateTo('/membre')
  }
})