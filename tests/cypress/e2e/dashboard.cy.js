// Navodya Lakshani
// 2024/12/14
// Dashboard Front end test

// DASHBOARD
describe('Dashboard Page Test', () => {
  it('should log into dashboard page', () => {
    
    cy.visit('http://52.205.231.103/login');

    
    cy.get('input[name="email"]').type('cb011483@students.apiit.lk'); 
    cy.get('input[name="password"]').type('nova1234');       

    
    cy.get('button[type="submit"]').click();


    
    cy.url().should('eq', 'http://52.205.231.103/dashboard');

    
    
  });
});








// describe('Content Verification dashboard', () => {
//   before(() => {
      
//       cy.visit('http://52.205.231.103/dashboard');
//   });

//   it('be able to find and click content', () => {
//       cy.contains('Dashboard')
//       cy.visit('http://52.205.231.103/dashboard')
      

     
       
//       cy.contains('Recommended Tutors For You:').click();
//       cy.contains('Email: ')
//       cy.contains('Select Skill: ')
//       // cy.get('select[name="skill[]") 
//       //     .should('be.visible')
//       //     .select('CTF Challange Solver') 
//       //     .should('have.value', 'CTF Challange Solver');
      
      
//       cy.contains('Select Available Slot: ')
      

      
//       // cy.get('select[name="slot"]') 
//       //     .should('be.visible')
//       //     .select('10:00 AM - 11:00 AM') 
//       //     .should('have.value', '10:00 AM - 11:00 AM');

//       cy.contains('Request').click();

      
//       cy.contains('Sessions').click();

      
//       cy.contains('Teach').click();

//       cy.contains('Sessions').click();

  
//       cy.contains('Group Sessions').click();
//   });
// });