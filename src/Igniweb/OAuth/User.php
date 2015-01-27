<?php namespace Igniweb\OAuth;

class User {

    /**
     * OAuth provider
     * @var string
     */
    public $provider;

    /**
     * User login
     * @var string
     */
    public $login;

    /**
     * User email address
     * @var string
     */
    public $email;

    /**
     * User name
     * @var string
     */
    public $name;

    /**
     * User URL
     * @var string
     */
    public $url;

    /**
     * User avatar URL
     * @var string
     */
    public $avatar;

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
    }

}
