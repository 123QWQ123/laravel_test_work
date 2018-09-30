<?php
return [

    'response_type' => env('API_RESPONSE_TYPE', 'code'),
    'client_id' => env('API_CLIENT_ID', 'test-task'),
    'client_secret' => env('API_CLIENT_SECRET', 'soHievJa'),
    'scope' => env('API_SCOPE', 'firstname,surname,email,phone,pwhash,viber,skype,wechat,trust_level,otp,totp_secret'),
    'oauth_url' => env('API_OAUTH_URL', 'https://testing.e-id.cards/oauth/'),
    'base_api_url_v1' => 'https://testing.bb.yttm.work:5000/v1/',

];
