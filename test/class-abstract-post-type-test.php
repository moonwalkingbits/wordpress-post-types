<?php

namespace Moonwalking_Bits\Post_Types;

use PHPUnit\Framework\TestCase;

class Abstract_Post_Type_Test extends TestCase {

	/**
	 * @test
	 */
	public function should_have_getters_for_all_properties(): void {
		$post_type = new Fixtures\Post_Type();

		$this->assertEquals( 'post-type', $post_type->key() );
		$this->assertEmpty( $post_type->description() );
		$this->assertEmpty( $post_type->labels() );
		$this->assertFalse( $post_type->is_public() );
		$this->assertFalse( $post_type->is_hierarchical() );
		$this->assertFalse( $post_type->is_included_in_search() );
		$this->assertFalse( $post_type->is_publicly_queryable() );
		$this->assertFalse( $post_type->is_showing_ui() );
		$this->assertFalse( $post_type->is_visible_in_nav_menus() );
		$this->assertFalse( $post_type->is_visible_in_admin_bar() );
		$this->assertFalse( $post_type->is_included_in_rest() );
		$this->assertFalse( $post_type->is_using_default_meta_capability_handling() );
		$this->assertFalse( $post_type->is_exported() );
		$this->assertNull( $post_type->delete_with_user() );
		$this->assertFalse( $post_type->archive() );
		$this->assertFalse( $post_type->menu_location() );
		$this->assertFalse( $post_type->rest_base_path() );
		$this->assertFalse( $post_type->rest_controller_class_name() );
		$this->assertNull( $post_type->menu_position() );
		$this->assertEquals( 'none', $post_type->menu_icon() );
		$this->assertEquals( array( 'post', 'posts' ), $post_type->capability_nouns() );
		$this->assertEmpty( $post_type->capabilities() );
		$this->assertEquals( array( 'title', 'editor' ), $post_type->features() );
		$this->assertEmpty( $post_type->taxonomies() );
		$this->assertTrue( $post_type->rewrites() );
		$this->assertTrue( $post_type->query_parameter_name() );
		$this->assertEmpty( $post_type->template_blocks() );
		$this->assertFalse( $post_type->template_lock_strategy() );
		$this->assertEmpty( $post_type->meta_boxes()->getIterator() );
	}
}
