<?php

namespace Advox\SpecialOrderComment\Observer\Sales;

use Advox\SpecialOrderComment\Api\MessageInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\MessageQueue\PublisherInterface;
use Psr\Log\LoggerInterface;

/**
 * Class OrderPlaceAfter
 * @package Advox\SpecialOrderComment\Observer\Sales
 */
class OrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface
{
    const TOPIC_NAME = 'order_comment.add';
    const XML_PATH_SPECIAL_COMMENT_ENABLE = 'special_order_comment/general/enable';

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * OrderPlaceAfter constructor.
     * @param Data $jsonHelper
     * @param PublisherInterface $publisher
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param MessageInterface $message
     */
    public function __construct(
        Data $jsonHelper,
        PublisherInterface $publisher, // use for publish message in RabbitMQ
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        MessageInterface $message
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->publisher = $publisher;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->message = $message;
    }

    /**
     * Execute method.
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        if ($this->scopeConfig->getValue(self::XML_PATH_SPECIAL_COMMENT_ENABLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            $order = $observer->getEvent()->getOrder();

            try {
                $this->message->setMessage($order->getIncrementId());
                $this->publisher->publish(self::TOPIC_NAME, $this->message); //Here we are using order id for message publish
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $this;
    }
}
