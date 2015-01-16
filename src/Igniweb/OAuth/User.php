<?php namespace Igniweb\OAuth;

class User {

    public $uid;

    public $name;

    public $firstName;

    public $lastName;

    public $email;

    public $imageUrl;

    /**
     * OAuth domain User instance constructor
     * @param array $data
     * @return void
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $val)
        {
            $property = $this->studly($key);

            if (property_exists($this, $property))
            {
                $this->$property = $val;
            }
        }
    }

    /**
     * Convert a value to studly caps case
     * @param string $str
     * @return string
     */
    private function studly($str)
    {
        $str = ucwords(str_replace('_', ' ', $str));

        return str_replace(' ', '', $str);
    }

}
