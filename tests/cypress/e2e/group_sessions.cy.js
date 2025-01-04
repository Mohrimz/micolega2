// Navodya Lakshani & Rimzan
// 2024/12/14
// Dashboard Front-end Test

// GROUP SESSIONS DASHBOARD

describe('Group Sessions test', () => {
  it('should create and join group sessions', () => {
    // Visit the login page
    cy.visit('http://127.0.0.1:8000/login');

    // Fill in the login form
    cy.get('input[name="email"]').type('naleem@students.apiit.lk');
    cy.get('input[name="password"]').type('rimzan123');

    // Submit the login form
    cy.get('button[type="submit"]').click();

    // Verify that the user is redirected to the dashboard
    cy.url().should('eq', 'http://127.0.0.1:8000/dashboard');

    // Navigate to the group sessions page
    cy.visit('http://127.0.0.1:8000/group-sessions');

    // Check for group course information
    cy.contains('Available Group Courses');
    cy.contains('Tutor:');
    cy.contains('Time:');

    // Enroll in a group course
    cy.contains('Enroll').click();

    // Ensure "Student Availability" field exists with required details
    cy.contains('Student Availability');
    cy.contains('Skill');
    cy.contains('Date');
    cy.contains('Time');

    // Remove a session
    //cy.contains('Remove Session').click();

    // Type a reason for removing the session
    //cy.get('textarea').type('Due to sickness');

    // Confirm the removal
    //cy.contains('Confirm').click();

    // Cancel button functionality check
    //cy.contains('Remove Session').click();

    // Type a reason for canceling the removal
    //cy.get('textarea').type('Due to sickness');

    // Cancel the action
    //cy.contains('Cancel').click();

    // Join a session
    cy.contains('Join Session').click();
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
