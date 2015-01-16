<?php namespace spec\Igniweb\OAuth\Providers;

use Igniweb\OAuth\Tokens\AccessToken;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GoogleSpec extends ObjectBehavior {

    public function it_is_initializable()
    {
        $this->shouldHaveType('Igniweb\OAuth\Providers\Google');
    }

    public function it_should_extend_abstract_provider()
    {
        $this->shouldBeAnInstanceOf('Igniweb\OAuth\Providers\AbstractProvider');
    }

    public function it_provides_valid_authorize_url()
    {
        $this->urlAuthorize()->shouldBeValidUrl();
    }

    public function it_provides_valid_access_token_url()
    {
        $this->urlAccessToken()->shouldBeValidUrl();
    }

    public function it_provides_valid_user_details_url()
    {
        $token = new AccessToken(['access_token' => 'token']);

        $this->urlUserDetails($token)->shouldBeValidUrl();
    }

    public function getMatchers()
    {
        return [
            'beValidUrl' => function ($url)
            {
                return (filter_var($url, FILTER_VALIDATE_URL) === false) ? false : true;
            }
        ];
    }
    
}
