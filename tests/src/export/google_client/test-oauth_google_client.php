<?php

/**
 * Class oauth_google_client
 *
 * @package Andyp_exporter
 */

/**
 * @testdox Testing the oauth_google_client class
 */
class testOauthGoogleClient extends PHPUnit_Framework_TestCase {

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

        
    /** 
	 * @test
     * 
     * @testdox Test oauth_google_client wrapper class exists and returns an object.
     * 
	 */
    public function test_oauth_google_client() {

        $received = $this->oauth;
        $this->assertIsObject($received);
    }


    
    /** 
	 * @test
     * 
     * @testdox Test the google authentication secrets file exists to use.
     * 
	 */
    public function test_google_secrets_file_exists() {

        /**
         * expected, received, asserted
         */
        $expected = true;

        $received = defined('GOOGLE_APPLICATION_CREDENTIALS');

        $this->assertEquals($expected, $received);


        
        /**
         * expected, received, asserted
         */
        $expected = true;

        $received = file_exists(GOOGLE_APPLICATION_CREDENTIALS);

        $this->assertEquals($expected, $received);
    }


    /** 
	 * @test
     * 
     * @testdox Test the YT_OAUTH_REFRESH_TOKEN constant exists
     * 
	 */
    public function test_transient_exists()
    {
        $this->refresh_token = YT_OAUTH_REFRESH_TOKEN;
        $got = $this->refresh_token;
        $this->assertIsString($got);
    }


    /** 
	 * @test
     * 
     * @testdox Test using YT_OAUTH_REFRESH_TOKEN to get a valid google client.
     * 
	 */
    public function test_use_refresh_token_to_get_access_token()
    {
        $this->oauth->set_scope("https://www.googleapis.com/auth/youtube.force-ssl");
        $this->oauth->set_refresh_token(YT_OAUTH_REFRESH_TOKEN);
        $this->oauth->run();
        $got = $this->oauth->get_client();
        $this->assertIsObject($got);
    }


    /** 
	 * @test
     * 
     * @testdox Test we can get a valid access token from the client.
     * 
	 */
    public function test_access_token_is_set()
    {
        $want = 'access_token';

        $this->oauth->set_scope("https://www.googleapis.com/auth/youtube.force-ssl");
        $this->oauth->set_refresh_token(YT_OAUTH_REFRESH_TOKEN);
        $this->oauth->run();
        $client = $this->oauth->get_client();
        $got = $client->getAccessToken();
        
        $this->assertArrayHasKey($want, $got);
    }


    /** 
	 * @test
     * 
     * @testdox Test runnning google client with no refresh token.
     * 
	 */
    public function test_run_with_no_refresh_token()
    {
        $want = null;

        $this->oauth->set_scope("https://www.googleapis.com/auth/youtube.force-ssl");
        $got = $this->oauth->run();
        
        $this->assertEquals($want, $got);
    }


    /** 
	 * @test
     * 
     * @testdox Test can get Access Token with YT_OAUTH_REFRESH_TOKEN transient set.
     * 
	 */
    public function test_run_with_transient()
    {
        set_transient('yt_oauth_refresh_token', YT_OAUTH_REFRESH_TOKEN);
        $this->oauth->set_token_name('yt_oauth_refresh_token');
        $this->oauth->set_scope("https://www.googleapis.com/auth/youtube.force-ssl");
        $this->oauth->run();
        $client = $this->oauth->get_client();
        
        $want = 'access_token';
        
        $got = $client->getAccessToken();
        
        $this->assertArrayHasKey($want, $got);
    }



    
}
