// Navodya Lakshani
// 2024/12/14
// Front end test

// ADMIN DASHBOARD

describe('Adnmin Dashboard Page Test', () => {
  it('should log in 5o admin page', () => {
    
    cy.visit('http://52.205.231.103/login');

    
    cy.get('input[name="email"]').type('admin@apiit.lk'); 
    cy.get('input[name="password"]').type('Admin123');       

  
    cy.get('button[type="submit"]').click();

  
    cy.url().should('eq', 'http://52.205.231.103/dashboard');

  
    // cy.contains('button', 'Approve with Notes').click();

    // Optional: Fill the form fields (uncomment if required)
    // cy.get('.form-input.note-title').type('Approval Notes');
    // cy.get('textarea[name="noteDescription"]').type('random description.');

    
    // cy.contains('button', 'Approve').click();

    // checking for the detele button
  //   cy.get('#deleteButton'); // Assert it is visible


  //   cy.get('#deleteButton').click();

    
  //  cy.get('#deleteButton').then(($btn) => {
  
  // expect($btn.is(':visible')).to.be.false;
// });


// checking for the add new skill field
  cy.get('input[placeholder="Skill Name"]').type('Cyber Security Basics');
  cy.get('textarea[placeholder="Description"]').type('An introduction to cyber security concepts-nova.');
  cy.get('textarea[placeholder="Description"]').type('An introduction to cyber security concepts.');
  cy.contains('Category:').parent() 
      .contains('Computing-Cyber')    
      .click();  
  cy.contains('button', 'Add Skill').click(); 
  cy.contains('Skill added successfully').should('be.visible');
  });
});


