<?php namespace Igniweb\OAuth\Providers;

use GuzzleHttp\Client;
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
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Class instance constructor
     * @param array $options
     * @return void
     */
    public function __construct(array $options)
    {   // Whitelist public properties
        foreach ($options as $option => $value)
        {
            if (property_exists($this, $option))
            {
                $this->{$option} = $value;
            }
        }

        // Setup HTTP client (dependencies to Guzzle)
        $this->client = new Client;
    }

    /**
     * Return provider authorization URL
     * @return string
     */
    abstract public function authorizationUrl();

    /**
     * Return access token associated with the code
     * @param string $code
     * @return string|false
     */
    abstract protected function accessToken($code);

    /**
     * Return user object associated with the token
     * @param string $token
     * @return \Igniweb\OAuth\User|false
     */
    abstract protected function userByToken($token);

    /**
     * Return authenticated User instance
     * @param string $code
     * @throws \Igniweb\OAuth\Exceptions\InvalidTokenException
     * @throws \Igniweb\OAuth\Exceptions\UnknownUserException
     * @return \Igniweb\OAuth\User
     */
    public function user($code)
    {
        $token = $this->accessToken($code);
        if (empty($token))
        {
            throw new InvalidTokenException('No token associated with code "' . $code . '"');
        }

        $user = $this->userByToken($token);
        if (empty($user))
        {
            throw new UnknownUserException('Unknow user associated with token "' . $token . '"');   
        }

        return $user;
    }

}
