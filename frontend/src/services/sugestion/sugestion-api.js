import { get, post, put, destroy } from 'boot/axios'

export const getSugestions = async (params = { page: '', rowsPerPage: 0 }) => {
  params.perPage = params.rowsPerPage || 0
  const { data } = await get('/sugestions', params)
  params.rowsNumber = data.total
  return data.data
}

export const createSugestion = async sugestion => {
  const { data } = await post('/sugestions', sugestion)
  return data
}
