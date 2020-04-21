/// <reference types="cypress" />

context('Accounts', () => {
  beforeEach(() => {
    cy.visit('/')
    cy.contains('YCode')
    cy.contains('Ybank Challenge Application')
  })

  it('Get Account and Transactions and Logout', () => {
    // Login
    cy.get('#access-form').click()
    cy.get('#id-input').type(1)
    cy.get('#login').click()
    // Check account data
    cy.contains('Welcome,')
    cy.contains('Account: 1')
    cy.contains('Balance: ')
    cy.get('#logout').click()
    cy.contains('YCode')
    cy.contains('Ybank Challenge Application')
  })

  it('Make a transaction', () => {
    // Login
    cy.get('#access-form').click()
    cy.get('#id-input').type(1)
    cy.get('#login').click()
    // Get balance
    cy.get('#balance').then(($balance) => {
      const oldBalance = parseFloat($balance.text().substring(1))
      expect(oldBalance).be.gt(500)
      // Make a transaction
      cy.get('.btn-success').click()
      cy.get('#input-1').type('2')
      cy.get('#input-2').type('500')
      cy.get('#input-3').type('Sample transaction of $500 to account 2')
      cy.get('#newPayment').submit(() => {
        const newBalance = parseFloat($balance.text().substring(1))
        expect(oldBalance).to.eq(newBalance + 500)
      })
    })
    // Check for the transaction
    cy.contains('Sample transaction of $500 to account 2')
    // Logout
    cy.get('#logout').click()
    cy.contains('YCode')
    cy.contains('Ybank Challenge Application')
  })

  it('Create Account, Login and Logout', () => {
    // SignUp
    cy.get('#create-form').click()
    cy.get('#name-input').type('Test')
    cy.get('#create').click()
    // Login
    cy.get('#login').click()
    // Check account data
    cy.contains('Welcome, Test')
    cy.contains('Balance: $0')
    // Logout
    cy.get('#logout').click()
    cy.contains('YCode')
    cy.contains('Ybank Challenge Application')
})
