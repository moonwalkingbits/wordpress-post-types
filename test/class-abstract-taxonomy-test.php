<?php

namespace Moonwalking_Bits\Post_Types;

use PHPUnit\Framework\TestCase;

class Abstract_Taxonomy_Test extends TestCase {

	/**
	 * @test
	 */
	public function should_have_getters_for_all_properties(): void {
		$taxonomy = new Fixtures\Taxonomy();

		$this->assertEquals( 'taxonomy', $taxonomy->key() );
		$this->assertEmpty( $taxonomy->description() );
		$this->assertEmpty( $taxonomy->labels() );
		$this->assertFalse( $taxonomy->is_public() );
		$this->assertFalse( $taxonomy->is_hierarchical() );
		$this->assertFalse( $taxonomy->is_publicly_queryable() );
		$this->assertFalse( $taxonomy->is_showing_ui() );
		$this->assertFalse( $taxonomy->is_visible_in_nav_menus() );
		$this->assertFalse( $taxonomy->is_included_in_rest() );
		$this->assertEmpty( $taxonomy->default_term() );
		$this->assertFalse( $taxonomy->is_sorted() );
		$this->assertFalse( $taxonomy->is_showing_in_menu() );
		$this->assertEmpty( $taxonomy->rest_base_path() );
		$this->assertEmpty( $taxonomy->rest_namespace_path() );
		$this->assertEmpty( $taxonomy->rest_controller_class_name() );
		$this->assertFalse( $taxonomy->is_showing_tag_cloud() );
		$this->assertFalse( $taxonomy->is_showing_in_quick_edit() );
		$this->assertFalse( $taxonomy->is_showing_admin_column() );
		$this->assertEmpty( $taxonomy->capabilities() );
		$this->assertTrue( $taxonomy->rewrite() );
		$this->assertTrue( $taxonomy->query_parameter_name() );
	}
}
