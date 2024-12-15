// Navodya Lakshani
// 2024/12/14
// Dashboard Front end test

// SESSIONS PAGE
describe('Session Page Test', () => {
  it('should log into dashboard page', () => {
    
    cy.visit('http://52.205.231.103/login');

    
    cy.get('input[name="email"]').type('cb011483@students.apiit.lk'); 
    cy.get('input[name="password"]').type('nova1234');       

    
    cy.get('button[type="submit"]').click();


    
    cy.url().then(url => {
      expect(url).to.equal('http://52.205.231.103/sessions');
    });
    
    cy.contains('Accepted Sessions')

    
    
  });
});





    

    
    



