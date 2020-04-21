/// <reference types="cypress" />

context('Accounts', () => {
  beforeEach(() => {
    cy.visit('/')
    cy.contains('Enter your account ID:')
    cy.get('#input').clear().type('1').should('have.value', '1')
    cy.get('.btn').click()
  })

  it('Get Account and Transactions and Logout', () => {
    cy.contains('Welcome,')
    cy.contains('Account: 1')
    cy.contains('Balance: ')
    cy.get('#logout').click()
    cy.contains('Enter your account ID:')
  })

  it('Make a transaction', () => {
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
    cy.contains('Enter your account ID:')
  })
})
