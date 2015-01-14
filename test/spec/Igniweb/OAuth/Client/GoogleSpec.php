<?php namespace spec\Igniweb\OAuth\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GoogleSpec extends ObjectBehavior {

    public function it_is_initializable()
    {
        $this->shouldHaveType('Igniweb\OAuth\Client\Google');
    }
    
}
