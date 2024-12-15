// Navodya Lakshani
// 2024/12/14
// Dashboard Front end test


//  TEACH PAGE
describe('tutor Dashboard Page Test', () => {
  it('should log in to tutor dashoard', () => {
    
    cy.visit('http://52.205.231.103/login');

    
    cy.get('input[name="email"]').type('cb011483@students.apiit.lk'); 
    cy.get('input[name="password"]').type('nova1234');       

    
    cy.get('button[type="submit"]').click();


    
    cy.url().should('eq', 'http://52.205.231.103/dashboard');
    cy.visit('http://52.205.231.103/teach')

    cy.contains('Skills and Demand')
    cy.contains('Available Skills')
    cy.contains('Skill Name')
    cy.contains('Skill Description')
    cy.contains('Demand')
    cy.contains('Action')
    cy.contains('Approved Skills')
    cy.contains('Rejected Skills')
    cy.contains('Rejected Session Requests')
    cy.contains('Teach Skill').click()

    
    
  });
});





// an Attempt to check file types 
// Cypress.Commands.add('uploadFile', (selector, filePath) => {
  
//     cy.contains('Teach Skill').click();


//     cy.get(selector);

  
//     cy.get(selector).attachFile(filePath);

  
//     const validExtensions = ['pdf', 'png', 'jpeg', 'jpg'];
//     const fileExtension = filePath.split('.').pop().toLowerCase();
//     if (!validExtensions.includes(fileExtension)) {
//       throw new Error('Invalid file type. Only PDF, PNG, JPEG, and JPG are allowed.');
//     }

  
//     cy.get(selector).parent().contains('Submit').click();
//   });
