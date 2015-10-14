Feature: Login
  In order to access the website
  As an user
  I need to login

  Scenario: Redirect to login page if user is not logged in
    Given I am on "/logout"
    Given I am on "/"
    Then I should see "Login"

  Scenario: Open terms & conditions
    Given I am on "/"
    When I follow "Terms & Conditions"
    Then I wait for "Terms & Conditions" to appear

  Scenario: Login as admin
    Given I am on "/"
    When I fill in "username" with "admin@gs1au.org"
    When I fill in "password" with "123456"
    Then I press "Log in"
    Then I wait for "Admin" to appear
    Then I follow "userDropDownButton"
    Then I wait for "Logout" to appear
    Then I follow "Logout"