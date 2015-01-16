<?php namespace Igniweb\OAuth\Providers;

use Igniweb\OAuth\User;

class Github extends AbstractProvider implements ProviderInterface {
    
    /**
     * Return provider authorization URL
     * @return string
     */
    public function authorizationUrl()
    {
        $url = 'https://github.com/login/oauth/authorize?';
        
        return $url . http_build_query([
            'client_id' => $this->clientId,
            'scope'     => implode(':', $this->scopes),
        ]);
    }

    /**
     * Return provider access token URL
     * @return string
     */
    protected function accessTokenUrl()
    {
        return 'https://github.com/login/oauth/access_token';
    }

    /**
     * Return user object associated with the token
     * @param string
     * @return \Igniweb\OAuth\User|false
     */
    protected function userByToken($token)
    {
        $response = $this->client->get('https://api.github.com/user', [
            'headers' => ['Authorization' => 'token ' . $token],
        ]);

        $user = $response->json();
        if (empty($user) or ! empty($user['error']))
        {
            return false;
        }return $user;

        return new User([
            'login'  => $user['login'],
            'email'  => $user['email'],
            'name'   => $user['name'],
            'url'    => $user['html_url'],
            'avatar' => $user['avatar_url'],
        ]);
    }

}
