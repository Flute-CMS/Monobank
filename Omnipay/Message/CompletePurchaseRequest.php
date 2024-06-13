<?php

namespace Omnipay\Monobank\Message;

use Flute\Core\Database\Entities\PaymentGateway;
use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        // Проверка наличия необходимых данных
        if (empty($data['invoiceId']) || empty($data['amount']) || empty($data['status']) || empty($data['reference'])) {
            throw new InvalidResponseException("Missing required data");
        }

        // Проверка подписи
        $x_sign = $this->httpRequest->headers->get('x-sign');

        if (!$this->verifySignature($raw, $x_sign)) {
            throw new InvalidResponseException("Invalid digital signature");
        }

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    private function verifySignature($data, $x_sign)
    {
        $pubKeyBase64 = $this->getPublicKey();
        $signature = base64_decode($x_sign);
        $publicKey = openssl_get_publickey(base64_decode($pubKeyBase64));

        $result = openssl_verify($data, $signature, $publicKey, OPENSSL_ALGO_SHA256);

        return $result === 1;
    }

    private function getPublicKey()
    {
        $gateway = rep(PaymentGateway::class)->findOne(['adapter' => 'Monobank', 'enabled' => true]);

        if( !$gateway ) {
            throw new \Exception('Monobank gateway wasnt found');
        }

        $params = json_decode($gateway->additional, true);

        if( isset( $params['public'] ) ) {
            return $params['public'];
        }

        $response = $this->httpClient->request('GET', 'https://api.monobank.ua/api/merchant/pubkey', [
            'X-Token' => $this->getSecret()
        ]);

        $responseDecoded = json_decode($response->getBody()->getContents(), true);

        if (!empty($responseDecoded['key'])) {
            $params['public'] = $responseDecoded['key'];

            $gateway->additional = json_encode($params);

            transaction($gateway)->run();

            return $params['public'];
        }

        throw new \Exception('Public key not found');
    }
}
