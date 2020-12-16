<?php

class oauth_google_client
{

    private $client;

    private $token_name;

    private $refresh_token;

    private $scope;


    public function set_token_name($token)
    {
        $this->token_name = $token;
    }

    public function set_scope($scope)
    {
        $this->scope = $scope;
    }

    public function set_refresh_token($refresh_token)
    {
        $this->refresh_token = $refresh_token;
    }


    public function run()
    {
        if (empty($this->refresh_token)){
            $this->refresh_token = get_transient( $this->token_name );
        }

        if ( false !== ( $this->refresh_token ) ) {
            $this->use_refresh_token();
            return;
        }

        return;
    }


    public function get_client()
    {
        return $this->client;
    }


    /**
     * create_client
     *
     * Uses the google client library.
     * 
     * @return void
     */
    private function use_refresh_token()
    {
        $this->client = new Google_Client();
        
        $this->client->setAuthConfigFile(GOOGLE_APPLICATION_CREDENTIALS);

        $this->client->addScope($this->scope);

        $this->client->setPrompt('consent');  // Needed to get refresh_token every time.

        $this->client->setAccessType('offline');

        $this->client->setApiFormatV2(TRUE);

        $this->client->refreshToken($this->refresh_token);

    }

}