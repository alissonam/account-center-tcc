import { get, post, put, destroy } from 'boot/axios'

export const getSuggestions = async (params = { page: '', rowsPerPage: 0 }) => {
  params.perPage = params.rowsPerPage || 0
  const { data } = await get('/suggestions', params)
  params.rowsNumber = data.total
  return data.data
}

export const createSuggestion = async suggestion => {
  const { data } = await post('/suggestions', suggestion)
  return data
}
