<?php namespace Igniweb\OAuth\Providers;

use BadMethodCallException;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Service\Client;
use Igniweb\OAuth\Exceptions\OAuthException;
use Igniweb\OAuth\Tokens\AccessToken;
use Igniweb\OAuth\User;

abstract class AbstractProvider implements ProviderInterface {

    /**
     * OAuth client ID
     * @var string
     */
    public $clientId;

    /**
     * OAuth client secret
     * @var string
     */
    public $clientSecret;

    /**
     * OAuth application redirect URI
     * @var string
     */
    public $redirectUri;

    /**
     * OAuth application current state
     * @var string
     */
    public $state;

    /**
     * OAuth queried scopes
     * @var array
     */
    public $scopes;

    /**
     * Class constructor
     * @param array $options
     * @return void
     */
    public function __construct(array $options = [])
    {   // Whitelist public properties
        foreach ($options as $option => $value)
        {
            if (property_exists($this, $option))
            {
                $this->{$option} = $value;
            }
        }
    }

    /**
     * Return provider authorization base URL
     * @return string
     */
    abstract public function urlAuthorize();

    /**
     * Return provider access token URL
     * @return string
     */
    abstract public function urlAccessToken();

    /**
     * Return provider user details oauth API URL
     * @param AccessToken $token
     * @return string
     */
    abstract public function urlUserDetails(AccessToken $token);

    /**
     * Return an OAuth\User object
     * @param object $response
     * @return User
     */
    abstract public function userDetails($response);

    /**
     * Return provider authorization URL
     * @param array $options
     * @return string
     */
    public function getAuthorizationUrl($options = [])
    {
        $this->state = isset($options['state']) ? $options['state'] : md5(uniqid(rand(), true));

        $params = [
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'state'         => $this->state,
            'scope'         => is_array($this->scopes) ? implode(',', $this->scopes) : $this->scopes,
            'response_type' => isset($options['response_type']) ? $options['response_type'] : 'code',
        ];

        return $this->urlAuthorize() . '?' . http_build_query($params);
    }

    /**
     * Return provider authorization token
     * @param array $options
     * @throws BadMethodCallException
     * @throws OAuthException
     * @return AccessToken
     */
    public function getAccessToken($options = [])
    {
        if ( ! isset($options['code']))
        {
            throw new BadMethodCallException('Missing authorization code');
        }

        $params = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
        ];

        $response = $this->postParams(array_merge($params, $options));
        $result = json_decode($response, true);

        if (isset($result['error']) and ! empty($result['error']))
        {
            throw new OAuthException($result);
        }

        return new AccessToken($result);
    }

    /**
     * Return an array containing user information
     * @param AccessToken $token
     * @return array
     */
    public function getUser(AccessToken $token)
    {
        $response = json_decode($this->fetchUserDetails($token));

        return $this->userDetails($response);
    }

    /**
     * POST parameters using Guzzle Client and return either error message OR body response
     * @param array $params
     * @return string
     */
    protected function postParams(array $params)
    {
        try
        {
            $client = new Client;
            $client->setBaseUrl($this->urlAccessToken());

            $request = $client->post(null, null, $params)->send();
            $response = $request->getBody();
        }
        catch (BadResponseException $e)
        {
            $response = explode("\n", $e->getResponse());
            $response = end($response);
        }

        return $response;
    }

    /**
     * Fetch user details of the the given on the OAuth provider API
     * @param AccessToken $token
     * @throws OAuthException
     * @return string
     */
    protected function fetchUserDetails(AccessToken $token)
    {
        try
        {
            $client = new Client;
            $client->setBaseUrl($this->urlUserDetails($token));

            $request = $client->get()->send();
            $response = $request->getBody();
        }
        catch (BadResponseException $e)
        {
            $response = explode("\n", $e->getResponse());
            throw new OAuthException(end($response));
        }

        return $response;
    }
    
}
