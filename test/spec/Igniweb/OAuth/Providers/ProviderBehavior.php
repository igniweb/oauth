<?php namespace spec\Igniweb\OAuth\Providers;

require_once __DIR__ . '/../../MatchersTrait.php';

use GuzzleHttp\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Igniweb\MatchersTrait;

abstract class ProviderBehavior extends ObjectBehavior {

    use MatchersTrait;

    const CACHE_TTL = 60; // In minutes

    abstract public function stubAccessTokenRequest(Client $client);

    abstract public function stubUserRequest(Client $client);

    public function let(Client $client)
    {   // Remove SSL check
        $client->setDefaultOption('verify', false);

        $options = [
            'clientId'     => 'id',
            'clientSecret' => 'secret',
            'redirectUrl'  => 'redirect',
            'scopes'       => ['scope_1', 'scope_2'],
        ];

        $this->beConstructedWith($client, $options);
    }

    private function stubClient($client, $response)
    {   
/*
        $client = $this->stubGithub($client, $response);
        $client->post('https://github.com/login/oauth/access_token', [
            'body' => [
                'code'          => $this->code,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ])->willReturn($response);

        $client->get('https://api.github.com/user', [
            'headers' => ['Authorization' => 'token ' . $this->token],
        ])->willReturn($response);
*/
        return $client;
    }

    public function it_implements_provider_interface()
    {
        $this->shouldImplement('Igniweb\OAuth\Providers\ProviderInterface');
    }

    public function it_provides_valid_authorization_url()
    {
        $this->authorizationUrl()->shouldBeValidUrl();
    }

    public function it_get_an_access_token_and_fetch_a_user(Client $client)
    {
        $code = 'code';

        // $client = $this->stubAccessTokenRequest($client);
        // $client = $this->stubUserRequest($client);

        $this->accessToken($code)->shouldBeCalled();
        $this->userByToken('token')->shouldBeCalled();

        $this->user($code);
    }

/*
    protected function cache($key, $data = null)
    {
        if (empty($data))
        {   // Getter
            return $this->getCache($key);
        }

        // Setter
        return $this->setCache($key, $data);
    }

    private function setCache($key, $data)
    {
        $cache = __DIR__ . '/cache/' . $key;

        return file_put_contents($cache, $data);
    }

    private function getCache($key)
    {
        $cache = __DIR__ . '/cache/' . $key;
        if (file_exists($cache) and ((time() - filemtime($cache)) < (static::CACHE_TTL * 60)))
        {
            return file_get_contents($cache);
        }

        return false;
    }
*/

}
