<?php

namespace Moonwalking_Bits\Post_Types;

use PHPUnit\Framework\TestCase;

class Abstract_Meta_Box_Test extends TestCase {

	/**
	 * @test
	 */
	public function should_have_getters_for_all_properties(): void {
		$meta_box = new Fixtures\Meta_Box();

		$this->assertEquals( 'meta-box', $meta_box->id() );
		$this->assertEquals( 'Title', $meta_box->title() );
		$this->assertEquals( 'advanced', $meta_box->context() );
		$this->assertEquals( 'default', $meta_box->priority() );
	}
}
