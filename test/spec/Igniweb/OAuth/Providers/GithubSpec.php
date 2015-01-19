<?php namespace spec\Igniweb\OAuth\Providers;

require_once __DIR__ . '/ProviderBehavior.php';

use Prophecy\Argument;

class GithubSpec extends ProviderBehavior {

    public function it_is_initializable()
    {
        $this->shouldHaveType('Igniweb\OAuth\Providers\Github');

        $this->shouldImplement('Igniweb\OAuth\Providers\AbstractProvider');
    }
    
}
