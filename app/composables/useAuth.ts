export const useAuth = () => {
  const utilisateur = useState<any | null>('utilisateur', () => null)

  const connecter = (user: any) => {
    utilisateur.value = user
  }

  const deconnecter = () => {
    utilisateur.value = null
    navigateTo('/connexion')
  }

  const estConnecte = computed(() => {
    return utilisateur.value !== null
  })

  const estAdmin = computed(() => {
    return utilisateur.value?.role === 'admin'
  })

  const estMembre = computed(() => {
    return utilisateur.value?.role === 'membre'
  })

  return {
    utilisateur,
    connecter,
    deconnecter,
    estConnecte,
    estAdmin,
    estMembre
  }
}