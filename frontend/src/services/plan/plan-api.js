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

export const createPlan = async plan => {
  const { data } = await post('/plans', plan)
  return data
}

export const updatePlan = async (id, plan) => {
  const { data } = await put(`/plans/${id}`, plan)
  return data
}

export const destroyPlan = async id => {
  await destroy(`/plans/${id}`)
}
