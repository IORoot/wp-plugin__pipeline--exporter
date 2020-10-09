<?php

/**
 * Class wordpressTest
 *
 * @package Andyp_exporter
 */

/**
 * @testdox Testing the \ue\exporter class
 */
class wordpressTest extends WP_UnitTestCase {

    /**
     * @before
     */
	public function setup()
    {
        parent::setUp();

        $this->testPostId = wp_insert_post([
            'post_title' => 'Sample Post',
            'post_content' => 'This is just some sample post content.'
        ]);
    }

    public function tearDown(): void
    {
        wp_delete_post($this->testPostId, true);
    }


    public function test_wordpress_functions() {

        $want = 'This is just some sample post content.';

        $got = get_the_content(null, false, $this->testPostId );

        $this->assertEquals($got, $want);

        
    }

}
