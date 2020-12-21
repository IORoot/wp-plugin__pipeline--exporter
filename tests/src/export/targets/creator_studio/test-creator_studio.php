<?php


/**
 * @testdox Testing the \ex_creator_studio class
 */
class exCreatorStudioTest extends WP_UnitTestCase
{



    public $class_instance;




    public function setUp()
    {
        parent::setUp();    
        $this->class_instance = new ex_creator_studio;
    }

    public function tearDown()
    {
        $this->remove_added_uploads();
        parent::tearDown();
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
     * Mock options.
     * 
     * Note that the NOOP param is set to NOT publish at the
     *
     * @return array
     */
    public static function mock_options()
    {
        return [
            'acf_fc_layout' => 'creator_studio',
            'post_types_creator_studio' =>
                [
                    [
                        'acf_fc_layout'       => 'instagram',
                        'enabled'             => true,
                        'post_caption'        => 'PHPUnit test',
                        'location'            => 'London',
                        'content_url'         => 'https://londonparkour.com/wp-content/uploads/2020/10/output.mp4',
                        'cover_image_url'     => 'https://londonparkour.com/wp-content/uploads/2020/06/Free-1.jpg',
                        'schedule_or_publish' => 'schedule',
                        'schedule'            => '+3 hours +30 minutes',
                        'specific'            => null,
                        'crosspost'           => true,
                        'noop'                => false,
                        'screenshots'         => true,
                        'clear_cookies'       => false,
                        'cookie_filename'     => "cookies.json",
                        'video_filename'      => "output.mp4",
                        'image_filename'      => "image.jpg",
                    ],
                ],
            'auth' =>
                [
                    [
                        'acf_fc_layout'       => 'igs',
                        'username'            => CREATOR_STUDIO_USERNAME,
                        'password'            => CREATOR_STUDIO_PASSWORD,
                        'igs_api_key'         => CREATOR_STUDIO_IGS_API_KEY,
                        'ip_address'          => CREATOR_STUDIO_IGS_IP,
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
    public function no_test_run()
    {

        /**
         * Setup - Options
         */
        $options = $this::mock_options();

        $return = $this->class_instance->set_options($options);


        /**
         * Setup - data
         */
        $this->util_make_post();
        
        $this->class_instance->set_data($this->input);


        /**
         * Run
         */
        $this->class_instance->run();

        $results = $this->class_instance->get_results();

        /**
         * Expected, Received, Asserted
         */
        $expected = 'success';

        $received = $results['status'];

        $asserted = $this->assertEquals($expected, $received);
    }

    
}