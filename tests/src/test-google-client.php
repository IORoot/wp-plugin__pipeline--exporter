<?php

/**
 * Class googleClientTest
 *
 * @package Andyp_exporter
 */

/**
 * @testdox Testing the \ue\exporter class
 */
class googleClientTest extends WP_UnitTestCase {

    /**
     * @before
     */
	public function setup()
    {
        parent::setUp();

        $this->oauth = new oauth_google_client();

    }

    public function tearDown(): void
    {
        $this->client = null;
    }

    
    public function test_google_client() {

        $got = $this->oauth;
        $this->assertIsObject($got);
    }

    public function test_transient_exists()
    {
        $this->refresh_token = YT_OAUTH_REFRESH_TOKEN;
        $got = $this->refresh_token;
        $this->assertIsString($got);
    }

    public function test_use_refresh_token_to_get_access_token()
    {
        $this->oauth->set_scope("https://www.googleapis.com/auth/youtube.force-ssl");
        $this->oauth->set_refresh_token(YT_OAUTH_REFRESH_TOKEN);
        $this->oauth->use_refresh_token();
        $got = $this->oauth->get_client();
        $this->assertIsObject($got);
    }



    public function test_access_token_is_set()
    {
        $want = 'access_token';

        $this->oauth->set_scope("https://www.googleapis.com/auth/youtube.force-ssl");
        $this->oauth->set_refresh_token(YT_OAUTH_REFRESH_TOKEN);
        $this->oauth->use_refresh_token();
        $client = $this->oauth->get_client();
        $got = $client->getAccessToken();
        
        $this->assertArrayHasKey($want, $got);
    }

}
