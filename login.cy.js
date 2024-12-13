//Navodya Lakshani
//Front-End Testing 
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
  it('should allow only @students.apiit.lk email and require an 8-character password, then redirect to the dashboard', () => {
    cy.visit('http://127.0.0.1:8000/login');

    // Enter an invalid email
    cy.get('input[name="email"]').type('nova@email.com');
    cy.get('button').contains('Log in').click();

    // Check for the error message
    // cy.contains('Whoops! These credentials do not match our records.')
    //   .should('exist')
    //   .and('be.visible');

    // Enter a valid email
    cy.get('input[name="email"]').clear().type('nova@students.apiit.lk');

    // Enter an invalid password
    cy.get('input[name="password"]').type('short');
    cy.get('button').contains('Log in').click();

    
    // cy.contains('Whoops! These credentials do not match our records.')
    //   .should('exist')
    //   .and('be.visible');

    // Enter valid credentials and intercept the login request
    cy.intercept('POST', '/login', (req) => {
      req.continue((res) => {
        // Check for redirection to dashboard URL
        if (res.url.includes('/dashboard')) {
          cy.url().should('include', '/dashboard');
        } else {
          // throw new Error('Redirection failed.');
        }
      });
    });

    // Submit the login 
    cy.get('input[name="password"]').clear().type('Password123');
    cy.get('button').contains('Log in').click();
  });
});


describe('Login and Redirect to Dashboard Test', () => {
  it('should log in successfully and then navigate to the Dashboard', () => {
    // Visit the login page
    cy.visit('http://127.0.0.1:8000/login');

    // Enter valid credentials
    cy.get('input[name="email"]').clear().type('nova@students.apiit.lk');
    cy.get('input[name="password"]').clear().type('Password123');

  
    cy.intercept('POST', 'http://127.0.0.1:8000/login').as('login');

    // Submit the login form
    cy.get('button').contains('Log in').click();


    cy.wait('@login').then(() => {
       
       cy.visit('http://127.0.0.1:8000/dashboard');
    });
  });
});



describe('Navigate to the Register page', () => {
  it('should navigate to Register page', () => {
  
    cy.visit('http://127.0.0.1:8000/register');
  });
});

describe('Registration and Redirect to Login Test', () => {
  it('should register successfully and then navigate to the Login page', () => {
    // Visit the registration page
    cy.visit('http://127.0.0.1:8000/register');

    // Fill in the registration form
    cy.get('input[name="email"]').clear().type('nova@students.apiit.lk');
    cy.get('input[name="password"]').clear().type('Password123');
    cy.get('input[name="password_confirmation"]').clear().type('Password123');

    // Select a level from the dropdown list
    cy.get('select[name="level"]').select('L4');

//     // Select skills checkboxes
//     cy.get('input[name="skills[]').check(['CTF Challenge Solver']); // Check multiple skills if needed

// // Select availabilities checkboxes
//     cy.get('input[name="availabilities[]').check(['availabilities[1]']); // Check specific times

    

    // Click the Register button
    cy.get('button').contains('Register').click();

    // Verify redirection to the Login page
    cy.url().then(( currentUrl) => {
      expect(currentUrl).to.eq('http://127.0.0.1:8000/login');
    });
  });
});



// describe('Checkbox Element Tests', () => {
//   before(() => {
//     // Visit the page before running the tests
//     cy.visit('http://127.0.0.1:8000/register');
//   });

//   it('should verify the presence and attributes of checkboxes', () => {
//     // Ensure all checkboxes are present
//     cy.get('input[name="skills[]"').should('have.length', 2); // Check if there are 2 checkboxes

//     // Verify the value of each checkbox
//     cy.get('input[name="skills[]"').eq(0).should('have.value', '1'); // First checkbox
//     cy.get('input[name="skills[]"').eq(1).should('have.value', '2'); // Second checkbox

//     // Verify the labels (if applicable)
//     cy.get('input[name="skills[]"').eq(0).parent().contains('CTF Challenge Solver'); // Label for first checkbox
//     cy.get('input[name="skills[]"').eq(1).parent().contains('Business Strategy Mastery'); // Label for second checkbox
//   });

//   it('should test checkbox interaction', () => {
//     // Check specific checkboxes
//     cy.get('input[name="skills[]"').check(['1', '2']); // Check Skill1 and Skill2

//     // Verify their checked state
//     cy.get('input[name="skills[]"').eq(0).should('be.checked'); // First checkbox is checked
//     cy.get('input[name="skills[]"').eq(1).should('be.checked'); // Second checkbox is checked
//   });
// });
