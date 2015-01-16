<?php namespace Igniweb\OAuth\Exceptions;

use Exception;

class OAuthException extends Exception {

    /**
     * JSON decoded response from OAuth provider
     * @var array
     */
    protected $result;

    /**
     * Exception instance constructor
     * @param array $result
     * @return void
     */
    public function __construct(array $result = [])
    {
        $this->result = $result;

        $code = isset($result['code']) ? $result['code'] : 0;

        $message = $this->getResultMessage();

        parent::__contruct($message, $code);
    }

    /**
     * Return exception tailored message
     * @return string
     */
    private function getResultMessage()
    {
        if (isset($this->result['error']))
        {
            return $this->result['error'];
        }

        return isset($this->result['message']) ? $this->result['message'] : 'Unknow error';
    }

    /**
     * Get exception type definition
     * @return string
     */
    public function getType()
    {
        $result = 'Exception';

        if (isset($this->result['error']) and is_string($this->result['error']))
        {
            $result = $this->result['error'];
        }

        return $result;
    }

    /**
     * Make debugging easier
     * @return string
     */
    public function __toString()
    {
        $string = $this->getType() . ': ';

        if ($this->code !== 0)
        {
            $string .= ' [error #' . $this->code . '] - ';
        }

        return $string . $this->message;
    }

}
