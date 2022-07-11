import { get, post, put, destroy } from 'boot/axios'

export const getPlans = async (params = { page: '', rowsPerPage: 0 }) => {
  params.perPage = params.rowsPerPage || 0
  const { data } = await get('/plans', params)
  params.rowsNumber = data.total
  return data.data
}

export const getPlan = async (id, params) => {
  const { data } = await get(`/plans/${id}`, params)
  return data
}

export const createPlan = async user => {
  const { data } = await post('/plans', user)
  return data
}

export const updatePlan = async (id, user) => {
  const { data } = await put(`/plans/${id}`, user)
  return data
}

export const destroyPlan = async id => {
  await destroy(`/plans/${id}`)
}
