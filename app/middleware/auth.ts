export default defineNuxtRouteMiddleware(() => {
  const { estConnecte } = useAuth()

  if (!estConnecte.value) {
    return navigateTo('/connexion')
  }
})