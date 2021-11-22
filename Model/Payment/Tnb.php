<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace TNB\Pay\Model\Payment;

use Magento\Payment\Model\Method\AbstractMethod;

class Tnb extends AbstractMethod
{

    protected $_code = "tnb";
    protected $_isOffline = true;
    protected $_canUseInternal = false;
    protected $_infoBlockType = \TNB\Pay\Block\Info\Tnb::class;

    public function getInstructions()
    {
        return $this->getConfigData('instructions');
    }

    public function getAddress()
    {
        return $this->getConfigData('address');
    }

    public function getRates()
    {
        return $this->getConfigData('rates');
    }
}

