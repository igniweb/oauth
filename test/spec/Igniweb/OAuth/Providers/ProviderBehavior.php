<?php namespace spec\Igniweb\OAuth\Providers;

require_once __DIR__ . '/../../MatchersTrait.php';

use PhpSpec\ObjectBehavior;
use spec\Igniweb\MatchersTrait;

class ProviderBehavior extends ObjectBehavior {

    use MatchersTrait;

    public function let()
    {
        $this->beConstructedWith([
            'clientId'     => 'foo',
            'clientSecret' => 'bar',
            'redirectUrl'  => 'foobar',
            'scopes'       => ['foo', 'bar']
        ]);
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
        $this->accessToken('foo')->shouldBeCalled();
        $this->userByToken('bar')->shouldBeCalled();

        $this->user('foobar');
    }
    
}
