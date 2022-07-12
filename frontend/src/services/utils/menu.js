import { loggedUser } from '../../boot/user'

const generalItems = [
  {
    label: 'Dashboard',
    icon: 'o_dashboard',
    to: {
      name: 'dashboard'
    },
    permission: "allowed"
  },
  {
<<<<<<< HEAD
    label: 'Planos',
    icon: 'o_auto_awesome_motion',
    to: {
      name: 'plans'
    },
    permission: ['admin']
=======
    label: 'Inscrições',
    icon: 'o_bookmark_add',
    to: {
      name: 'subscriptions'
    },
    permission: ['admin', 'member']
>>>>>>> 9cd7a88565ce6ed0f8e684d9090a5ceb7820bd07
  },
  {
    label: 'Acesso ao Sistema',
    icon: 'o_manage_accounts',
    children: [
      {
        label: 'Usuários',
        icon: 'o_account_circle',
        to: {
          name: 'users'
        },
        permission: ['admin']
      },
      {
        label: 'Permissões',
        icon: 'fingerprint',
        to: {
          name: 'permissions'
        },
        permission: ['admin']
      }
    ]
  },
]

export const generateMenu = () => {
  const filteredMenu = []
  for (const menuItem of generalItems) {
    if (
      (menuItem.permission || []).includes(loggedUser.role) ||
      (menuItem.permission || []).includes('allowed')
    ) {
      filteredMenu.push(menuItem)
      continue
    }

    if (menuItem.children) {
      menuItem.children = menuItem.children.filter(children => (
        (children.permission || []).includes(loggedUser.role) ||
        (children.permission || []).includes('allowed')
      ))

      if (menuItem.children.length > 0) {
        filteredMenu.push(menuItem)
      }
    }
  }
  return filteredMenu
}
