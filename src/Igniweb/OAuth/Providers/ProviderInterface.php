<?php namespace Igniweb\OAuth\Providers;

use Igniweb\OAuth\Tokens\AccessToken;
use Igniweb\OAuth\User;

interface ProviderInterface {

    /**
     * Return provider authorization base URL
     * @return string
     */
    public function urlAuthorize();

    /**
     * Return provider access token URL
     * @return string
     */
    public function urlAccessToken();

    /**
     * Return provider user details oauth API URL
     * @param AccessToken $token
     * @return string
     */
    public function urlUserDetails(AccessToken $token);

    /**
     * Return provider authorization URL
     * @param array $options
     * @return string
     */
    public function getAuthorizationUrl($options = []);

    /**
     * Return provider authorization token
     * @param array $options
     * @return string
     */
    public function getAccessToken($options = []);

    /**
     * Return an array containing user information
     * @param AccessToken $token
     * @return array
     */
    public function getUser(AccessToken $token);

    /**
     * Return an OAuth\User object
     * @param object $response
     * @return User
     */
    public function userDetails($response);
    
}
