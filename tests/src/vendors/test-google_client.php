<?php

/**
 * Class Google_Client_Test
 *
 * This tests the client library and that the google
 * API can be called.
 */

/**
 * @testdox Testing the oauth_google_client class
 */
class Google_Client_Test extends PHPUnit_Framework_TestCase {

    /**
     * @before
     */
	public function setup()
    {
        parent::setUp();

        $this->class_instance = new Google_Client();

    }

    public function tearDown(): void
    {
        $this->class_instance = null;
    }


    /** 
	 * @test
     * 
     * @testdox Test the /vendor/google/client class exists and can be called
     * 
	 */
    public function test_google_client() {

        $received = $this->class_instance;
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
     * @testdox Can the youtube.force-ssl SCOPE be set?
     * 
	 */
    public function test_google_client_scope() 
    {

        /**
         * Setup - scope
         */
        $scope = "https://www.googleapis.com/auth/youtube.force-ssl";

        $this->class_instance->addScope($scope);

        /**
         * expected, received, asserted
         */
        $expected = [$scope];

        $received = $this->class_instance->getScopes();

        $this->assertEquals($expected, $received);
    }



    
    
}
