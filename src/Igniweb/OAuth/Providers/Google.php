<?php namespace Igniweb\OAuth\Providers;

use Igniweb\OAuth\User;

class Google extends AbstractProvider implements ProviderInterface {
    
    /**
     * Return provider authorization URL
     * @return string
     */
    public function authorizationUrl()
    {
        $url = 'https://accounts.google.com/o/oauth2/auth?';

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
        $response = $this->client->post('https://accounts.google.com/o/oauth2/token', [
            'body' => [
                'code'          => $code,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri'  => $this->redirectUrl,
                'grant_type'    => 'authorization_code',
            ],
        ]);

        $accessToken = $response->json();
        if (empty($accessToken['access_token']) or ! empty($accessToken['error']))
        {
            return false;
        }

        return $accessToken['access_token'];
    }

    /**
     * Return user object associated with the token
     * @param string $token
     * @return \Igniweb\OAuth\User|false
     */
    protected function userByToken($token)
    {
        $url = 'https://www.googleapis.com/plus/v1/people/me?' . http_build_query([
            // https://developers.google.com/+/api/latest/people?hl=fr
            'fields'       => 'id,url,name(familyName,givenName),displayName,emails/value,image/url',
            'alt'          => 'json',
            'access_token' => $token,
        ]);
        $response = $this->client->get($url);

        $user = $response->json();
        if (empty($user) or ! empty($user['error']))
        {
            return false;
        }

        return new User([
            'login'  => null,
            'email'  => ! empty($user['emails'][0]['value']) ? $user['emails'][0]['value'] : null,
            'name'   => $user['displayName'],
            'url'    => $user['url'],
            'avatar' => ! empty($user['image']['url']) ? $user['image']['url'] : null,
        ]);
    }

}
