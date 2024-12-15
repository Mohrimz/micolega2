// Navodya Lakshani
// 2024/12/14
// Dashboard Front end test


// GROUP SESSIONS DASHBOARD


describe('Group Sessions test', () => {
  it('should create and join group sessions', () => {
    
    cy.visit('http://52.205.231.103/login');

    
    cy.get('input[name="email"]').type('cb011483@students.apiit.lk'); 
    cy.get('input[name="password"]').type('nova1234');       

    
    cy.get('button[type="submit"]').click();


    
    cy.url().should('eq', 'http://52.205.231.103/dashboard');
    cy.visit('http://52.205.231.103/group-sessions')
    // cy.contains('Create Group Course').click()

    cy.contains('Available Group Courses')
    cy.contains('Tutor:')
    cy.contains('Time:')
    cy.contains('Join Session').click()
   


    

    
    
  });
});

// attaemp to check drop down lists

// describe('Dropdown Skills Search Test', () => {
//   beforeEach(() => {
    
//     cy.visit('http://127.0.0.1/group_sessions');
//   });

//   it('should display available skills when "search" is clicked', () => {
  
    
    
//     cy.get('Skill').click();

    
//     cy.get('Skill')
//       .find('CTF Challange Solver') 
      
//       .then((options) => {
      
//         const skillToSelect = options[1].value; 
//         cy.get('Skills').select(skillToSelect);
//       });

  
//     cy.contains('Search').click();

    
  
//     cy.get('#available-skills')
//       .children()
//       .should('have.length.greaterThan', 0) 
//       .each(($skill) => {
        
//       });
//   });
// });
