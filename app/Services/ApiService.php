<?php

namespace App\Services;


use Ixudra\Curl\Facades\Curl;

class ApiService
{
    protected $redirectUrlAuth = '/';
    protected $redirectUrlToken = '';
    private $code;
    private $sid;
    private $responseType;
    private $clientId;
    private $clientSecret;
    private $scope;
    private $baseOauthUrl;
    private $baseUrlApiV1;
    private $session;

    public function __construct(\Illuminate\Session\SessionManager  $session, array $config = [])
    {
        $this->code = $session->get('code', null);
        $this->sid = $session->get('sid', null);
        $this->session = $session;

        $this->setApiConfig($config);
    }

    /**
     * Set config
     * @param array $config
     */
    private function setApiConfig(array $config)
    {
        $this->responseType = $config['responseType'] ?? null;
        $this->clientId     = $config['clientId'] ?? null;
        $this->clientSecret = $config['clientSecret'] ?? null;
        $this->scope        = $config['scope'] ?? '';
        $this->baseOauthUrl = $config['baseOauthUrl'] ?? '';
        $this->baseUrlApiV1 = $config['baseUrlApiV1'] ?? '';
    }

    /**
     * Get auth url
     * @param string $redirectUrl
     * @return string
     */
    public function getUrlOauthUser(string $redirectUrl = '')
    {
        if (!$redirectUrl) {
            $redirectUrl = url($this->redirectUrlAuth);
        }

        $oauthUrl = $this->baseOauthUrl . 'user';

        $urlParams = http_build_query([
            'response_type' => $this->responseType,
            'redirect_uri' => $redirectUrl,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => $this->scope
        ]);

        return "{$oauthUrl}?{$urlParams}";
    }

    /**
     * @param string $redirectUrl
     * @return bool|null
     */
    public function getAccessToken(string $redirectUrl = '')
    {
        if (!$redirectUrl) {
            $redirectUrl = url($this->redirectUrlToken);
        }

        if (!$this->code) {
            return false;
        }

        $token = json_decode(
            \Curl::to($this->baseOauthUrl . 'client')
                ->withData([
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'authorization_code',
                    'code' => $this->code,
                    'redirect_uri' => $redirectUrl,
                    'scope' => $this->scope,
                ])->get(),true);

        return $token['access_token'] ?? false;
    }

    /**
     * Get data to send
     * @param string $token
     * @return bool|mixed
     */
    public function getData(string $token)
    {
        if (!$token) {
            return false;
        }

        return json_decode(\Curl::to($this->baseOauthUrl . 'data')
            ->withData([
                'access_token' => $token
            ])
            ->get());
    }

    /**
     * Get currencies
     * @return array
     */
    public function getCurrencies()
    {
        if (!$sid = $this->getSid()) {
            return [];
        }

        $response = json_decode(Curl::to($this->baseUrlApiV1 . 'get_currencies')->get(), true);

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
        $response = json_decode(Curl::to($this->baseUrlApiV1 . 'get_currency_rates')->get(), true);

        if (!isset($response['result']) || $response['result'] !== 'ok') {
            return [];
        }

        return $response['rates'] ?? [];
    }

    /**
     * Get sid
     * @return bool|string
     */
    public function getSid()
    {
        if ($this->sid) {
            return $this->sid;
        }

        return $this->oauth();
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
     * Authentication
     * @return bool|string
     */
    public function oauth()
    {
        $accessToken = $this->getAccessToken();
        if ($accessToken && $data = $this->getData($accessToken)) {
            $response = json_decode(Curl::to($this->baseUrlApiV1 . 'oauth_auth')->withData($data)->post(), true);

            if (!isset($response['result']) || $response['result'] !== 'ok') {
                return false;
            }

            $sid = $response['sid'] ?? '';

            $this->session->put(['sid' => $sid]);

            return $this->sid = $sid;
        }

        return false;
    }
}
