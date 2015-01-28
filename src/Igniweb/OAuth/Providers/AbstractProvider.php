<?php namespace Igniweb\OAuth\Providers;

use GuzzleHttp\ClientInterface;
use Igniweb\OAuth\Exceptions\InvalidTokenException;
use Igniweb\OAuth\Exceptions\UnknownUserException;

abstract class AbstractProvider implements ProviderInterface {
    
    /**
     * Provider client ID
     * @var string
     */
    public $clientId;

    /**
     * Provider client secret
     * @var string
     */
    public $clientSecret;

    /**
     * Provider configured redirect URL
     * @var string
     */
    public $redirectUrl;

    /**
     * Queried scopes
     * @var array
     */
    public $scopes;

    /**
     * HTTP client object
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * Class instance constructor
     * @param \GuzzleHttp\ClientInterface $client
     * @param array $options
     * @return void
     */
    public function __construct(ClientInterface $client, array $options)
    {   
        $this->client = $client;

        // Whitelist public properties
        foreach ($options as $option => $value)
        {
            if (property_exists($this, $option))
            {
                $this->{$option} = $value;
            }
        }
    }

    /**
     * Return provider authorization URL
     * @return string
     */
    abstract public function authorizationUrl();

    /**
     * Return access token associated with code
     * @param string $code
     * @throws \Igniweb\OAuth\Exceptions\InvalidTokenException
     * @return string
     */
    public function accessToken($code)
    {
        $request = $this->requestAccessToken($code);

        $response = $request->json();
        if (empty($response['access_token']))
        {
            throw new InvalidTokenException('Invalid token matching "' . $code . '"');
        }

        return $response['access_token'];
    }

    /**
     * POST request for connected account access token
     * @param string $code
     * @return \GuzzleHttp\Message\Response
     */
    abstract protected function requestAccessToken($token);

    /**
     * Return user array associated with the token
     * @param string $token
     * @return array|false
     */
    abstract protected function userByToken($token);

    /**
     * Map object to fit \Igniweb\OAuth\User object
     * @param string $token
     * @return \Igniweb\OAuth\User|false
     */
    abstract protected function mapUser(array $user);

    /**
     * Return authenticated User instance
     * @param string $token
     * @throws \Igniweb\OAuth\Exceptions\UnknownUserException
     * @return \Igniweb\OAuth\User
     */
    public function user($token)
    {
        $user = $this->userByToken($token);
        if (empty($user))
        {
            throw new UnknownUserException('Unknow user associated with token "' . $token . '"');   
        }

        return $this->mapUser($user);
    }

}
