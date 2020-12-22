<?php

        
/**
 * Class combineTest
 *
 * @package Andyp_processor
 */

/**
 * @testdox Testing the \andyp\scheduler\scheduler class
 */
class schedulerTest extends WP_UnitTestCase
{



    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \andyp\scheduler\schedule;
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
     * s
	 */
	public function test_set_options() {

        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'run_this_hook',
            'params'  => []
        ];

        $return = $this->class_instance->set_options($options);

        /**
         * Expected, Received, Asserted
         */
        
        $expected = null;

        $received = $return;

        $this->assertEquals($expected, $received );

    }

	/** 
	 * @test
     * 
     * @testdox Testing the run() method with missing options
     * s
	 */
	public function test_run_missing_options() {


        /**
         * Missing Options
         */
        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'run_this_hook',
            'params'  => []
        ];
        //$this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = false;
        $received = $return;
        $this->assertEquals($expected, $received );



        /**
         * Missing enabled
         */

        $options = [
            // 'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'run_this_hook',
            'params'  => []
        ];
        $this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = false;
        $received = $return;
        $this->assertEquals($expected, $received );



        /**
         * Missing label
         */

        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'run_this_hook',
            'params'  => []
        ];
        $this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = false;
        $received = $return;
        $this->assertEquals($expected, $received );



        /**
         * Missing repeats
         */

        $options = [
            'enabled' => true,
            // 'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'run_this_hook',
            'params'  => []
        ];
        $this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = false;
        $received = $return;
        $this->assertEquals($expected, $received );



        /**
         * Missing starts
         */

        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            // 'starts'  => 1893456000,
            'hook'    => 'run_this_hook',
            'params'  => []
        ];
        $this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = false;
        $received = $return;
        $this->assertEquals($expected, $received );



        /**
         * Starts not an int
         */

        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => '1893456000',
            'hook'    => 'run_this_hook',
            'params'  => []
        ];
        $this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = false;
        $received = $return;
        $this->assertEquals($expected, $received );



        /**
         * Missing hook
           */

        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            // 'hook'    => 'run_this_hook',
            'params'  => []
        ];
        $this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = false;
        $received = $return;
        $this->assertEquals($expected, $received );



        /**
         * Missing params
         */

        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'run_this_hook',
            // 'params'  => []
        ];
        $this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = false;
        $received = $return;
        $this->assertEquals($expected, $received );



        /**
         *  params not array
         */

        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'run_this_hook',
            'params'  => ''
        ];
        $this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = false;
        $received = $return;
        $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Testing the run() method with correct details.
     * s
	 */
	public function test_run() {

        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'pipeline_exporter',
            'params'  => [
                'job_id' => 'Testing for phpunit'
            ]
        ];
        $this->class_instance->set_options($options);
        $return = $this->class_instance->run();


        /**
         * Expected, Received, Asserted
         */
        $expected = true;
        $received = $return;
        $this->assertEquals($expected, $received );
    }


	/** 
	 * @test
     * 
     * @testdox Testing the get_existing_event() method.
     * s
	 */
	public function test_get_event() {

        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'pipeline_exporter',
            'params'  => [
                'job_id' => 'Testing for phpunit'
            ]
        ];
        $this->class_instance->set_options($options);
        $this->class_instance->run();
        $return = $this->class_instance->get_event();

        /**
         * Expected, Received, Asserted
         */
        $expected = [ 'next_scheduled' => 1893456000 ];
        $received = $return;
        $this->assertEquals($expected, $received );
    }


	/** 
	 * @test
     * 
     * @testdox Testing the remove_event() method.
     * s
	 */
	public function test_remove_event() {

        /**
         * Setup - Add new event
         */
        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'pipeline_exporter',
            'params'  => [
                'job_id' => 'Testing for phpunit'
            ]
        ];
        $this->class_instance->set_options($options);
        $this->class_instance->run();
        $before = $this->class_instance->get_event();

        /**
         * Expected, Received, Asserted
         */
        $expected = [ 'next_scheduled' => 1893456000 ];
        $received = $before;
        $this->assertEquals($expected, $received );


        /**
         * Setup - now delete the event
         */
        $options = [
            'hook'    => 'pipeline_exporter',
            'params'  => [
                'job_id' => 'Testing for phpunit'
            ]
        ];
        $this->class_instance->set_options($options);
        $this->class_instance->remove_event();
        $after = $this->class_instance->get_event();

        /**
         * Expected, Received, Asserted
         */
        $expected = [];
        $received = $after;
        $this->assertEquals($expected, $received );
    }



	/** 
	 * @test
     * 
     * @testdox Test updating the starttime.
     * s
	 */
	public function test_update_starttime() {

        /**
         * Setup - Add new event
         */
        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'pipeline_exporter',
            'params'  => [
                'job_id' => 'Testing for phpunit'
            ]
        ];
        $this->class_instance->set_options($options);
        $this->class_instance->run();
        $before = $this->class_instance->get_event();

        /**
         * Expected, Received, Asserted
         */
        $expected = [ 'next_scheduled' => 1893456000 ];

        $received = $before;

        $this->assertEquals($expected, $received );


        /**
         * Setup - Add new updated event
         */
        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456001,
            'hook'    => 'pipeline_exporter',
            'params'  => [
                'job_id' => 'Testing for phpunit'
            ]
        ];
        $this->class_instance->set_options($options);
        $this->class_instance->run();
        $after = $this->class_instance->get_event();

        /**
         * Expected, Received, Asserted
         */
        $expected = [ 'next_scheduled' => 1893456001 ];

        $received = $after;

        $this->assertEquals($expected, $received );
    }


	/** 
	 * @test
     * 
     * @testdox Test updating the starttime with same starttime.
     * s
	 */
	public function test_update_starttime_with_duplicate_time() {

        /**
         * Setup - Add new event
         */
        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'pipeline_exporter',
            'params'  => [
                'job_id' => 'Testing for phpunit'
            ]
        ];
        $this->class_instance->set_options($options);
        $this->class_instance->run();
        $before = $this->class_instance->get_event();

        /**
         * Expected, Received, Asserted
         */
        $expected = [ 'next_scheduled' => 1893456000 ];

        $received = $before;

        $this->assertEquals($expected, $received );


        /**
         * Setup - Add new updated event
         */
        $options = [
            'enabled' => true,
            'repeats' => '1hour',
            'starts'  => 1893456000,
            'hook'    => 'pipeline_exporter',
            'params'  => [
                'job_id' => 'Testing for phpunit'
            ]
        ];
        $this->class_instance->set_options($options);
        $this->class_instance->run();
        $after = $this->class_instance->get_event();

        /**
         * Expected, Received, Asserted
         */
        $expected = [ 'next_scheduled' => 1893456000 ];

        $received = $after;

        $this->assertEquals($expected, $received );
    }


}
