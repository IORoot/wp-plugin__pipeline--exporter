<?php

/**
 * Class guzzleTest
 *
 * @package Andyp_exporter
 */

/**
 * @testdox Testing the \ue\exporter class
 */
class guzzleTest extends WP_UnitTestCase {

    /**
     * @before
     */
	public function setup()
    {
        parent::setUp();

        $this->http = new GuzzleHttp\Client(['base_uri' => 'https://londonparkour.com/']);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }


    public function test_guzzle_connection() {

        $want = 200;

        $response = $this->http->request('GET', '/');
        $got = $response->getStatusCode();

        $this->assertEquals($want, $got);
    }

}
