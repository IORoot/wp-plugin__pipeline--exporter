<?php


/**
 * @testdox Testing the \ex_google_my_business class
 */
class exGMBTest extends WP_UnitTestCase
{



    public $class_instance;




    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new ex_google_my_business;
    }

    public function tearDown()
    {
        $this->remove_added_uploads();
        parent::tearDown();
    }



    public static function mock_gmb_CTA()
    {
        return [
            'post_types_google_my_business' =>
                [
                    [
                        'acf_fc_layout' => 'call_to_action',
                        'enabled' => '1',
                        'locationid' => 'accounts/106324301700393434193/locations/13389130540797665003',
                        'summary' => 'GMB Test CTA Export',
                        'settings' => [
                                'action_type' => 'LEARN_MORE',
                                'url' => 'https://londonparkour.com/',
                                'media_type' => 'PHOTO',
                                'media_source_url' => 'https://londonparkour.com/wp-content/uploads/2020/06/Outdoors-1.jpg',
                                'media_category' => 'ADDITIONAL',
                            ]
                    ]
                ]
        ];
    }


    public static function mock_gmb_event()
    {
        return [
            'post_types_google_my_business' =>
                [
                    [
                        'acf_fc_layout' => 'events',
                        'enabled' => '1',
                        'locationid' => 'accounts/106324301700393434193/locations/13389130540797665003',
                        'summary' => 'GMB Test Event Export',
                        'settings' => [
                                'title' => '{{0_post_title}}',
                                'start_datetime' => '2020, 12, 30, 7, 24, 00',
                                'end_datetime' => '2020, 12, 31, 7, 24, 00',
                                'media_type' => 'PHOTO',
                                'media_source_url' => 'https://londonparkour.com/wp-content/uploads/2020/06/Outdoors-1.jpg',
                                'media_category' => 'ADDITIONAL',
                                'button_action_type' => 'LEARN_MORE',
                                'button_url' => 'https://londonparkour.com',
                            ]
                    ]
                ]
        ];
    }


    /**
     * Create a post, attachment and thumbnail.
     * 
     * int $number is the number of post you want.
     */
    public $input;
    public function util_make_post(int $number = 1)
    {
        for ($i = 0; $i < $number; $i++) {
            $in = (array) $this->factory->post->create_and_get();
            add_post_meta($in['ID'], 'videoId',     DIR_DATA.'/test_video.mp4');
            add_post_meta($in['ID'], 'thumbnailId', 'https://londonparkour.com/wp-content/uploads/2020/06/Outdoors-1.jpg'); // Needs to be a REAL accessible image.
            $this->input[$i] = array_merge($in, get_post_meta($in['ID']));
        }
    }

    /**
     * Cleanup function
     * Pass in the GMB Returned object
     */
    private function cleanup()
    {
        $this->remove_gmb_last_media();
        $this->remove_gmb_last_post();
    }


    /**
     * remove_gmb_item function
     * 
     * Cleanup any uploaded image.
     *
     * @return void
     */
    private function remove_gmb_last_media()
    {
        $options = $this::mock_gmb_CTA();

        $gmb = new \ex\exporter\gmb\delete_media;
        $gmb->set_client($this->class_instance->get_client());
        $gmb->set_sourceURL($options['post_types_google_my_business'][0]['settings']['media_source_url']);
        $gmb->run(); 
        $result = $gmb->get_results();
    }


    /**
     * remove_gmb_post function
     * 
     * Cleanup any uploaded post.
     *
     * @return void
     */
    private function remove_gmb_last_post()
    {
        $options = $this::mock_gmb_CTA();

        $gmb = new \ex\exporter\gmb\delete_post;
        $gmb->set_client($this->class_instance->get_client());
        $gmb->set_summary($options['post_types_google_my_business'][0]['summary']);
        $gmb->run(); 
        $result = $gmb->get_results();
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
     * @testdox Testing the run() method with no refresh token transient set
     *
     */
    public function test_run_event_missing_refresh_token()
    {

        /**
         * Expected, Received, Asserted
         */
        $expected = False;

        $received = $this->class_instance->run();

        $asserted = $this->assertEquals($expected, $received);


        /**
         * Expected, Received, Asserted
         */
        $expected = 'GMB_OAUTH_REFRESH_TOKEN Transient not set.';

        $received = $this->class_instance->get_error();

        $asserted = $this->assertEquals($expected, $received);

    }


    /**
     * @test
     *
     * @testdox Testing the run() method with an EVENT disabled
     *
     */
    public function test_run_event_while_disabled()
    {

        /**
         * Setup - Transient
         */
        set_transient('GMB_OAUTH_REFRESH_TOKEN', GMB_OAUTH_REFRESH_TOKEN);

        
        /**
         * Setup - Options
         */
        $options = $this::mock_gmb_event();
        $options['post_types_google_my_business'][0]['enabled'] = FALSE;
        $this->class_instance->set_options($options);
        $this->class_instance->run();

        /**
         * Run
         */
        $result = $this->class_instance->get_results();

        /**
         * Expected, Received, Asserted
         */
        $expected = [ 0 => null ];

        $received = $result;

        $asserted = $this->assertEquals($expected, $received);

    }


    /**
     * @test
     *
     * @testdox Testing the run() method with an CTA disabled
     *
     */
    public function test_run_CTA_while_disabled()
    {

        /**
         * Setup - Transient
         */
        set_transient('GMB_OAUTH_REFRESH_TOKEN', GMB_OAUTH_REFRESH_TOKEN);

        
        /**
         * Setup - Options
         */
        $options = $this::mock_gmb_CTA();
        $options['post_types_google_my_business'][0]['enabled'] = FALSE;
        $this->class_instance->set_options($options);
        $this->class_instance->run();

        /**
         * Run
         */
        $result = $this->class_instance->get_results();

        /**
         * Expected, Received, Asserted
         */
        $expected = [ 0 => null ];

        $received = $result;

        $asserted = $this->assertEquals($expected, $received);

    }


    /**
     * @test
     *
     * @testdox Testing the run() method with a CTA
     *
     */
    public function test_run_with_call_to_action()
    {

        /**
         * Setup - Transient
         */
        set_transient('GMB_OAUTH_REFRESH_TOKEN', GMB_OAUTH_REFRESH_TOKEN);

        
        /**
         * Setup - Options
         */
        $return = $this->class_instance->set_options($this::mock_gmb_CTA());


        /**
         * Setup - Data
         */
        $this->util_make_post();


        /**
         * Run
         */
        $this->class_instance->set_data($this->input); // needs to be array of post.
        $this->class_instance->run();
        $this->results = $this->class_instance->get_results();
        $result = $this->results[0];

        /**
         * Expected, Received, Asserted
         */
        $expected = 'object';

        $received = gettype($result);

        $asserted = $this->assertEquals($expected, $received);


        /**
         * Expected, Received, Asserted
         */
        $expected = 'LIVE';

        $received = $result->getState();

        $asserted = $this->assertEquals($expected, $received);

        /**
         * Cleanup and remove image / post uploaded.
         */
        $this->cleanup();
    }



    /**
     * @test
     *
     * @testdox Testing the run() method with an EVENT
     *
     */
    public function test_run_with_event()
    {

        /**
         * Setup - Transient
         */
        set_transient('GMB_OAUTH_REFRESH_TOKEN', GMB_OAUTH_REFRESH_TOKEN);

        
        /**
         * Setup - Options
         */
        $return = $this->class_instance->set_options($this::mock_gmb_event());


        /**
         * Setup - Data
         */
        $this->util_make_post();


        /**
         * Run
         */
        $this->class_instance->set_data($this->input); // needs to be array of post.
        $this->class_instance->run();
        $this->results = $this->class_instance->get_results();
        $result = $this->results[0];

        /**
         * Expected, Received, Asserted
         */
        $expected = 'object';

        $received = gettype($result);

        $asserted = $this->assertEquals($expected, $received);


        /**
         * Expected, Received, Asserted
         */
        $expected = 'LIVE';

        $received = $result->getState();

        $asserted = $this->assertEquals($expected, $received);

        /**
         * Cleanup and remove image / post uploaded.
         */
        $this->cleanup();
    }













    
}
