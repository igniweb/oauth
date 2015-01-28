<?php namespace Igniweb\OAuth\Providers;

use Igniweb\OAuth\User;

class Google extends AbstractProvider {
    
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
     * POST request for connected account access token
     * @param string $code
     * @return \GuzzleHttp\Message\Response
     */
    protected function requestAccessToken($code)
    {
        return $this->client->post('https://accounts.google.com/o/oauth2/token', [
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
        $url = 'https://www.googleapis.com/plus/v1/people/me?' . http_build_query([
            // https://developers.google.com/+/api/latest/people?hl=fr
            'fields'       => 'id,url,name(familyName,givenName),displayName,emails/value,image/url',
            'alt'          => 'json',
            'access_token' => $token,
        ]);
        $request = $this->client->get($url);

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
            'provider' => 'google',
            'login'    => null,
            'email'    => ! empty($user['emails'][0]['value']) ? $user['emails'][0]['value'] : null,
            'name'     => $user['displayName'],
            'url'      => $user['url'],
            'avatar'   => ! empty($user['image']['url']) ? $user['image']['url'] : null,
        ]);
    }

}
