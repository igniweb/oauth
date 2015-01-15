<?php namespace Igniweb\OAuth\Providers;

class Google extends AbstractProvider {

    /**
     * Return provider authorization base URL
     * @return string
     */
    public function urlAuthorize()
    {
        return 'https://accounts.google.com/o/oauth2/auth';
    }
    
}
