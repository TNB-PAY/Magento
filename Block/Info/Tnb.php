<?php

namespace TNB\Pay\Block\Info;

use Magento\Payment\Block\Info;

class Tnb extends Info
{
    /**
     * @var string
     */
    protected $_template = 'TNB_Pay::info/tnb.phtml';

    public function getMemo()
    {
        $data = $this->getInfo()->getAdditionalInformation('additional_data');
        if (isset($data['memo'])) {
            return $data['memo'];
        } else {
            return '';
        }
    }
}
