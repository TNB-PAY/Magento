<?php

namespace TNB\Pay\Controller\Verify;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use TNB\Pay\Helper\Data;
use TNB\Pay\Model\Payment\Tnb;

class Index extends Action implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var Session
     */
    private $checkoutSession;
    /**
     * @var Data
     */
    private $helper;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        Session $checkoutSession,
        Data $helper
    )
    {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->checkoutSession = $checkoutSession;
        $this->helper = $helper;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $memo = $this->getRequest()->getParam('memo');
        $total = $this->getRequest()->getParam('total');
        if ($memo) {
            $curlresponse = $this->helper->checkTnbTransaction($memo, $total);
            $data = ['success' => true, 'msg' => $curlresponse['msg'], 'flag' => $curlresponse['flag']];
            $result = $this->jsonFactory->create();
            $result->setData($data);
            return $result;
        }
    }
}
