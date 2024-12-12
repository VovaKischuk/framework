<?php

use Behat\Behat\Context\Context;
use Framework\Controller\ExampleController;
use Framework\Http\ServerRequest;
use Framework\Route;
use Framework\Http\Response;
use Framework\Http\Uri;

class FeatureContext implements Context
{
    private Route $route;
    private ?Response $response = null;

    /**
     * @Given I have added a GET route for :path with TestController handling returning :responseBody
     */
    public function iHaveAddedAGetRouteForWithTestControllerHandlingReturning($path, $responseBody): void
    {
        $this->route = new Route();
        $this->route->addRoute('GET', $path, [ExampleController::class, 'testGet']);
    }

    /**
     * @When I dispatch a GET request to :path
     */
    public function iDispatchAGetRequestTo($path): void
    {
        $request = new ServerRequest('GET', new Uri($path));
        $this->response = $this->route->dispatch($request);
    }

    /**
     * @Then the response should be :expectedResponse
     */
    public function theResponseShouldBe($expectedResponse): void
    {
        if ((string)$this->response->getBody() !== $expectedResponse) {
            throw new \Exception("Expected response was not received.");
        }
    }
}
