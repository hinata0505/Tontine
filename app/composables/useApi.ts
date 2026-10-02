export const useApi = () => {
  const config = useRuntimeConfig()

  const api = $fetch.create({
    baseURL: config.public.apiBase,
    headers: {
      Accept: 'application/json'
    }
  })

  return api
}
