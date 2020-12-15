<?php

/**
 * Class contentTest
 *
 * @package Andyp_pipeline_universal_exporter
 */

/**
 * @testdox Testing the \ex\content class
 */
class contentTest extends WP_UnitTestCase {
    


    public function setUp()
    {
        // before
        parent::setUp();
        $this->class_instance = new \ex\content;

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
	public function test_content_class_exists() {
		$this->assertIsObject($this->class_instance);
    }


	/** 
	 * @test
     * 
     * Just for code coverage, run the set_collection()
     * method, but this class doesn't use it.
     * 
     * @testdox Testing the set_collection() method
     * 
	 */
	public function test_set_collection() {

        $input = "collection";

        $return = $this->class_instance->set_collection($input);

        $expected = null;

        $received = $return;

        $this->assertEquals($expected, $received );

    }


	/** 
	 * @test
     * 
     * This will insert a mock post and attachment, then use the
     * content query class to retrieve that post.
     * 
     * @testdox Testing the ue\content\query class
     * 
	 */
	public function test_run_content_query() {

        $options = [
            'ex_job_content_id' => [
                'ex_content_group' => [
                    'ex_content_enabled' => true,
                    'ex_content_id' => 'phpunit_test_run_query',
                ],
                'ex_content_input' => 'query',
                'ex_content_query' => "[
                        'numberposts' => 1,
                    ]",
                
            ],
        ];

        $att_id = $this->factory->attachment->create_object([
            'file'        => '/var/www/vhosts/dev.londonparkour.com/wp-content/uploads/2018/03/Fizz-100x50.jpg',
            'meta_input' => [
                '_wp_attachment_metadata' => [''],
            ]
        ]);

        $posts = $this->factory->post->create_many(1, [
            'meta_input' => [
                '_thumbnail_id' => $att_id,
            ]
        ]);

        $this->class_instance->set_options($options);

        $results = $this->class_instance->run();

        $expected = $posts[0];

        $got = $results[0]['ID'];

        $this->assertEquals($expected, $got );
    }




	/** 
	 * @test
     * 
     * This will insert a mock post and metadata, then use the
     * content post class to retrieve that post.
     * 
     * @testdox Testing the ue\content\posts class
     * 
	 */
	public function test_run_content_post() {


        $post_object1 = $this->factory->post->create_and_get([
            'meta_input' => [
                'test_meta' => 'meta value 1',
            ]
        ]);

        $post_object2 = $this->factory->post->create_and_get([
            'meta_input' => [
                'test_meta' => 'meta value 2',
            ]
        ]);

        $options = [
            'ex_job_content_id' => [
                'ex_content_group' => [
                    'ex_content_enabled' => true,
                    'ex_content_id' => 'phpunit_test_run_query',
                ],
                'ex_content_input' => 'posts',
                'ex_content_posts' => [$post_object1, $post_object2],
                
            ],
        ];

        $this->class_instance->set_options($options);

        $results = $this->class_instance->run();

        $expected = 'meta value 2';

        $got = $results[1]['test_meta'][0];

        $this->assertEquals($expected, $got );

    }


}
