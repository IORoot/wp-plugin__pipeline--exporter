<?php

/**
 * Class exporterTest
 *
 * @package Andyp_universal_exporter
 */

/**
 * @testdox Testing the \ue\exporter class
 */
class exporterTest extends WP_UnitTestCase {

    /**
     * @before
     */
	public function setup()
    {
        parent::setUp();
    }

    public function test_exporter_class_exists() {

        $got = new \ex\exporter;

		$this->assertIsObject($got);
    }

}
