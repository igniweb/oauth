<?php namespace Igniweb\OAuth\Providers;

interface ProviderInterface {

    /**
     * Return provider authorization base URL
     * @return string
     */
    public function urlAuthorize();

    /**
     * Return provider authorization URL
     * @param array $options
     * @return string
     */
    public function getAuthorizationUrl($options = []);

    /**
     * Return provider authorization token
     * @param string $grant
     * @param array $params
     * @return string
     */
    public function getAccessToken($grant = 'authorization_code', $params = []);
    
}
