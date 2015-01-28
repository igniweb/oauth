<?php namespace Igniweb\OAuth\Providers;

use Igniweb\OAuth\User;

class Github extends AbstractProvider {
    
    /**
     * Return provider authorization URL
     * @return string
     */
    public function authorizationUrl()
    {
        $url = 'https://github.com/login/oauth/authorize?';
        
        return $url . http_build_query([
            'client_id' => $this->clientId,
            'scope'     => implode(',', $this->scopes),
        ]);
    }

    /**
     * POST request for connected account access token
     * @param string $code
     * @return \GuzzleHttp\Message\Response
     */
    protected function requestAccessToken($code)
    {
        return $this->client->post('https://github.com/login/oauth/access_token', [
            'body' => [
                'code'          => $code,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
            'headers' => [
                'Accept' => 'application/json',
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
        $request = $this->client->get('https://api.github.com/user', [
            'headers' => ['Authorization' => 'token ' . $token],
        ]);

        $response = $request->json();
        if (empty($response) or ! empty($response['error']))
        {
            return false;
        }

        return $response;
    }

    /**
     * Map object to fit \Igniweb\OAuth\User object
     * @param string $user
     * @return \Igniweb\OAuth\User|false
     */
    protected function mapUser(array $user)
    {
        return new User([
            'provider' => 'github',
            'login'    => $user['login'],
            'email'    => $user['email'],
            'name'     => $user['name'],
            'url'      => 'https://github.com/' . $user['login'],
            'avatar'   => $user['avatar_url'],
        ]);
    }

}
