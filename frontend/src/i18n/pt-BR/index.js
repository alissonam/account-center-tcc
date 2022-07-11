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
  permissions: {
    users: 'Usuários',
    permissions:'Permissões',
  },
  plan: {
    hidden: {
      0: 'Não',
      1: 'Sim',
    },
    preferential: {
      0: 'Não',
      1: 'Sim',
    }
  }
}
