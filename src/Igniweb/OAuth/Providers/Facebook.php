<?php namespace Igniweb\OAuth\Providers;

use GuzzleHttp\Exception\ClientException;
use Igniweb\OAuth\User;
// https://developers.facebook.com/docs/facebook-login/manually-build-a-login-flow/v2.2?locale=fr_FR
class Facebook extends AbstractProvider {
    
    /**
     * Return provider authorization URL
     * @return string
     */
    public function authorizationUrl()
    {
        $url = 'https://www.facebook.com/dialog/oauth?';
        
        return $url . http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUrl,
            'scope'         => implode(',', $this->scopes),
            'response_type' => 'code',
        ]);
    }

    /**
     * Return access token associated with code
     * @param string $code
     * @throws \Igniweb\OAuth\Exceptions\InvalidTokenException
     * @return string
     */
    public function accessToken($code)
    {
        $request = $this->requestAccessToken($code);

        parse_str($request->getBody()->getContents(), $response);
        if (empty($response['access_token']))
        {
            throw new InvalidTokenException('Invalid token matching "' . $code . '"');
        }

        return $response['access_token'];
    }

    /**
     * POST request for connected account access token
     * @param string $code
     * @return \GuzzleHttp\Message\Response
     */
    protected function requestAccessToken($code)
    {
        $url = 'https://graph.facebook.com/oauth/access_token?' . http_build_query([
            'code'          => $code,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUrl,
        ]);

        return $this->client->get($url);
    }

    /**
     * Return user array associated with the token
     * @param string $token
     * @return array|false
     */
    protected function userByToken($token)
    {
        $url = 'https://graph.facebook.com/me?' . http_build_query([
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
            'provider' => 'facebook',
            'login'    => null,
            'email'    => $user['email'],
            'name'     => $user['name'],
            'url'      => $user['link'],
            'avatar'   => 'https://graph.facebook.com/' . $user['id'] . '/picture?type=large',
        ]);
    }

}
