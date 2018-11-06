<?php

namespace ContactDuty\Api;

use Exception as BaseException;

class Exception extends BaseException
{
    /** @var array */
    protected $errors = array();

    /**
     * @param string $message
     * @param int $code
     * @param BaseException|null $previous
     * @param array $errors
     */
    public function __construct(
        $message,
        $code = 0,
        BaseException $previous = null,
        $errors = array()
    ) {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
