import { loggedUser } from '../../boot/user';

const generalItems = [
  {
    label: 'Dashboard',
    icon: 'o_dashboard',
    to: {
      name: 'dashboard'
    },
    permission: ['allowed']
  },
  {
    label: 'Produtos',
    icon: 'o_shopping_basket',
    to: {
      name: 'products'
    },
    permission: ['products', 'admin']
  },
  {
    label: 'Planos',
    icon: 'o_auto_awesome_motion',
    to: {
      name: 'plans'
    },
    permission: ['plans', 'admin']
  },
  {
    label: 'Planos',
    icon: 'o_auto_awesome_motion',
    to: {
      name: 'client_plans'
    },
    permission: ['member']
  },
  {
    label: 'Inscrições',
    icon: 'o_bookmark_add',
    to: {
      name: 'subscriptions'
    },
    permission: ['subscriptions', 'admin']
  },
  {
    label: 'Usuários',
    icon: 'o_account_circle',
    to: {
      name: 'users'
    },
    permission: ['users', 'admin']
  },
  {
    label: 'Permissões',
    icon: 'fingerprint',
    to: {
      name: 'permissions'
    },
    permission: ['permissions', 'admin']
  },
]

export const generateMenu = () => {
  const filteredMenu = []
  const abilities = loggedUser.permission.abilities

  for (const menuItem of generalItems) {
    if (
      (menuItem.permission || []).every(elem => abilities.includes(elem)) ||
      (menuItem.permission || []).includes('allowed')
    ) {
      filteredMenu.push(menuItem)
      continue
    }

    if (menuItem.children) {
      menuItem.children = menuItem.children.filter(children => (
        (children.permission || []).every(elem => abilities.includes(elem)) ||
        (children.permission || []).includes('allowed')
      ))

      if (menuItem.children.length > 0) {
        filteredMenu.push(menuItem)
      }
    }
  }
  return filteredMenu
}
