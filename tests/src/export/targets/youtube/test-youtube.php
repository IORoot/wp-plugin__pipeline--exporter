<?php

    
/**
 * Class ex_youtube
 *
 * @package Andyp_processor
 */

/**
 * @testdox Testing the \ex_youtube class
 */
class ex_youtubeTest extends WP_UnitTestCase
{



    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new ex_youtube;
    }

    public function tearDown()
    {
        $this->remove_added_uploads();
        parent::tearDown();
    }




    /**
     * @test
     *
     * @testdox Testing class exists and returns an object.
     *
     */
    public function test_process_class_exists()
    {
        $this->assertIsObject($this->class_instance);
    }



	/** 
	 * @test
     * 
     * @testdox Testing the set_options() method
     * 
	 */
	public function test_set_options() {

        /**
         * Setup - Options
         */
        $options = [];

        $return = $this->class_instance->set_options($options);

        
        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $return;

        $asserted = $this->assertEquals($expected, $received);

    }



	/** 
	 * @test
     * 
     * @testdox Testing the set_data() method
     * 
	 */
	public function test_set_data() {

        /**
         * Setup - Data
         */
        $options = [];

        $return = $this->class_instance->set_data($options);


        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $return;

        $asserted = $this->assertEquals($expected, $received);

    }



	/** 
	 * @test
     * 
     * @testdox Testing the run() method with no token
     * 
	 */
	public function run_with_no_token() {

        $return = $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $return;

        $asserted = $this->assertEquals($expected, $received);

        /**
         * Expected, Received, Asserted
         */
        $expected = 'Error: No transient YT_OAUTH_REFRESH_TOKEN found.';

        $received = $this->class_instance->get_error();

        $asserted = $this->assertEquals($expected, $received);

    }



	/** 
	 * @test
     * 
     * @testdox Testing the run() method with transient token
     * 
	 */
	public function run_with_transient_token() {

        /**
         * Setup - Transient
         */
        set_transient('YT_OAUTH_REFRESH_TOKEN', YT_OAUTH_REFRESH_TOKEN);

        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $return;

        $asserted = $this->assertEquals($expected, $received);


        /**
         * Expected, Received, Asserted
         */
        $expected = 'Warn: No YouTube export instances found.';

        $received = $this->class_instance->get_error();

        $asserted = $this->assertEquals($expected, $received);

    }



	/** 
	 * @test
     * 
     * @testdox Testing the run() method with options set
     * 
	 */
	public function run_with_options_set() {

        /**
         * Setup - Transient
         */
        set_transient('YT_OAUTH_REFRESH_TOKEN', YT_OAUTH_REFRESH_TOKEN);

        /**
         * Setup - Options
         */
        $options = [
            'post_types_youtube' => ''
        ];
        $this->class_instance->set_options($options);




        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $return;

        $asserted = $this->assertEquals($expected, $received);


        /**
         * Expected, Received, Asserted
         */
        $expected = 'Warn: No YouTube export instances found.';

        $received = $this->class_instance->get_error();

        $asserted = $this->assertEquals($expected, $received);

    }
}
