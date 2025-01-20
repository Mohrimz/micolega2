describe('creat group session test for Tutor', () => {
    it('should create and join group sessions', () => {
        // Visit the login page
        cy.visit('http://127.0.0.1:8000/login');

        // Fill in the login form
        cy.get('input[name="email"]').type('cb011474@students.apiit.lk');
        cy.get('input[name="password"]').type('rimzan123');

        // Submit the login form
        cy.get('button[type="submit"]').click();

        // Verify that the user is redirected to the dashboard
        cy.url().should('eq', 'http://127.0.0.1:8000/dashboard');

        // Navigate to the group sessions page
        cy.visit('http://127.0.0.1:8000/group-sessions');

        

        // Click on "Create Group Course"
        cy.contains('Create Group Course').click();
      //  cy.get('div.container select[name="skill_id"]').select('CTF Challenge Solver');
        // cy.get('select[name="level"]').select('L4');
        // cy.get('input[name="date"]').type('2025-01-10');
        // cy.get('input[name="time"]').should('have.value', '14:30');

       
        // Submit the form
       // cy.get('button[type="Submit"]').click();

        // Remove a session
        cy.contains('Remove Session').click();

        // Type a reason for removing the session
        cy.get('textarea').type('Due to sickness');

        // // Confirm the removal
        cy.contains('Confirm').click();

        // // Cancel button functionality check
        cy.contains('Remove Session').click();

        // // Type a reason for canceling the removal
        cy.get('textarea').type('Due to sickness');

        // // Cancel the action
        cy.contains('Cancel').click();

        // Join a session
        cy.contains('Join Session').click();
});
});