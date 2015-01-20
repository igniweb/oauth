<?php namespace spec\Igniweb\OAuth\Providers;

require_once __DIR__ . '/ProviderBehavior.php';

use GuzzleHttp\Client;

class GithubSpec extends ProviderBehavior {

    public function stubAccessTokenRequest(Client $client)
    {
        return $client;
    }

    public function stubUserRequest(Client $client)
    {
        return $client;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Igniweb\OAuth\Providers\Github');

        $this->shouldImplement('Igniweb\OAuth\Providers\AbstractProvider');
    }
    
}
