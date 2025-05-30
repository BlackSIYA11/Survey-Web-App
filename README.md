**Survey Form Project**

Overview

This project is a web-based survey form built with HTML, CSS, and PHP. Users submit personal details, select their favorite foods, and rate various statements. The backend validates the input, stores responses in a MySQL database, and provides user feedback with automatic redirect after submission.

Features
Responsive HTML form with fields for:

Full Name

Email

Date of Birth (with age validation between 5 and 120)

Cellphone Number (10-digit format)

Multiple selectable food preferences (minimum one required)

Ratings (1 to 5) for several lifestyle statements

Server-side validation for all inputs

Sanitized input to prevent SQL injection

Data stored securely in a MySQL database

User-friendly error messages on invalid input

Success message displayed after submission

Automatic redirect to home page after submission

Form reset/clear functionality upon submission

Technologies Used:

Frontend: HTML5, CSS3, JavaScript

Backend: PHP 7+

Database: MySQL

Server: Apache ( via WAMP)

Usage:

Fill out the survey form with valid information.

Ensure at least one food choice is selected.

Rate all lifestyle statements.

Click Submit.

After successful submission, you will see a thank-you message and be redirected to the home page.

If validation fails, error messages will guide you to correct inputs.


