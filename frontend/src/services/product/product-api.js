import { get, post, put, destroy } from 'boot/axios'

export const getProducts = async (params = { page: '', rowsPerPage: 0 }) => {
  params.perPage = params.rowsPerPage || 0
  const { data } = await get('/products', params)
  params.rowsNumber = data.total
  return data.data
}

export const getProduct = async (id, params) => {
  const { data } = await get(`/products/${id}`, params)
  return data
}

export const createProduct = async product => {
  const { data } = await post('/products', product)
  return data
}

export const updateProduct = async (id, product) => {
  const { data } = await put(`/products/${id}`, product)
  return data
}

export const destroyProduct = async id => {
  await destroy(`/products/${id}`)
}
