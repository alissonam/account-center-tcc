import { get, post, put, destroy } from 'boot/axios'

export const getPlans = async (params = { page: '', rowsPerPage: 0 }) => {
  params.perPage = params.rowsPerPage || 0
  const { data } = await get('/plans', params)
  params.rowsNumber = data.total
  return data.data
}

