<?php

namespace Omnipay\Monobank\Message;

class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://api.monobank.ua/api/merchant/invoice/create';

    public function getData()
    {
        $this->validate('amount', 'currency', 'returnUrl');

        $description = $this->getDescription() ?? 'Пополнение баланса';

        $data = [];
        $data['amount'] = $this->getAmount() * 100; // Amount in kopecks
        $data['ccy'] = 980; // Currency code for UAH
        $data['merchantPaymInfo'] = [
            'reference' => $this->getTransactionId(),
            'destination' => $description,
            'comment' => $description,
            'basketOrder' => [
                [
                    'name' => $description,
                    'qty' => 1,
                    'sum' => $this->getAmount() * 100,
                    'code' => 'product_code',
                ]
            ]
        ];
        $data['redirectUrl'] = $this->getReturnUrl();
        $data['webHookUrl'] = $this->getNotifyUrl();
        $data['validity'] = 7200; // 2 hours
        $data['paymentType'] = 'debit';

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->endpoint,
            [
                'X-Token' => $this->getSecret(),
                'X-Cms' => 'Flute CMS',
                'Content-Type' => 'application/json'
            ],
            json_encode($data)
        );

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);
        $responseData['transactionId'] = $this->getTransactionId();

        return $this->response = new PurchaseResponse($this, $responseData);
    }
}
