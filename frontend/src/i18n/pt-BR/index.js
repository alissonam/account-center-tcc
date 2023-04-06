// This is just an example,
// so you can safely delete all default props below

export default {
  user: {
    role: {
      admin: 'Administrador',
      member: 'Membro',
    },
    status: {
      pending_password: 'Pendente de senha',
      active: 'Ativo',
      blocked: 'Bloqueado',
      blocked_by_time: 'Bloqueado por tempo',
    }
  },
  product: {
    status: {
      active: 'Ativado',
      inative: 'Desativado',
    }
  },
  permissions: {
    users: 'Usuários',
    permissions:'Permissões',
    subscriptions: 'Inscrições',
    plans: 'Planos',
    products: 'Produtos',
  },
  plan: {
    visible: {
      false: 'Não',
      true: 'Sim',
    },
    preferential: {
      false: 'Não',
      true: 'Sim',
    },
    default: {
      false: 'Não',
      true: 'Sim',
    }
  }
}
