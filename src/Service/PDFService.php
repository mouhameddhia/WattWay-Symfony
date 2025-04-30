<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class PDFService
{
    private $client;
    private $apiKey;
    private $apiSecret;
    private $templateId;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiKey = "2f2641bd9ee356b6d5bf032cabb5bcec35c4685548c841a2df483f3cf23b8dd3";
        $this->apiSecret = "7a2100a2d807bc0bcd1b48067b9d72321a57b49041de35be24a670abf3775649";
        $this->templateId = 1389131;
    }

    private function generateJWT(): string
    {
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($this->apiSecret)
        );

        $now = new \DateTimeImmutable();
        $token = $config->builder()
            ->issuedBy($this->apiKey)
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 hour'))
            ->relatedTo('pdfgeneratorapi')
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }

    public function generatePDF(int $id, string $date, string $car, float $total, string $username, string $address, string $phoneNumber): ?string
    {
        $apiUrl = 'https://us1.pdfgeneratorapi.com/api/v4/documents/generate';

        // Remove commas from car name
        $car = str_replace(',', '', $car);

        $jsonPayload = [
            "template" => [
                "id" => (int)$this->templateId,
                "data" => [
                    "idBill" => $id,
                    "dateBill" => $date,
                    "car" => $car,
                    "priceCar" => round($total / 1.08, 2),
                    "dealerFees" => 500,
                    "deliveryFees" => 500,
                    "registrationFees" => 150,
                    "taxBill" => round(($total * 0.08) / 1.08, 2),
                    "totalAmountBill" => $total,
                    "firstNameClient" => $username,
                    "address" => $address,
                    "phoneNumber" => $phoneNumber,
                ]
            ],
            "format" => "pdf",
            "output" => "url"
        ];

        try {
            $jwt = $this->generateJWT();

            $response = $this->client->request('POST', $apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $jwt,
                    'Content-Type' => 'application/json',
                ],
                'json' => $jsonPayload,
            ]);

            $data = $response->toArray();

            if (isset($data['response'])) {
                return $data['response']; 
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
