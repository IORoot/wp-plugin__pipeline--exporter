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
     * Mock options.
     * 
     * Note that the NOOP param is set to NOT publish
     *
     * @return array
     */
    public static function mock_options()
    {
        return [
            'acf_fc_layout' => 'trello',
            'post_types_trello' =>
                [
                    [
                        'acf_fc_layout'       => 'add_card',
                        'location'            => [
                            'enabled' => true,
                            'board' => "5a8d3c2a5b7deee57e2efe2b",
                            'list' => "5c95efda4da9724812f428a7",
                        ],
                        'details' => [
                            'name' => 'PHPUNIT Trello Test',
                            'description' => 'PHPUNIT Trello Description',
                            'due_date' => "2020-11-26 00:00:00",
                            'labels' => [ "5a8d3c2b5b7deee57e2efe64" ],
                            'source_url' => "https://londonparkour.com/wp-content/uploads/2020/06/Youth-2.jpg",
                            'custom_fields' => false,
                        ]
                    ],
                ],
            'auth' =>
                [
                    [
                        'acf_fc_layout'       => 'trello',
                        'trello_api_key'      => TRELLO_API_KEY,
                        'trello_token'        => TRELLO_TOKEN,
                    ]
                ]
        ];
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
         * Setup - Options
         */
        $options = $this::mock_options();
        $this->class_instance->set_options($options);


        /**
         * Setup - Data
         */
        $data = $this->factory->post->create_and_get();
        $return = $this->class_instance->set_data($data);

        /**
         * Run
         */
        $result = $this->class_instance->run();
        $result = $result[0];

        /**
         * Expected, Received, Asserteds
         */
        $expected = "PHPUNIT Trello Description";

        $received = $result->desc;

        $asserted = $this->assertEquals($expected, $received);
    }


    
}