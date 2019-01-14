<template>
  <v-flex>
    <v-card flat>
      <v-card-title>
        <span class="headline">
          Transactions
        </span>
      </v-card-title>
      <v-card-text>
        <v-form>
          <v-layout row wrap>
            <v-flex xs12 sm6 md4>
              <v-autocomplete
                v-model="form.person"
                :items="people"
                label="Cliente"
                item-text="name"
                item-value="id"
                no-data-text="Nenhum item encontrado"
                clearable
              />
            </v-flex>
            <v-flex xs12 sm6 md4>
              <v-autocomplete
                v-model="form.company"
                :items="companies"
                label="Empresa"
                item-text="name"
                item-value="id"
                no-data-text="Nenhum item encontrado"
                clearable
              />
            </v-flex>
            <v-flex xs12 sm6 md4>
              <v-select
                v-model="form.paymentMethod"
                :items="paymentMethods"
                label="Forma de pagamento"
                clearable
              />
            </v-flex>
            <v-flex xs12>
              <v-btn slot="activator" color="secondary" @click="filter">
                <v-icon left>
                  filter_list
                </v-icon> Filtrar
              </v-btn>
            </v-flex>
          </v-layout>
        </v-form>
      </v-card-text>
    </v-card>
    <v-card flat class="mt-3 mb-3">
      <v-card-text>
        <v-layout row wrap>
          <v-flex xs12 sm6>
            <v-btn color="primary" to="/transactions/new">
              <v-icon left>
                insert_drive_file
              </v-icon> Novo
            </v-btn>
          </v-flex>
          <v-flex xs12 sm6>
            <v-text-field
              v-model="search"
              prepend-icon="search"
              label="Pesquisar"
              autofocus
              hide-details
              single-line
            />
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
    <v-data-table
      :headers="headers"
      :items="transactions"
      :search="search"
      :rows-per-page-items="rowsPerPage"
      no-results-text="Nenhum resultado encontrado"
      no-data-text="Nenhum resultado correspondente encontrado"
      rows-per-page-text="Items por página"
      disable-initial-sort
    >
      <template slot="items" slot-scope="props">
        <tr @click="props.expanded = !props.expanded">
          <td>
            <span :class="[props.item.operationType === 'I' ? 'success--text' : 'error--text']">
              <strong>{{ props.item.id }}</strong>
            </span>
          </td>
          <td>{{ props.item.person.name }}</td>
          <td>{{ props.item.company.name }}</td>
          <td>{{ props.item.category.name }}</td>
          <td>
            {{ props.item.paymentMethod ?
              paymentMethods.find(i => i.value === props.item.paymentMethod).text : 'Não informado'
            }}
          </td>
          <td>{{ formatMoney(props.item.value) }}</td>
          <td>{{ formatDate(props.item.paidAt) }}</td>
          <td class="text-xs-right">
            <v-btn :to="`/transactions/${props.item.id}`" color="cyan" fab dark small>
              <v-icon>edit</v-icon>
            </v-btn>
            <v-btn color="red" fab dark small @click="deleteTransaction(props.item.id)">
              <v-icon>delete</v-icon>
            </v-btn>
          </td>
        </tr>
      </template>
      <template slot="expand" slot-scope="props">
        <v-card flat>
          <v-card-text>
            <span class="body-2">
              Observações:
            </span> {{ props.item.note }}<br>
          </v-card-text>
        </v-card>
      </template>
      <template slot="footer">
        <td colspan="6" /><td class="text-xs-left">
          <strong>Valor Total</strong>
        </td>
        <td class="text-xs-right">
          {{ formatMoney(totalItemsValue) }}
        </td>
      </template>
      <template slot="pageText" slot-scope="{ pageStart, pageStop }">
        De {{ pageStart }} à {{ pageStop }}
      </template>
    </v-data-table>
  </v-flex>
</template>

<script>
import { formatDate, formatMoney } from '~/utils/helpers'

export default {
  head: {
    title: 'Transactions'
  },
  data() {
    return {
      search: '',
      transactions: [],
      people: [],
      companies: [],
      categories: [],
      tags: [],
      paymentMethods: [
        { text: 'Dinheiro', value: 'DN' },
        { text: 'Cheque', value: 'CH' },
        { text: 'Boleto Bancário', value: 'BB' },
        { text: 'Cartão de Crédito', value: 'CC' },
        { text: 'Cartão de Débito', value: 'CD' },
        { text: 'Transferência Bancária', value: 'TB' },
        { text: 'Débito Automático', value: 'DA' },
        { text: 'Nota Promissória', value: 'NP' }
      ],
      form: {
        tags: [],
        mediaTags: [],
        person: '',
        company: '',
        paymentMethod: '',
        startPaidAt: '',
        endPaidAt: ''
      },
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Cliente', value: 'person.name' },
        { text: 'Empresa', value: 'company.name' },
        { text: 'Categoria', value: 'category.name' },
        { text: 'Forma de pagamento', value: 'paymentMethod' },
        { text: 'Valor', value: 'value' },
        { text: 'Data de pagamento', value: 'paidAt' },
        { text: '', value: 'actions', sortable: false }
      ],
      rowsPerPage: [
        10, 25, 50, { text: 'Tudo', value: -1 }
      ]
    }
  },
  computed: {
    totalItemsValue() {
      return this.transactions.reduce((p, c) => p + (c.operationType === 'I' ? c.value : -c.value), 0)
    }
  },
  async asyncData({ app }) {
    const transactions = await app.$axios.get('transactions?include=person,company,category')

    return { transactions: transactions.data.data }
  },
  async mounted() {
    this.people = (await this.$axios.get('people')).data.data
    this.companies = (await this.$axios.get('companies')).data.data
    this.tags = (await this.$axios.get('tags')).data.data
  },
  methods: {
    deleteTransaction(id) {
      this.$axios.delete(`transactions/${id}`).then(() => {
        this.transactions = this.transactions.filter(item => item.id !== id)
      })
    },
    filter() {
      this.$axios.get(
        'transactions?include=person,company,category'
        + `&filter[person_id]=${this.form.person || ''}`
        + `&filter[company_id]=${this.form.company || ''}`
        + `&filter[payment_method]=${this.form.paymentMethod || ''}`
      ).then(({ data }) => {
        this.transactions = data.data
      })
    },
    formatDate(value) {
      return formatDate(value)
    },
    formatMoney(value) {
      return formatMoney(value)
    }
  }
}
</script>
