<?php

namespace App\Services;

class ApiService
{
    private $sid;
    private $baseUrlApiV1;

    public function __construct(array $config = [])
    {
        $this->sid = $config['sid'];
        $this->baseUrlApiV1 = $config['baseUrlApiV1'];
    }

    /**
     * Get currencies
     * @return array
     * @throws \Exception
     */
    public function getCurrencies()
    {
        if (!$sid = $this->getSid()) {
            throw new \Exception('No authorization token');
        }

        $response = json_decode(\Curl::to($this->baseUrlApiV1 . 'get_currencies')->get(), true);

        if (!isset($response['result']) || $response['result'] !== 'ok') {
            return [];
        }

        return $response['currencies'] ?? [];
    }

    /**
     * Get rates
     * @return array
     */
    public function getRates()
    {
        $response = json_decode(\Curl::to($this->baseUrlApiV1 . 'get_currency_rates')->get(), true);

        if (!isset($response['result']) || $response['result'] !== 'ok') {
            return [];
        }

        return $response['rates'] ?? [];
    }

    /**
     * Is authenticated
     * @return mixed
     */
    public function isAuth()
    {
        return $this->sid;
    }

    /**
     * Get sid
     * @param array $data
     * @return string
     */
    public function getSid(array $data = [])
    {
        if ($this->sid) {
            return $this->sid;
        }

        $response = json_decode(\Curl::to($this->baseUrlApiV1 . 'oauth_auth')->withData($data)->post(), true);

        if (!isset($response['result']) || $response['result'] !== 'ok') {
            return '';
        }

        $sid = $response['sid'] ?? '';

        return $this->sid = $sid;
    }
}
