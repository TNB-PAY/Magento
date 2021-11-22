<?php

namespace TNB\Pay\Observer\Payment;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;

class DataAssignObserver extends AbstractDataAssignObserver
{

    public function execute(Observer $observer)
    {
        $data = $this->readDataArgument($observer);
        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_array($additionalData)) {
            return;
        }
        $paymentInfo = $this->readPaymentModelArgument($observer);
        $paymentInfo->setAdditionalInformation(
            PaymentInterface::KEY_ADDITIONAL_DATA,
            $additionalData
        );
    }
}
