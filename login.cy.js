//Navodya Lakshani
//Front End Testing 
//12/12/2024

//DASHBOARD
//Dashboard view
describe('Dashboard', () => {
  it('passes', () => {
    cy.visit('http://127.0.0.1:8000')
  })
})

// Finding the Login button
describe('visits login page', () => {
  it('finds the content "Log in"', () => {
    cy.visit('http://127.0.0.1:8000')
    cy.contains('Log in')
  })
})

//Clicking the Login Button
describe('clicks "Log in"', () => {
  it('clicks "Log in"', () => {
    cy.visit('http://127.0.0.1:8000')
    cy.contains('Log in').click
  })
})

//Finding the Register Button
describe('visits "Register"', () => {
  it('finds the content "Register"', () => {
    cy.visit('http://127.0.0.1:8000')
    cy.contains('Register')
  })
})

//Clicking the register Button
describe('clicks "Register"', () => {
  it('clicks "Register"', () => {
    cy.visit('http://127.0.0.1:8000')
    cy.contains('Register').click
  })
})

//Finding the "Register Now" Button
describe('visits "Register Now"', () => {
  it('finds the content "Register Now"', () => {
    cy.visit('http://127.0.0.1:8000')
    cy.contains('Register Now')
  })
})

//clicking the "Register Now" Button
describe('clicks "Register Now"', () => {
  it('clicks "Register Now"', () => {
    cy.visit('http://127.0.0.1:8000')
    cy.contains('Register Now').click
  })
})

// Navigating to the Login Page
describe('"Navigates to "Login Page"', () => {
  it('clicks "Log in" and navigates to a URL', () => {
    
    cy.visit('http://127.0.0.1:8000');

    
    cy.contains('Log in').click();

    //navogating to http://127.0.0.1.8000/login
    cy.location('pathname').should('include', '/login');
  });
});

 // Navigating to the Register page through "Register" Button
 describe('"Navigates to "Register"', () => {
  it('clicks "Register" and navigates to a URL', () => {
    
    cy.visit('http://127.0.0.1:8000');

    
    cy.contains('Register').click();

    //navogating to http://127.0.0.1.8000/login
     cy.location('pathname').should('include', '/register');
   });
  });

 // Navigating to the Register page through "Register Now" Button
describe('"Navigates to "Register Now"', () => {
  it('clicks "Register Now" and navigates to a URL', () => {
    
   cy.visit('http://127.0.0.1:8000');

    
   cy.contains('Register Now').click();

   //navogating to http://127.0.0.1.8000/login
    cy.location('pathname').should('include', '/register');
   });
 });
// ***********************************************************************************************************************************


//LOGIN PAGE
describe('Validating the login page', () => {
  it('should allow only student.apiit.lk email and require an 8-character password', () => {
    
    cy.visit('http://127.0.0.1:8000/login');

    // Email validation
    cy.get('input[name="email"]')
      .type('@students.apiit.lk')
      .then(($input) => {
        const emailValue = $input.val(); 
        
        if (!emailValue.endsWith('@students.apiit.lk')) {
          throw new Error('Whoops! These credentials do not match our records.');
        }

        // Password validation
        cy.get('input[name="password"]')
          .type('Password123')  // Example password
          .then(($passwordInput) => {
            const passwordValue = $passwordInput.val();

            if (passwordValue.length < 8) {
              throw new Error('Password must be at least 8 characters long.');
            }

            // Submit the form and wait for the redirection to complete
            cy.get('button').contains('Log in').click().then(() => {
              cy.url().then(url => {
                if (!url.includes('/dashboard')) {
                throw new Error('Redirection failed. Did not land on the dashboard.');
                }
              });
            });
          });
      });
  });
});
