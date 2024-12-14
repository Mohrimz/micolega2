describe('User Registration', () => {
  beforeEach(() => {
      // Visit the registration page
      cy.visit('/register');
  });

  it('should successfully register a user with valid data', () => {
      // Fill in the name
      cy.get('input[name="name"]').type('John Doe');

      // Fill in the email with a valid student domain
      cy.get('input[name="email"]').type('john.doe@students.apiit.lk');

      // Fill in the password and confirm password
      cy.get('input[name="password"]').type('Password123');
      cy.get('input[name="password_confirmation"]').type('Password123');

      // Select a level
      cy.get('select[name="level"]').select('L4');

      // Check some skills
      cy.get('input[name="skills[]"]').check([1, 2]); // Use the IDs of skills

      // Check some availabilities
      cy.get('input[name="availabilities[]"]').check([1, 3]); // Use the IDs of availabilities

      // Submit the form
      cy.get('form').submit();

      // Assert the user is redirected to the dashboard
      cy.url().should('include', '/dashboard');
  });

  it('should show validation errors for missing or invalid data', () => {
      // Try submitting without filling in any fields
      cy.get('form').submit();

      // Check for validation error messages
      cy.contains('The name field is required.');
      cy.contains('The email field is required.');
      cy.contains('The password field is required.');
      cy.contains('The level field is required.');
      cy.contains('The availabilities field is required.');
      cy.contains('The skills field is required.');
  });

  it('should show an error for invalid email domain', () => {
      // Fill in the email with an invalid domain
      cy.get('input[name="email"]').type('invalid.email@gmail.com');

      // Submit the form
      cy.get('form').submit();

      // Check for the domain-specific error message
      cy.contains('The email must be an email address with the domain students.apiit.lk.');
  });
});
