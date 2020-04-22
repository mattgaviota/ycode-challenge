<template>
  <div>
    <div class="container" v-if="loading">loading...</div>

    <div class="container" v-if="!loading">
      <b-card :header="'Welcome, ' + account.name" class="mt-3">
        <b-card-text>
          <div>
            Account:
            <code id="account">{{ account.id }}</code>
          </div>
          <div>
            Balance:
            <code
              id="balance"
            >{{ account.currency === 'USD' ? '$' : '€'}}{{ account.balance }}</code>
          </div>
        </b-card-text>
        <b-button size="sm" variant="success" @click="show = !show">New payment</b-button>
        <b-button id="logout" class="float-right" variant="danger" size="sm" nuxt-link to="/">Logout</b-button>
      </b-card>

      <b-card class="mt-3" header="New Payment" v-show="show">
        <b-form id="newPayment" @submit="onSubmit">
          <b-form-group id="input-group-1" label="To:" label-for="input-1">
            <b-form-input
              id="input-1"
              size="sm"
              v-model="payment.to"
              type="number"
              required
              placeholder="Destination ID"
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-2" label="Amount:" label-for="input-2">
            <b-input-group prepend="$" size="sm">
              <b-form-input
                id="input-2"
                v-model="payment.amount"
                type="number"
                required
                placeholder="Amount"
              ></b-form-input>
            </b-input-group>
          </b-form-group>

          <b-form-group id="input-group-3" label="Details:" label-for="input-3">
            <b-form-input
              id="input-3"
              size="sm"
              v-model="payment.details"
              required
              placeholder="Payment details"
            ></b-form-input>
          </b-form-group>

          <b-button type="submit" size="sm" variant="primary">Submit</b-button>
        </b-form>
      </b-card>

      <b-card class="mt-3" header="Payment History">
        <b-table striped hover :items="computedTransactions"></b-table>
      </b-card>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'

interface AccountResponse {
  id: number
  name: string
  balance: string
}

interface Transaction {
  id: number
  from: number
  to: number
  details: string
  amount: string
}

interface TransactionResponse {
  response: Array<Transaction>
}

interface Response {
  message: string
}

export default Vue.extend({
  async asyncData({ $axios, app, env, params, redirect }) {
    let accountData: AccountResponse = {
      id: 0,
      name: '',
      balance: ''
    }
    let transactionsData: TransactionResponse = { response: [] }
    try {
      accountData = await $axios.$get<AccountResponse>(
        env.apiUrl + '/api/accounts/' + params.id
      )
      transactionsData = await $axios.$get<TransactionResponse>(
        env.apiUrl + '/api/accounts/' + params.id + '/transactions'
      )
    } catch (error) {
      app.$toast.error('Account Not Found', {
        duration: 4500,
        position: 'bottom-center'
      })
      redirect('/')
    }
    return {
      account: accountData,
      transactions: transactionsData,
      loading: false
    }
  },
  data() {
    return {
      show: false,
      payment: {},
      account: null,
      transactions: null,

      loading: true
    }
  },

  methods: {
    async onSubmit(evt: Event): Promise<any> {
      evt.preventDefault()
      try {
        const transaction: Response | null = await this.$axios.$post<Response>(
          process.env.apiUrl +
            '/api/accounts/' +
            this.account.id +
            '/transactions',
          this.payment
        )
        const accountData: AccountResponse | null = await this.$axios.$get<
          AccountResponse
        >(process.env.apiUrl + '/api/accounts/' + this.account.id)
        const transactionsData: TransactionResponse | null = await this.$axios.$get<
          TransactionResponse
        >(
          process.env.apiUrl +
            '/api/accounts/' +
            this.account.id +
            '/transactions'
        )
        this.account = accountData
        this.transactions = transactionsData
        this.payment = {}
        this.show = false
        this.$bvToast.toast(transaction.message, {
          title: 'Success',
          variant: 'success',
          solid: true
        })
      } catch (error) {
        let message: string | Array<string>
        for (message in error.response.data) {
          this.$bvToast.toast(error.response.data[message], {
            title: 'Error',
            variant: 'danger',
            solid: true
          })
        }
      }
    }
  },

  computed: {
    computedTransactions(): Array<Transaction> {
      let processedTransactions = []
      for (const transaction of this.transactions) {
        let amount =
          (this.account.currency === 'USD' ? '$' : '€') + transaction.amount
        if (this.account.id != transaction.to) {
          amount = '-' + amount
        }
        processedTransactions.push({
          id: transaction.id,
          from: transaction.from,
          to: transaction.to,
          details: transaction.details,
          amount: amount
        })
      }
      return processedTransactions
    }
  }
})
</script>
