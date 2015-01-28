<?php namespace Igniweb\OAuth\Providers;

use Igniweb\OAuth\User;
use Igniweb\OAuth\Exceptions\InvalidTokenException;

class Instagram extends AbstractProvider {
    
    /**
     * Raw user object returned with access token
     * @var array
     */
    private $user;

    /**
     * Return provider authorization URL
     * @return string
     */
    public function authorizationUrl()
    {
        $url = 'https://api.instagram.com/oauth/authorize/?';
        
        return $url . http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUrl,
            'scope'         => implode(' ', $this->scopes),
            'response_type' => 'code',
        ]);
    }

    /**
     * Return access token associated with code
     * Overrided to store the $response['user'] to avoid future userByToken() request
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

        $this->user = $response['user'];

        return $response['access_token'];
    }

    /**
     * POST request for connected account access token
     * @param string $code
     * @return \GuzzleHttp\Message\Response
     */
    protected function requestAccessToken($code)
    {
        return $this->client->post('https://api.instagram.com/oauth/access_token', [
            'body' => [
                'code'          => $code,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri'  => $this->redirectUrl,
                'grant_type'    => 'authorization_code',
            ],
        ]);
    }

    /**
     * Return user array associated with the token
     * @param string $token
     * @return array|false
     */
    protected function userByToken($token)
    {
        return $this->user;
    }

    /**
     * Map object to fit \Igniweb\OAuth\User object
     * @param string $user
     * @return \Igniweb\OAuth\User|false
     */
    protected function mapUser(array $user)
    {
        return new User([
            'provider' => 'instagram',
            'login'    => $user['username'],
            'email'    => null,
            'name'     => ! empty($user['full_name']) ? $user['full_name'] : null,
            'url'      => 'https://instagram.com/' . $user['username'],
            'avatar'   => $user['profile_picture'],
        ]);
    }

}
