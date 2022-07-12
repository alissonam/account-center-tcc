import { get, post, put, destroy } from 'boot/axios'

export const getSubscriptions = async (params = { page: '', rowsPerPage: 0 }) => {
  params.perPage = params.rowsPerPage || 0
  const { data } = await get('/subscriptions', params)
  params.rowsNumber = data.total
  return data.data
}

export const getSubscription = async (id, params) => {
  const { data } = await get(`/subscriptions/${id}`, params)
  return data
}

export const createSubscription = async user => {
  const { data } = await post('/subscriptions', user)
  return data
}

export const updateSubscription = async (id, user) => {
  const { data } = await put(`/subscriptions/${id}`, user)
  return data
}

export const destroySubscription = async id => {
  await destroy(`/subscriptions/${id}`)
}
