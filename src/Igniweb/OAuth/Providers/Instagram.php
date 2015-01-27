<?php namespace Igniweb\OAuth\Providers;

use Igniweb\OAuth\User;

class Instagram extends AbstractProvider implements ProviderInterface {
    
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
     * Return access token associated with the code
     * @param string $code
     * @return string|false
     */
    protected function accessToken($code)
    {
        $response = $this->requestAccessToken($code);

        $accessToken = $response->json();
        if (empty($accessToken['access_token']))
        {
            return false;
        }

        $this->user = $accessToken['user'];

        return $accessToken['access_token'];
    }

    /**
     * POST request for connected account access token
     * @param string $code
     * @return \GuzzleHttp\Message\Response
     */
    private function requestAccessToken($code)
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
     * Return user object associated with the token
     * @param string $token
     * @return \Igniweb\OAuth\User|false
     */
    protected function userByToken($token)
    {
        if (empty($this->user))
        {
            return false;
        }

        return new User([
            'provider' => 'instagram',
            'login'    => $this->user['username'],
            'email'    => null,
            'name'     => $this->user['full_name'],
            'url'      => $this->user['website'],
            'avatar'   => $this->user['profile_picture'],
        ]);
    }

}
