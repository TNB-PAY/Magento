<?php

namespace TNB\Pay\Model\Payment;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Framework\View\Asset\Repository;

class TnbConfigProvider implements ConfigProviderInterface
{
    protected $methodCode = "tnb";

    protected $url;

    protected $escaper;
    /**
     * @var \Magento\Payment\Model\MethodInterface
     */
    protected $method;

    public function __construct(
        PaymentHelper $paymentHelper,
        UrlInterface $url,
        Escaper $escaper,
        Repository $moduleAssetDir
    ) {
        $this->method = $paymentHelper->getMethodInstance($this->methodCode);
        $this->url = $url;
        $this->escaper = $escaper;
        $this->moduleAssetDir = $moduleAssetDir;
    }

    public function getConfig()
    {
        return $this->method->isAvailable() ? [
            'payment' => [
                'tnb' => [
                    'instructions' => $this->getInstructions(),
                    'paymentAcceptanceMarkSrc' => $this->paymentAcceptanceMarkSrc(),
                    'address' => $this->getTnbAddress(),
                    'rates' => $this->getTnbRates(),
                    'memo' => $this->getTnbMemo(),
                    'copyIcon' => $this->getCopyIcon()
                ]
            ]
        ] : [];
    }

    private function getInstructions()
    {
        return nl2br($this->escaper->escapeHtml($this->method->getInstructions()));
    }

    protected function paymentAcceptanceMarkSrc()
    {
        return $this->moduleAssetDir->getUrl("TNB_Pay::images/thenewboston-primary.52b925da.svg");
    }

    protected function getTnbAddress()
    {
        return $this->method->getAddress();
    }

    protected function getTnbRates()
    {
        return $this->method->getRates();
    }

    protected function getTnbMemo()
    {
        $memo = base64_encode(rand(100000000, 999999999));
        return $memo;
    }

    protected function getCopyIcon()
    {
        return $this->moduleAssetDir->getUrl("TNB_Pay::images/copy-clipborad-icon.svg");
    }
}
