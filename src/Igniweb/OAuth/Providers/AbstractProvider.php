<?php namespace Igniweb\OAuth\Providers;

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
     * @return void
     */
    public function __construct($options = [])
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
     * @param string $grant
     * @param array $params
     * @return string
     */
    public function getAccessToken($grant = 'authorization_code', $params = [])
    {
        
    }
    
}
