Feature: Signin via oauth2
    In order to signin on my site
    As a guest
    I need to have an oauth authorized account

    Scenario: Click on Google signin button
        Given I am on "/oauth.php"
        When I follow "signin_google"
        Then I should see "Sign in - Google Accounts"
