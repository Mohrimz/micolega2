describe('Welcome Page', () => {
  beforeEach(() => {
    // Visit the welcome page
    cy.visit('/');
  });

  it('should display the logo', () => {
    cy.get('img[alt="Logo"]')  // Check for the logo image with alt text 'Logo'
      .should('be.visible');    // Ensure it is visible
  });

  it('should display the "Register" button', () => {
    cy.get('a[href="' + Cypress.config().baseUrl + '/register"]')  // Check for the register button
      .should('contain.text', 'Register')  // Check the button contains the text "Register"
      .should('be.visible');  // Ensure the button is visible
  });

  it('should display the "Log in" button', () => {
    cy.get('a[href="' + Cypress.config().baseUrl + '/login"]')  // Check for the login button
      .should('contain.text', 'Log in')  // Check the button contains the text "Log in"
      .should('be.visible');  // Ensure the button is visible
  });

  it('should display the hero section', () => {
    cy.get('section.text-center.mb-10')  // Check for the hero section
      .should('contain.text', 'Empower Your Learning at APIIT')  // Check for the main title
      .and('contain.text', 'Join a community of motivated students');  // Check for the description
  });

  it('should display the benefits section with four items', () => {
    cy.get('section.grid.grid-cols-1.md\\:grid-cols-2.gap-8.mb-12')  // Check the benefits section
      .children()  // Get the child elements (the benefit items)
      .should('have.length', 4);  // Ensure there are exactly four benefit items
  });

  it('should have a working "Join Now" button', () => {
    cy.get('a[href="' + Cypress.config().baseUrl + '/register"]')  // Check for the "Join Now" button
      .contains('Join Now')  // Ensure it contains the text "Join Now"
      .click()  // Simulate a click on the "Join Now" button
      .url()  // Get the current URL after the click
      .should('include', '/register');  // Ensure the URL includes /register (the registration page)
  });

  it('should have the correct footer text', () => {
    cy.get('footer')  // Check the footer
      .should('contain.text', '© ' + new Date().getFullYear() + ' MiColega. All rights reserved.');  // Check for the copyright text
  });
});
