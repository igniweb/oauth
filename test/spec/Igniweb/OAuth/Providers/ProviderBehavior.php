<?php namespace spec\Igniweb\OAuth\Providers;

require_once __DIR__ . '/../../MatchersTrait.php';

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Igniweb\MatchersTrait;

class ProviderBehavior extends ObjectBehavior {

    use MatchersTrait;

    protected $code = 'code';

    protected $token = 'token';

    protected $clientId = 'id';

    protected $clientSecret = 'secret';

    protected $redirectUrl = 'redirect';

    protected $scopes = ['foo', 'bar'];

    public function let(Client $client, Response $response)
    {
        $client = $this->stubClient($client, $response);   

        $this->beConstructedWith($client, [
            'clientId'     => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'redirectUrl'  => $this->redirectUrl,
            'scopes'       => $this->scopes,
        ]);
    }

    private function stubClient($client, $response)
    {   // Remove SSL check
        $client->setDefaultOption('verify', false);

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

    public function it_get_an_access_token_and_fetch_a_user()
    {
        $this->accessToken($this->code)->shouldBeCalled();
        $this->userByToken($this->token)->shouldBeCalled();

        $this->user($this->code);
    }
    
}
