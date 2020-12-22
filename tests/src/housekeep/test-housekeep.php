<?php

    
/**
 * Class combineTest
 *
 * @package Andyp_processor
 */

/**
 * @testdox Testing the \ex\housekeep class
 */
class housekeepTest extends WP_UnitTestCase
{



    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \andyp\housekeeper\housekeep;
    }

    public function tearDown()
    {
        $this->remove_added_uploads();
        parent::tearDown();
    }
    
    
    /**
     * Create a post, attachment and thumbnail.
     * 
     * int $number is the number of posts you want.
     */
    public $input;
    public function util_make_post_with_thumbnail(int $number = 1)
    {
        for ($i = 0; $i < $number; $i++) {
            $in['post']            = $this->factory->post->create_and_get();
            $in['attachment_id']   = $this->factory->attachment->create_upload_object( DIR_DATA . '/test_image.jpg', $in['post']->ID );
            $in['thumbnail_id']    = set_post_thumbnail( $in['post']->ID, $in['attachment_id'] );
            $in['attachment']      = get_attached_media( 'image', $in['post']->ID );
            $this->input[$i] = $in;
        }
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
        $options = [
            'enabled' => true,
            'action' => '',
            'query' => '',
        ];

        $return = $this->class_instance->set_options($options);

        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $return;

        $asserted = $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Test run() while disabled
     * 
	 */
	public function test_run_disabled() {

        /**
         * Setup - Options
         */
        $options = [
            'enabled' => false,
            'action' => '',
            'query' => '',
        ];
        $this->class_instance->set_options($options);


        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $this->class_instance->run();

        $asserted = $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Test run() while missing enabled
     * 
	 */
	public function test_run_missing_enabled() {

        /**
         * Setup - Options
         */
        $options = [
            'action' => '',
            'query' => '',
        ];
        $this->class_instance->set_options($options);


        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $this->class_instance->run();

        $asserted = $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Test run() while missing action
     * 
	 */
	public function test_run_missing_action() {

        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'query' => '',
        ];
        $this->class_instance->set_options($options);


        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $this->class_instance->run();

        $asserted = $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Test run() while missing query
     * 
	 */
	public function test_run_missing_query() {

        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => '',
        ];
        $this->class_instance->set_options($options);


        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $this->class_instance->run();

        $asserted = $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Test run() while missing Options
     * 
	 */
	public function test_run_missing_options() {

        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $this->class_instance->run();

        $asserted = $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Test run() with wrong action
     * 
	 */
	public function test_run_wrong_action() {

        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'wrong',
            'query' => '',
        ];
        $this->class_instance->set_options($options);


        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $this->class_instance->run();

        $asserted = $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Test run() with action - none
     * 
	 */
	public function test_run_with_action_none() {

        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'none',
            'query' => '',
        ];
        $this->class_instance->set_options($options);


        /**
         * Expected, Received, Asserted
         */
        $expected = null;

        $received = $this->class_instance->run();

        $asserted = $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Test run() with action - none. Get results.
     * 
	 */
	public function test_run_with_action_none_get_results() {

        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'none',
            'query' => '',
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         */
        $expected = true;

        $received = $this->class_instance->get_result();

        $asserted = $this->assertEquals($expected, $received );

    }



	/** 
	 * @test
     * 
     * @testdox Test run() with action - bin_all.
     * 
     * Bin all (place in trash) all posts in post_type => post.
     * 
	 */
	public function test_run_with_action_bin_all() {

        /**
         * Setup - Add post
         */
        $post = (array) $this->factory->post->create_and_get();

        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'bin_all',
            'query' => "[
                'post_type' => 'post',
            ]",
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         * 
         * run completed true.
         */
        $expected = true;

        $received = $this->class_instance->get_result();

        $asserted = $this->assertEquals($expected, $received );

        /**
         * Expected, Received, Asserted
         * 
         * 0 posts published
         */
        $expected = 0;

        $received = wp_count_posts( 'post' )->publish;

        $asserted = $this->assertEquals($expected, $received );

        /**
         * Expected, Received, Asserted
         * 
         * 1 in the trash
         */
        $expected = 1;

        $received = wp_count_posts( 'post' )->trash;

        $asserted = $this->assertEquals($expected, $received );


    }


    /** 
	 * @test
     * 
     * @testdox Test run() with action - bin_all (with images).
     * 
     * Bin all (place in trash) all posts & images.
     * 
	 */
	public function test_run_with_action_bin_all_with_images() {

        /**
         * Setup - Add post
         */
        $this->util_make_post_with_thumbnail();

        $attachment = wp_get_attachment_image($this->input[0]['attachment_id']);
        
        /**
         * Expected, Received, Asserted
         * 
         * Attachment exists.
         */
        $expected = true; 

        $received = strpos($attachment, 'http://example.org/wp-content/uploads/2020/12/test_image');

        $asserted = $this->assertEquals($expected, $received );




        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'bin_all',
            'query' => "[
                'post_type' => 'post',
            ]",
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         * 
         * Attachment binned.
         */
        $expected = "";

        $received = wp_get_attachment_image($this->input[0]['attachment_id']);

        $asserted = $this->assertEquals($expected, $received );

    }
    


    /** 
	 * @test
     * 
     * @testdox Test run() with action - bin_all with wrong querys
     * 
	 */
	public function test_run_bin_all_with_wrong_options() {

        /**
         * Setup - Add post
         */
        $this->util_make_post_with_thumbnail();
        $attachment = wp_get_attachment_image($this->input[0]['attachment_id']);


        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'bin_all',
            'query' => "wrong",
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         */
        $expected = false;

        $received = $this->class_instance->get_result();

        $asserted = $this->assertEquals($expected, $received );

    }




    /** 
	 * @test
     * 
     * @testdox Test run() with action - bin_posts.
     * 
     * Bin posts (place in trash) all posts (not images)
     * 
	 */
	public function test_run_bin_posts() {

        /**
         * Setup - Add post
         */
        $this->util_make_post_with_thumbnail();
        $attachment = wp_get_attachment_image($this->input[0]['attachment_id']);


        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'bin_posts',
            'query' => "[
                'post_type' => 'post',
            ]",
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();


        

        
        
        /**
         * Expected, Received, Asserted
         * 
         * Attachment is NOT binned.
         */
        $expected = true; 

        $received = strpos(wp_get_attachment_image($this->input[0]['attachment_id']), 'http://example.org/wp-content/uploads/2020/12/test_image');

        $asserted = $this->assertEquals($expected, $received );

        /**
         * Expected, Received, Asserted
         * 
         * But post HAS bin binned.
         */
        $expected = 1;

        $received = wp_count_posts( 'post' )->trash;

        $asserted = $this->assertEquals($expected, $received );

    }

    /** 
	 * @test
     * 
     * @testdox Test run() with action - bin_posts with wrong querys
     * 
	 */
	public function test_run_bin_posts_with_wrong_options() {

        /**
         * Setup - Add post
         */
        $this->util_make_post_with_thumbnail();
        $attachment = wp_get_attachment_image($this->input[0]['attachment_id']);


        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'bin_posts',
            'query' => "wrong",
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         */
        $expected = false;

        $received = $this->class_instance->get_result();

        $asserted = $this->assertEquals($expected, $received );

    }


    /** 
	 * @test
     * 
     * @testdox Test run() with action - delete_all.
     * 
     * Deletes posts and images permenantly.
     * 
	 */
	public function test_run_delete_all() {

        /**
         * Setup - Add post
         */
        $this->util_make_post_with_thumbnail();
        $attachment = wp_get_attachment_image($this->input[0]['attachment_id']);


        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'delete_all',
            'query' => "[
                'post_type' => 'post',
            ]",
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         * 
         * Attachment IS deleted.
         */
        $expected = '';

        $received = wp_get_attachment_image($this->input[0]['attachment_id']);

        $asserted = $this->assertEquals($expected, $received );

        /**
         * Expected, Received, Asserted
         * 
         * And Post HAS been deleted.
         */
        $expected = 0;

        $received = wp_count_posts( 'post' )->publish;

        $asserted = $this->assertEquals($expected, $received );

        /**
         * Expected, Received, Asserted
         * 
         * And Post is not in the trash.
         */
        $expected = 0;

        $received = wp_count_posts( 'post' )->trash;

        $asserted = $this->assertEquals($expected, $received );
    }


    /** 
	 * @test
     * 
     * @testdox Test run() with action - delete_all with wrong querys
     * 
	 */
	public function test_run_delete_all_with_wrong_options() {

        /**
         * Setup - Add post
         */
        $this->util_make_post_with_thumbnail();
        $attachment = wp_get_attachment_image($this->input[0]['attachment_id']);


        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'delete_all',
            'query' => "wrong",
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         */
        $expected = false;

        $received = $this->class_instance->get_result();

        $asserted = $this->assertEquals($expected, $received );

    }




    /** 
	 * @test
     * 
     * @testdox Test run() with action - delete_posts.
     * 
     * Deletes posts permenantly, but keep images.
     * 
	 */
	public function test_run_delete_posts() {

        /**
         * Setup - Add post
         */
        $this->util_make_post_with_thumbnail();
        $attachment = wp_get_attachment_image($this->input[0]['attachment_id']);


        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'delete_posts',
            'query' => "[
                'post_type' => 'post',
            ]",
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         * 
         * Attachment IS NOT deleted.
         */
        $expected = true; 

        $received = strpos(wp_get_attachment_image($this->input[0]['attachment_id']), 'http://example.org/wp-content/uploads/2020/12/test_image');

        $asserted = $this->assertEquals($expected, $received );

        

        /**
         * Expected, Received, Asserted
         * 
         * And Attachment still exists
         */
        $expected = 1;
        
        $received = wp_count_posts( 'attachment' )->inherit;

        $asserted = $this->assertEquals($expected, $received );


        /**
         * Expected, Received, Asserted
         * 
         * And Post HAS been deleted.
         */
        $expected = 0;

        $received = wp_count_posts( 'post' )->publish;

        $asserted = $this->assertEquals($expected, $received );

        /**
         * Expected, Received, Asserted
         * 
         * And Post is not in the trash.
         */
        $expected = 0;

        $received = wp_count_posts( 'post' )->trash;

        $asserted = $this->assertEquals($expected, $received );
    }



    /** 
	 * @test
     * 
     * @testdox Test run() with action - delete_posts with wrong querys
     * 
	 */
	public function test_run_delete_posts_with_wrong_options() {

        /**
         * Setup - Add post
         */
        $this->util_make_post_with_thumbnail();
        $attachment = wp_get_attachment_image($this->input[0]['attachment_id']);


        /**
         * Setup - Options
         */
        $options = [
            'enabled' => true,
            'action' => 'delete_posts',
            'query' => "wrong",
        ];
        $this->class_instance->set_options($options);

        $this->class_instance->run();

        /**
         * Expected, Received, Asserted
         */
        $expected = false;

        $received = $this->class_instance->get_result();

        $asserted = $this->assertEquals($expected, $received );

    }

}
