<?php namespace Igniweb\OAuth\Providers;

interface ProviderInterface {
    
    /**
     * Return provider authorization URL
     * @return string
     */
    public function authorizationUrl();

    /**
     * Return authenticated User instance
     * @param string $code
     * @throws \Igniweb\OAuth\Exceptions\InvalidTokenException
     * @throws \Igniweb\OAuth\Exceptions\UnknownUserException
     * @return \Igniweb\OAuth\User
     */
    public function user($code);

}
