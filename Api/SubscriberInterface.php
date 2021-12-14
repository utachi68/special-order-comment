<?php

namespace Advox\SpecialOrderComment\Api;

/**
 * Interface SubscriberInterface
 * @package Advox\SpecialOrderComment\Api
 */
interface SubscriberInterface
{
    /**
     * @param MessageInterface $message
     * @return void
     */
    public function processMessage(MessageInterface $message);
}
