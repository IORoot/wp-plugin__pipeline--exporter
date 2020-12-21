<?php


/**
 * @testdox Testing the \ex_trello class
 */
class exTrelloTest extends WP_UnitTestCase
{



    public $class_instance;




    public function setUp()
    {
        parent::setUp();    
        $this->class_instance = new ex_trello;
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
    public function test_set_options()
    {

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
    public function test_set_data()
    {

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
     * @testdox Testing the run() method
     *
     */
    public function test_run()
    {

        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $this->class_instance->run();

        $asserted = $this->assertEquals($expected, $received);
    }


    
}