<?php

namespace Advox\SpecialOrderComment\Model;

use Advox\SpecialOrderComment\Api\MessageInterface;

/**
 * Class Message
 * @package Advox\SpecialOrderComment\Model
 */
class Message implements MessageInterface
{
    /**
     * @var string
     */
    protected $message;

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        return $this->message = $message;
    }
}
