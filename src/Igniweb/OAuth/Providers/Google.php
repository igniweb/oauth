<?php namespace Igniweb\OAuth\Providers;

use Igniweb\OAuth\Tokens\AccessToken;
use Igniweb\OAuth\User;

class Google extends AbstractProvider {

    /**
     * Return provider authorization base URL
     * @return string
     */
    public function urlAuthorize()
    {
        return 'https://accounts.google.com/o/oauth2/auth';
    }

    /**
     * Return provider access token URL
     * @return string
     */
    public function urlAccessToken()
    {
        return 'https://accounts.google.com/o/oauth2/token';
    }

    /**
     * Return provider user details oauth API URL
     * @param AccessToken $token
     * @return string
     */
    public function urlUserDetails(AccessToken $token)
    {
        return 'https://www.googleapis.com/plus/v1/people/me?fields=id%2Cname(familyName%2CgivenName)%2CdisplayName%2Cemails%2Fvalue%2Cimage%2Furl&alt=json&access_token=' . $token;
    }

    /**
     * Return an OAuth\User object
     * @param object $response
     * @return User
     */
    public function userDetails($response)
    {
        $response = (array) $response;

        return new User([
            'uid'        => $response['id'],
            'name'       => $response['displayName'],
            'first_name' => $response['name']->givenName,
            'last_name'  => $response['name']->familyName,
            'email'      => ! empty($response['emails'][0]->value) ? $response['emails'][0]->value : null,
            'image_url'  => ! empty($response['image']->url) ? $response['image']->url : null,
        ]);
    }
    
}
