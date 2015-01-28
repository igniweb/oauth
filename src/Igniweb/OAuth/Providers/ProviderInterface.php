<?php namespace Igniweb\OAuth\Providers;

interface ProviderInterface {
    
    /**
     * Return provider authorization URL
     * @return string
     */
    public function authorizationUrl();

    /**
     * Return access token associated with code
     * @param string $code
     * @throws \Igniweb\OAuth\Exceptions\InvalidTokenException
     * @return string
     */
    public function accessToken($code);

    /**
     * Return authenticated User instance
     * @param string $token
     * @throws \Igniweb\OAuth\Exceptions\UnknownUserException
     * @return \Igniweb\OAuth\User
     */
    public function user($token);

}
