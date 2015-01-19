<?php namespace spec\Igniweb;

trait MatchersTrait {

    public function getMatchers()
    {
        return [
            'beValidUrl' => function ($url)
            {
                return (filter_var($url, FILTER_VALIDATE_URL) === false) ? false : true;
            }
        ];
    }

}
