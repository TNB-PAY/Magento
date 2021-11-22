<?php

namespace TNB\Pay\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use TNB\Pay\Model\Payment\Tnb;

class Data extends AbstractHelper
{
    /**
     * @var Curl
     */
    private $curl;
    /**
     * @var Json
     */
    private $json;
    /**
     * @var Tnb
     */
    private $tnb;

    public function __construct(
        Context $context,
        Curl $curl,
        Json $json,
        Tnb $tnb
    )
    {
        parent::__construct($context);
        $this->curl = $curl;
        $this->json = $json;
        $this->tnb = $tnb;
    }

    public function checkTnbTransaction($memo, $price)
    {
        //var_dump($memo);
        $store_address = $this->tnb->getAddress();
        $url = 'http://54.183.16.194/bank_transactions?limit=100';
        $this->curl->setOption(CURLOPT_HEADER, 0);
        $this->curl->setOption(CURLOPT_TIMEOUT, 60);
        $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->curl->get($url);
        $response = $this->curl->getBody();
        $data = $this->json->unserialize($response);
        $response = $data['results'];
        //print_r($response);
        foreach ($response as $key => $value) {
            //echo $value['memo'].$value['recipient'].$value['amount']."\n";
            if ($value['memo'] == $memo && $value['recipient'] == $store_address && $value['amount'] > 0) {
                if ($value['amount'] > $price && $this->checkTnbBlock($value['block']['id'])) {
                    $msg = "User Overpaid " . ($value['amount'] - $price) . " TNBC. Please discuss the overpayment with the customer or refund them at " . $value['block']['sender'];
                    return ['msg' => $msg, 'flag' => 2];
                } elseif ($value['amount'] < $price && $this->checkTnbBlock($value['block']['id'])) {
                    $msg = "User Underpaid: awaiting an additional payment of TNBC" . ($price - $value['amount']) . " this account number was used: " . $value['block']['sender'];
                    return ['msg' => $msg, 'flag' => 3];
                } elseif ($value['amount'] == $price && $this->checkTnbBlock($value['block']['id'])) {
                    $msg = "Completed purchase with account number " . $value['block']['sender'];
                    return ['msg' => $msg, 'flag' => 1];
                }
                //return ['msg' => "Purchase is not completed", 'flag' => 0];
            }
        }
        return ['msg' => "Purchase is not completed", 'flag' => 0];
    }

    public function checkTnbBlock($block) {
        $url = 'http://54.183.16.194/confirmation_blocks?block='.$block;
        $this->curl->setOption(CURLOPT_HEADER, 0);
        $this->curl->setOption(CURLOPT_TIMEOUT, 60);
        $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->curl->get($url);
        $response = $this->curl->getBody();
        $data = $this->json->unserialize($response);
        $response = $data['results'];
        if (isset($response[0]['id'])) {
            return true;
        } else {
            return false;
        }
    }
}
