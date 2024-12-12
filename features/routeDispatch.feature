Feature: Dispatch GET request using Route class
  In order to retrieve data efficiently
  As an application
  I need to be able to dispatch GET requests through the enhanced Route class

  Scenario: Dispatching a successful GET request
    Given I have added a GET route for "/api/data" with TestController handling returning "Get Response"
    When I dispatch a GET request to "/api/data"
    Then the response should be "Get response"