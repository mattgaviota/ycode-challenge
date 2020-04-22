<template>
  <b-container>
    <div>
      <b-jumbotron header="YCode" lead="Ybank Challenge Application ">
        <b-button id="create-form" variant="success" @click="toggleForm(true)">Create Account</b-button>
        <b-button id="access-form" variant="primary" @click="toggleForm(false)">Access to Account</b-button>
      </b-jumbotron>
    </div>
    <b-form-row v-show="accessAccount">
      <b-form inline>
        <label class="sr-only" for="id-input">Enter your account ID</label>
        <b-form-input
          class="mb-2 mr-sm-2 mb-sm-0"
          id="id-input"
          type="number"
          v-model="accountID"
          required
          placeholder="Account ID"
        ></b-form-input>
        <b-button nuxt-link :to="'/accounts/' + accountID" id="login" variant="primary">Login</b-button>
      </b-form>
    </b-form-row>
    <b-form-row v-show="newAccount">
      <b-form inline>
        <label class="sr-only" for="name-input">Enter your account name</label>
        <b-form-input
          class="mb-2 mr-sm-2 mb-sm-0"
          id="name-input"
          type="text"
          v-model="accountName"
          required
          placeholder="Account name"
        ></b-form-input>
        <label class="sr-only" for="currencies">Select a currency</label>
        <b-form-radio
          class="mb-2 mr-sm-2 mb-sm-0"
          v-model="accountCurrency"
          name="currencies"
          value="USD"
        >US Dolar</b-form-radio>
        <b-form-radio
          class="mb-2 mr-sm-2 mb-sm-0"
          v-model="accountCurrency"
          name="currencies"
          value="EUR"
        >Euro</b-form-radio>
        <b-button id="create" variant="primary" @click="createAccount()">Create</b-button>
      </b-form>
    </b-form-row>
  </b-container>
</template>

<script lang="ts">
import Vue from 'vue'

interface Response {
  id: number
  message: string
}

export default Vue.extend({
  data() {
    return {
      accountID: null,
      newAccount: false,
      accessAccount: false,
      accountName: '',
      accountCurrency: 'USD'
    }
  },
  components: {},
  methods: {
    toggleForm(flag: boolean): void {
      this.newAccount = flag
      this.accessAccount = !flag
    },
    async createAccount(): Promise<Response> {
      try {
        const accountData = await this.$axios.$post<Response>(
          process.env.apiUrl + '/api/accounts',
          { name: this.accountName, currency: this.accountCurrency }
        )
        this.$bvToast.toast(accountData.message, {
          title: 'Success',
          variant: 'success',
          solid: true
        })
        this.clearData(accountData.id)
      } catch (error) {
        this.$bvToast.toast(error, {
          title: 'Error',
          variant: 'danger',
          solid: true
        })
      }
    },
    clearData(id: number): void {
      this.accountID = id
      this.newAccount = false
      this.accessAccount = true
      this.accountName = ''
      this.accountCurrency = 'USD'
    }
  }
})
</script>
