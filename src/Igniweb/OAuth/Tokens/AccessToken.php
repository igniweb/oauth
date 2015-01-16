<?php namespace Igniweb\OAuth\Tokens;

use InvalidArgumentException;

class AccessToken {

    /**
     * Provider returned access token
     * @var string
     */
    public $accessToken;

    /**
     * Expiration time of the token
     * @var integer
     */
    public $expires;

    /**
     * User unique ID
     * @var string
     */
    public $uid;

    /**
     * AccessToken constructor
     * @param array $options
     * @throws InvalidArgumentException
     * @return void
     */
    public function __construct(array $options = [])
    {
        if ( ! isset($options['access_token']))
        {
            throw new InvalidArgumentException('Required option: access_token' . PHP_EOL . print_r($options, true));
        }

        $this->accessToken = (string) $options['access_token'];

        if (isset($options['uid']))
        {
            $this->uid = $options['uid'];
        }

        // Compute token expiration time
        if ( ! empty($options['expires_in']))
        {
            $this->expires = time() + ((int) $options['expires_in']);
        } 
        elseif ( ! empty($options['expires']))
        {
            $expires = $options['expires'];
            $expiresInFuture = $expires > time();
            $this->expires = $expiresInFuture ? $expires : time() + ((int) $expires);
        }
    }

    /**
     * Make debugging easier
     * @return string
     */
    public function __toString()
    {
        return $this->accessToken;
    }

}
