<?php namespace spec\Igniweb\OAuth\Providers;

require_once __DIR__ . '/ProviderBehavior.php';

use PhpSpec\ObjectBehavior;

// class GoogleSpec extends ProviderBehavior {
class GoogleSpec extends ObjectBehavior {

    public function it_is_initializable()
    {
        $this->shouldHaveType('Igniweb\OAuth\Providers\Google');

        $this->shouldImplement('Igniweb\OAuth\Providers\AbstractProvider');
    }
    
}
