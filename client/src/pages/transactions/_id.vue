<template>
  <v-flex>
    <v-card flat>
      <v-card-title>
        <span class="headline">
          Edit transaction #{{ transaction.id }}
        </span>
      </v-card-title>
      <v-card-text>
        <v-form @submit.prevent="updateTransaction">
          <v-layout row wrap>
            <v-flex xs12 sm6>
              <v-text-field
                v-model="transaction.code"
                label="Numero documento"
                autofocus
              />
            </v-flex>
            <v-flex xs12 sm6>
              <v-select
                v-model="transaction.operationType"
                :items="operationTypes"
                label="Operação"
              />
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                ref="person"
                v-model="transaction.personId"
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
                v-model="transaction.companyId"
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
                v-model="transaction.tagId"
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
                v-model="transaction.paymentMethod"
                :items="paymentMethods"
                label="Forma de pagamento"
              />
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                ref="category"
                v-model="transaction.categoryId"
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
                v-model="transaction.subcategoryId"
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
                  v-model="transaction.issuedAt"
                  label="Data de Emissão"
                  readonly
                />
                <v-date-picker
                  v-model="transaction.issuedAt"
                  locale="pt-br"
                  actions
                  no-title
                  scrollable
                />
              </v-menu>
            </v-flex>
            <v-flex xs12 sm6>
              <v-menu
                :close-on-content-click="false"
                max-width="290px"
                lazy
                offset-y
                full-width
              >
                <v-text-field
                  slot="activator"
                  v-model="transaction.expiresAt"
                  label="Data de Vencimento"
                  readonly
                />
                <v-date-picker
                  v-model="transaction.expiresAt"
                  locale="pt-br"
                  actions
                  no-title
                  scrollable
                />
              </v-menu>
            </v-flex>
            <v-flex xs12 sm6>
              <v-menu
                :close-on-content-click="false"
                max-width="290px"
                lazy
                offset-y
                full-width
              >
                <v-text-field
                  slot="activator"
                  v-model="transaction.paidAt"
                  label="Data de Pagamento"
                  readonly
                />
                <v-date-picker
                  v-model="transaction.paidAt"
                  locale="pt-br"
                  actions
                  no-title
                  scrollable
                />
              </v-menu>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field v-model="transaction.value" label="Valor" />
            </v-flex>
            <v-flex xs12 sm6>
              <v-switch v-model="transaction.isPaid" label="Pago?" />
            </v-flex>
            <v-flex xs12 sm6>
              <v-textarea v-model="transaction.note" label="Obeservações" />
            </v-flex>
            <v-container fluid grid-list-sm>
              <v-layout row wrap>
                <v-flex v-for="file in transaction.files" :key="file.id" class="media" xs4 sm3 md2>
                  <a :href="file.url" @click.prevent="showFileDialog(file)">
                    <img
                      v-if="file.mimeType.endsWith('pdf')"
                      src="/pdf.png"
                      height="100%"
                      width="100%"
                    >
                    <img
                      v-else
                      :src="file.url"
                      height="100%"
                      width="100%"
                    >
                  </a>
                  <v-btn color="white" icon @click="removeFile(file)">
                    <v-icon>close</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
            <v-dialog v-model="fileDialog.show" max-width="500px" persistent>
              <v-card>
                <v-card-title>Propriedades</v-card-title>
                <v-card-text>
                  <v-autocomplete
                    ref="tag"
                    v-model="fileDialog.currentFile.tags"
                    :items="tags"
                    item-text="name"
                    item-value="id"
                    label="Tipo de Documento"
                    chips
                    multiple
                    persistent-hint
                  >
                    <template slot="no-data">
                      <a @click.prevent="newTag">
                        Clique aqui para cadastrar o item {{ getSelectSearchValue('tag') }}
                      </a>
                    </template>
                  </v-autocomplete>
                </v-card-text>
                <v-card-actions>
                  <v-btn flat @click="fileDialog.show = false">
                    Cancelar
                  </v-btn>
                  <v-btn color="primary" flat @click="saveFileProperties">
                    Salvar
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-flex xs12>
              <v-upload-field
                id="dropzone"
                ref="dropzone"
                :options="dropzoneOptions"
                @vdropzone-success="fileUploaded"
              />
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
    title: 'Edit Transaction'
  },
  async asyncData({ app, params }) {
    const transaction = (await app.$axios.get(`transactions/${params.id}?include=tags,media`)).data.data
    transaction.tagId = transaction.tags[0].id
    delete transaction.tags
    transaction.subcategoryId = null

    return { transaction }
  },
  data() {
    return {
      fileDialog: {
        show: false,
        currentFile: {
          tags: []
        }
      },
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
      dropzoneOptions: {
        url: `${this.$axios.defaults.baseURL}/transactions/${this.$route.params.id}/files`,
        thumbnailWidth: 180,
        maxFilesize: 10,
        autoProcessQueue: false,
        dictDefaultMessage: 'Solte arquivos aqui para fazer o upload',
        dictFallbackMessage: 'Seu navegador não suporta upload de arquivos.',
        dictFallbackText: 'Favor utilizar o formulário alternativo para realizar o upload de arquivos.',
        dictFileTooBig: 'O arquivo é muito grande ({{filesize}}MiB). Tamanho máximo permitido: {{maxFilesize}}MiB.',
        dictInvalidFileType: 'Você não pode fazer o upload de arquivos deste tipo.',
        dictResponseError: 'O servidor respondeu com o código {{statusCode}}.',
        dictCancelUpload: 'Cancelar o upload',
        dictUploadCanceled: 'Você tem certeza que deseja cancelar o upload?',
        dictCancelUploadConfirmation: 'O upload foi cancelado.',
        dictRemoveFile: 'Remover arquivo',
        dictRemoveFileConfirmation: 'Você tem certeza que deseja remover este arquivo?',
        dictMaxFilesExceeded: 'Você não pode fazer o upload de mais arquivos',
        dictFileSizeUnits: {
          tb: 'TB',
          gb: 'GB',
          mb: 'MB',
          kb: 'KB',
          b: 'b'
        }
      },
      people: [],
      companies: [],
      tags: [],
      categories: [],
      subcategories: []
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

      const category = this.categories.find(c => c.id === this.transaction.categoryId)

      if (category) {
        this.fetchSubcategories(category.id)
      } else {
        this.$axios.get(`categories/${this.transaction.categoryId}`).then(async ({ data }) => {
          const subcategoryId = this.transaction.categoryId
          this.transaction.categoryId = data.data.parent.id
          await this.fetchSubcategories(this.transaction.categoryId)
          this.transaction.subcategoryId = subcategoryId
        })
      }
    })
  },
  methods: {
    fileUploaded(file) {
      this.transaction.files.push(JSON.parse(file.xhr.response).data)
    },
    removeFile(file) {
      this.$axios.delete(`transactions/${this.$route.params.id}/files/${file.id}`).then(() => {
        const index = this.transaction.files.findIndex(item => item.id === file.id)
        this.transaction.files.splice(index, 1)
      })
    },
    saveFileProperties() {
      this.$axios.put(
        `transactions/${this.$route.params.id}/files/${this.fileDialog.currentFile.id}`,
        { tags: this.fileDialog.currentFile.tags }
      ).then(() => {
        this.fileDialog.show = false
      })
    },
    showFileDialog(file) {
      this.fileDialog.currentFile = file
      this.fileDialog.show = true
    },
    getSelectSearchValue(refname) {
      return this.$refs[refname] ? this.$refs[refname].lazySearch : ''
    },
    fetchSubcategories(id) {
      return this.$axios.get(`categories/${id}`).then(({ data }) => {
        this.subcategories = data.data.children
        this.transaction.subcategoryId = null
      })
    },
    newPerson() {
      const name = this.getSelectSearchValue('person')
      this.$axios.post('people', { name }).then((data) => {
        this.people.push(data.data.data)
        this.transaction.personId = data.data.data.id
      })
    },
    newCompany() {
      const name = this.getSelectSearchValue('company')
      this.$axios.post('companies', { name }).then((data) => {
        this.companies.push(data.data.data)
        this.transaction.companyId = data.data.data.id
      })
    },
    newTag() {
      const name = this.getSelectSearchValue('tag')
      this.$axios.post('tags', { name }).then((data) => {
        this.tags.push(data.data.data)
        this.transaction.tagId = data.data.data.id
      })
    },
    newCategory() {
      const name = this.getSelectSearchValue('category')
      this.$axios.post('categories', { name }).then((data) => {
        this.categories.push(data.data.data)
        this.transaction.categoryId = data.data.data.id
      })
    },
    newSubcategory() {
      const name = this.getSelectSearchValue('subcategory')
      this.$axios.post('categories', { name, parent_id: this.transaction.categoryId }).then((data) => {
        this.subcategories.push(data.data.data)
        this.transaction.subcategoryId = data.data.data.id
      })
    },
    updateTransaction() {
      this.$axios.put(`transactions/${this.$route.params.id}`, {
        code: this.transaction.code,
        payment_method: this.transaction.paymentMethod,
        issued_at: this.transaction.issuedAt,
        expires_at: this.transaction.expiresAt,
        paid_at: this.transaction.paidAt,
        value: this.transaction.operationType === 'I' ? this.transaction.value : -this.transaction.value,
        is_paid: this.transaction.isPaid,
        note: this.transaction.note,
        user_id: 1,
        person_id: this.transaction.personId,
        company_id: this.transaction.companyId,
        category_id: this.transaction.subcategoryId
          ? this.transaction.subcategoryId : this.transaction.categoryId,
        tags: [this.transaction.tagId]
      }).then(() => {
        this.$refs.dropzone.processQueue()
        alert('transaction successfully updated!')
      })
    }
  }
}
</script>

<style lang="stylus">
.media
  position: relative

.media a + .v-btn
  display: none
  position: absolute
  right: 5px
  top: 5px

.media .v-btn:hover, .media a:hover + .v-btn
  display: inline
</style>
