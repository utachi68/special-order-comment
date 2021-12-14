<?php

namespace Advox\SpecialOrderComment\Model;

use Advox\SpecialOrderComment\Api\MessageInterface;
use Advox\SpecialOrderComment\Api\SubscriberInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\OrderStatusHistoryRepositoryInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;

/**
 * Class Subscriber
 * @package Advox\SpecialOrderComment\Model
 */
class Subscriber implements SubscriberInterface
{
    /**
     * Comment text config path
     */
    const XML_PATH_COMMENT_TEXT = 'special_order_comment/general/comment_text';

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;


    /** @var OrderStatusHistoryRepositoryInterface */
    private $orderStatusRepository;

    /**
     * AddComment constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     * @param OrderStatusHistoryRepositoryInterface $orderStatusRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger,
        OrderStatusHistoryRepositoryInterface $orderStatusRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->orderStatusRepository = $orderStatusRepository;
    }

    /**
     * @param MessageInterface $message
     */
    public function processMessage(MessageInterface $message)
    {
        $orderId = $message->getMessage();
        try {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('increment_id', $orderId, 'eq')->create(); // filter order by increment Id
            $orderList = $this->orderRepository->getList($searchCriteria)->getItems();

            /** @var Order $order */
            $order = reset($orderList); // get order from first element

            $orderHistory = null;
            if ($order) {
                $commentText = $this->scopeConfig->getValue(self::XML_PATH_COMMENT_TEXT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                $comment = $order->addCommentToStatusHistory(
                    $commentText,
                    false,
                    true
                );

                $this->orderStatusRepository->save($comment);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
