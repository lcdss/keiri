<template>
  <v-flex>
    <v-card flat>
      <v-card-title>
        <span class="headline">
          New transaction
        </span>
      </v-card-title>
      <v-card-text>
        <v-form @submit.prevent="createTransaction">
          <v-layout row wrap>
            <v-flex xs12 sm6>
              <v-text-field v-model="form.code" label="Numero documento" autofocus />
            </v-flex>
            <v-flex xs12 sm6>
              <v-select v-model="form.operationType" :items="operationTypes" label="Operação" />
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                ref="person"
                v-model="form.person"
                :items="people"
                item-text="name"
                item-value="id"
                label="Cliente"
              >
                <template slot="no-data">
                  <a @click.prevent="newPerson">
                    Clique aqui para cadastrar o item {{ getSelectSearchValue('person') }}
                  </a>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                ref="company"
                v-model="form.company"
                :items="companies"
                item-text="name"
                item-value="id"
                label="Empresa"
              >
                <template slot="no-data">
                  <a @click.prevent="newCompany">
                    Clique aqui para cadastrar o item {{ getSelectSearchValue('company') }}
                  </a>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                ref="tag"
                v-model="form.tag"
                :items="tags"
                item-text="name"
                item-value="id"
                label="Referência"
              >
                <template slot="no-data">
                  <a @click.prevent="newTag">
                    Clique aqui para cadastrar o item {{ getSelectSearchValue('tag') }}
                  </a>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6>
              <v-select
                v-model="form.paymentMethod"
                :items="paymentMethods"
                label="Forma de pagamento"
              />
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                ref="category"
                v-model="form.category"
                :items="categories"
                item-text="name"
                item-value="id"
                label="Categoria"
                no-data-text="Nenhum item encontrado"
                @change="fetchSubcategories"
              >
                <template slot="no-data">
                  <a @click.prevent="newCategory">
                    Clique aqui para cadastrar o item {{ getSelectSearchValue('category') }}
                  </a>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                ref="subcategory"
                v-model="form.subcategory"
                :items="subcategories"
                item-text="name"
                item-value="id"
                label="Subcategoria"
              >
                <template slot="no-data">
                  <a @click.prevent="newSubcategory">
                    Clique aqui para cadastrar o item {{ getSelectSearchValue('subcategory') }}
                  </a>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6>
              <v-menu
                :close-on-content-click="false"
                max-width="290px"
                full-width
                lazy
                offset-y
              >
                <v-text-field
                  slot="activator"
                  v-model="form.issuedAt"
                  label="Data de Emissão"
                  readonly
                />
                <v-date-picker v-model="form.issuedAt" locale="pt-br" no-title scrollable />
              </v-menu>
            </v-flex>
            <v-flex xs12 sm6>
              <v-menu
                :close-on-content-click="false"
                max-width="290px"
                full-width
                lazy
                offset-y
              >
                <v-text-field
                  slot="activator"
                  v-model="form.expiresAt"
                  label="Data de Vencimento"
                  readonly
                />
                <v-date-picker v-model="form.expiresAt" locale="pt-br" no-title scrollable />
              </v-menu>
            </v-flex>
            <v-flex xs12 sm6>
              <v-menu
                :close-on-content-click="false"
                max-width="290px"
                full-width
                lazy
                offset-y
              >
                <v-text-field
                  slot="activator"
                  v-model="form.paidAt"
                  label="Data de Pagamento"
                  readonly
                />
                <v-date-picker v-model="form.paidAt" locale="pt-br" no-title scrollable />
              </v-menu>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field v-model="form.value" label="Valor" />
            </v-flex>
            <v-flex xs12 sm6>
              <v-switch v-model="form.isPaid" label="Pago?" />
            </v-flex>
            <v-flex xs12 sm6>
              <v-textarea v-model="form.note" label="Observações" />
            </v-flex>
            <v-flex sm12>
              <v-btn color="success" type="submit">
                <v-icon left>
                  save
                </v-icon> Salvar
              </v-btn>
            </v-flex>
          </v-layout>
        </v-form>
      </v-card-text>
    </v-card>
  </v-flex>
</template>

<script>
export default {
  head: {
    title: 'New Transaction'
  },
  data() {
    return {
      operationTypes: [
        { text: 'Entrada', value: 'I' },
        { text: 'Saída', value: 'O' }
      ],
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
      people: [],
      companies: [],
      tags: [],
      categories: [],
      subcategories: [],
      form: {
        code: '',
        operationType: null,
        person: null,
        company: null,
        tag: null,
        category: null,
        subcategory: null,
        paymentMethod: null,
        issuedAt: '',
        expiresAt: '',
        paidAt: null,
        value: '',
        isPaid: false,
        note: ''
      }
    }
  },
  created() {
    this.$axios.get('people').then(({ data }) => {
      this.people = data.data
    })
    this.$axios.get('companies').then(({ data }) => {
      this.companies = data.data
    })
    this.$axios.get('tags').then(({ data }) => {
      this.tags = data.data
    })
    this.$axios.get('categories').then(({ data }) => {
      this.categories = data.data
    })
  },
  methods: {
    getSelectSearchValue(refname) {
      return this.$refs[refname] ? this.$refs[refname].lazySearch : ''
    },
    fetchSubcategories(id) {
      this.$axios.get(`categories/${id}`).then(({ data }) => {
        this.subcategories = data.data.children
        this.form.subcategory = null
      })
    },
    newPerson() {
      const name = this.getSelectSearchValue('person')
      this.$axios.post('people', { name }).then((data) => {
        this.people.push(data.data.data)
        this.form.person = data.data.data
      })
    },
    newCompany() {
      const name = this.getSelectSearchValue('company')
      this.$axios.post('companies', { name }).then((data) => {
        this.companies.push(data.data.data)
        this.form.company = data.data.data
      })
    },
    newTag() {
      const name = this.getSelectSearchValue('tag')
      this.$axios.post('tags', { name }).then((data) => {
        this.tags.push(data.data.data)
        this.form.tag = data.data.data
      })
    },
    newCategory() {
      const name = this.getSelectSearchValue('category')
      this.$axios.post('categories', { name }).then((data) => {
        this.categories.push(data.data.data)
        this.form.category = data.data.data
      })
    },
    newSubcategory() {
      const name = this.getSelectSearchValue('subcategory')
      this.$axios.post('categories', { name, parent_id: this.form.category }).then((data) => {
        this.subcategories.push(data.data.data)
        this.form.subcategory = data.data.data
      })
    },
    createTransaction() {
      this.$axios.post('transactions', {
        code: this.form.code,
        payment_method: this.form.paymentMethod,
        issued_at: this.form.issuedAt,
        expires_at: this.form.expiresAt,
        paid_at: this.form.paidAt,
        value: this.form.operationType === 'I' ? this.form.value : -this.form.value,
        is_paid: this.form.isPaid,
        note: this.form.note,
        person_id: this.form.person,
        company_id: this.form.company,
        category_id: this.form.subcategory ? this.form.subcategory : this.form.category,
        user_id: 1,
        tags: [this.form.tag]
      }).then(({ data }) => {
        alert('transaction successfully created!')
        this.$router.push({ name: 'transactions-id', params: { id: data.data.id } })
      })
    }
  }
}
</script>
