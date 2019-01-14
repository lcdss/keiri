import moment from 'moment'

export const formatDate = (value) => {
  if (value === null) {
    return ''
  }

  return moment(value, 'YYYY-MM-DD').format('DD/MM/YYYY')
}

export const formatMoney = value => new Intl.NumberFormat('pt-BR', {
  style: 'currency',
  currency: 'BRL',
  minimumFractionDigits: 2
}).format(value)
