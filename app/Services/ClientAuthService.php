<?php

namespace App\Services;

class ClientAuthService
{

    private $code;
    private $responseType;
    private $clientId;
    private $clientSecret;
    private $scope;
    private $baseOauthUrl;
    private $redirectUrlRoute = 'oauthRedirectHandler';

    public function __construct(array $config = [])
    {
        $this->setApiConfig($config);
    }

    /**
     * Set config
     * @param array $config
     * @throws \Exception
     */
    private function setApiConfig(array $config)
    {
        if (empty($config['clientId']) || empty($config['clientSecret'])) {
            throw new \Exception('Bad configuration!');
        }

        $this->clientId = $config['clientId'];
        $this->clientSecret = $config['clientSecret'];
        $this->responseType = $config['responseType'] ?? null;
        $this->scope = $config['scope'] ?? '';
        $this->baseOauthUrl = $config['baseOauthUrl'] ?? '';
        $this->code = $config['code'];

    }

    /**
     * Get auth url
     * @return string
     */
    public function getUrlOauthUser()
    {
        $oauthUrl = $this->baseOauthUrl . 'user';

        $urlParams = http_build_query([
            'response_type' => $this->responseType,
            'redirect_uri' => route($this->redirectUrlRoute),
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => $this->scope
        ]);

        return "{$oauthUrl}?{$urlParams}";
    }

    /**
     * Get user data
     * @return array
     */
    public function getUserData()
    {
        $accessToken = $this->getAccessToken();

        if ($accessToken && $data = $this->getData($accessToken)) {
            return $data;
        }

        return [];
    }

    /**
     * Get data to send
     * @param string $token
     * @return array
     */
    private function getData(string $token)
    {
        if (!$token) {
            return [];
        }

        return json_decode(\Curl::to($this->baseOauthUrl . 'data')
            ->withData([
                'access_token' => $token
            ])
            ->get(), true);
    }

    /**
     * Get access token
     * @return string
     */
    private function getAccessToken()
    {

        if (!$this->code) {
            return '';
        }

        $token = json_decode(
            \Curl::to($this->baseOauthUrl . 'client')
                ->withData([
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'authorization_code',
                    'code' => $this->code,
                    'redirect_uri' => route($this->redirectUrlRoute),
                    'scope' => $this->scope,
                ])->get(), true);

        return $token['access_token'] ?? '';
    }

    /**
     * Set code
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }
}
