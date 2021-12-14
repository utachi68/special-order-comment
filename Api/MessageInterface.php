<?php

namespace Advox\SpecialOrderComment\Api;
/**
 * Interface MessageInterface
 * @package Advox\SpecialOrderComment\Api
 */
interface MessageInterface
{
    /**
     * @param string $message
     * @return void
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getMessage();
}
