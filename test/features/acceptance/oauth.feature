Feature: Signin via oauth2 (Google)
    In order to signin on my site
    As a guest
    I need to have an authorized google account

    Scenario: Click on signin button
        Given I am on "/signin.php"
        When I follow "signin_google"
        Then the URL should match "http://www.google.fr"
