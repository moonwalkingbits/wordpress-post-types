<?php

namespace Moonwalking_Bits\Post_Types;

use ArrayIterator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use WP_Post;
use phpmock\phpunit\PHPMock;

class Post_Type_Registry_Test extends TestCase {

	use PHPMock;

	private Post_Type_Registry $registry;
	private MockObject $post_type_mock;
	private MockObject $meta_box_mock;
	private MockObject $meta_box_collection_mock;
	private MockObject $add_action_mock;
	private MockObject $register_post_type_mock;
	private MockObject $remove_post_type_support_mock;
	private MockObject $add_meta_box_mock;

	/**
	 * @before
	 */
	public function set_up(): void {
		$this->registry = new Post_Type_Registry();
		$this->post_type_mock = $this->getMockBuilder( Abstract_Post_Type::class )->getMock();
		$this->meta_box_mock = $this->getMockBuilder( Abstract_Meta_Box::class )->getMock();
		$this->meta_box_collection_mock = $this->getMockBuilder( Meta_Box_Collection::class )->getMock();
		$this->add_action_mock = $this->getFunctionMock( __NAMESPACE__, 'add_action' );
		$this->register_post_type_mock = $this->getFunctionMock( __NAMESPACE__, 'register_post_type' );
		$this->remove_post_type_support_mock = $this->getFunctionMock( __NAMESPACE__, 'remove_post_type_support' );
		$this->add_meta_box_mock = $this->getFunctionMock( __NAMESPACE__, 'add_meta_box' );

		$this->post_type_mock->method( 'key' )->will( $this->returnValue( 'custom' ) );
		$this->meta_box_collection_mock->method( 'getIterator' )
			->will( $this->returnValue( new ArrayIterator( array( $this->meta_box_mock ) ) ) );

		$this->meta_box_mock->method( 'id' )->will( $this->returnValue( 'id' ) );
		$this->meta_box_mock->method( 'title' )->will( $this->returnValue( 'title' ) );
		$this->meta_box_mock->method( 'render' )->will( $this->returnValue( 'content' ) );
		$this->meta_box_mock->method( 'context' )->will( $this->returnValue( 'context' ) );
		$this->meta_box_mock->method( 'priority' )->will( $this->returnValue( 'priority' ) );
	}

	/**
	 * @test
	 */
	public function should_be_empty_by_default(): void {
		$this->assertEmpty( $this->registry->all() );
	}

	/**
	 * @test
	 */
	public function should_use_init_hook_to_register_post_type(): void {
		$this->add_action_mock->expects( $this->once() )
			->with( 'init', $this->isType( 'callable' ) );
		$this->register_post_type_mock->expects( $this->never() );

		$this->registry->register( $this->post_type_mock );
	}

	/**
	 * @test
	 */
	public function should_register_post_type(): void {
		$this->add_action_mock->expects( $this->once() )
			->with( 'init', $this->isType( 'callable' ) )
			->will( $this->returnCallback( fn( string $action, $callable ) => $callable() ) );
		$this->register_post_type_mock->expects( $this->once() )
			->with(
				'custom',
				$this->logicalAnd(
					$this->arrayHasKey( 'labels' ),
					$this->arrayHasKey( 'description' ),
					$this->arrayHasKey( 'public' ),
					$this->arrayHasKey( 'hierarchical' ),
					$this->arrayHasKey( 'exclude_from_search' ),
					$this->arrayHasKey( 'publicly_queryable' ),
					$this->arrayHasKey( 'show_ui' ),
					$this->arrayHasKey( 'show_in_menu' ),
					$this->arrayHasKey( 'show_in_nav_menus' ),
					$this->arrayHasKey( 'show_in_admin_bar' ),
					$this->arrayHasKey( 'show_in_rest' ),
					$this->arrayHasKey( 'rest_base' ),
					$this->arrayHasKey( 'rest_controller_class' ),
					$this->arrayHasKey( 'menu_position' ),
					$this->arrayHasKey( 'menu_icon' ),
					$this->arrayHasKey( 'capability_type' ),
					$this->arrayHasKey( 'capabilities' ),
					$this->arrayHasKey( 'map_meta_cap' ),
					$this->arrayHasKey( 'supports' ),
					$this->arrayHasKey( 'register_meta_box_cb' ),
					$this->arrayHasKey( 'taxonomies' ),
					$this->arrayHasKey( 'has_archive' ),
					$this->arrayHasKey( 'rewrite' ),
					$this->arrayHasKey( 'query_var' ),
					$this->arrayHasKey( 'can_export' ),
					$this->arrayHasKey( 'delete_with_user' ),
					$this->arrayHasKey( 'template' ),
					$this->arrayHasKey( 'template_lock' )
				)
			);
		$this->remove_post_type_support_mock->expects( $this->exactly( 2 ) )
			->withConsecutive(
				array( 'custom', 'page-attributes' ),
				array( 'custom', 'post-formats' ),
			);
		$this->post_type_mock->method( 'features' )
			->will(
				$this->returnValue(
					array(
						'title',
						'editor',
						'excerpt',
						'author',
						'trackbacks',
						'thumbnail',
						'custom-fields',
						'comments',
						'revisions',
					)
				)
			);

		$this->registry->register( $this->post_type_mock );
	}

	/**
	 * @test
	 */
	public function should_register_post_type_meta_boxes(): void {
		$this->add_action_mock->expects( $this->once() )
			->with( 'init', $this->isType( 'callable' ) )
			->will( $this->returnCallback( fn( string $action, $callable ) => $callable() ) );
		$this->register_post_type_mock->expects( $this->once() )
			->with(
				'custom',
				$this->callback(
					function ( array $value ) {
						$is_valid = array_key_exists( 'register_meta_box_cb', $value ) && is_callable( $value['register_meta_box_cb'] );

						if ( $is_valid ) {
							$value['register_meta_box_cb']( $this->getMockBuilder( WP_Post::class )->getMock() );
						}

						return $is_valid;
					}
				)
			);
		$this->add_meta_box_mock->expects( $this->once() )
			->with(
				'id',
				'title',
				$this->callback(
					function ( $value ) {
						if ( ! is_callable( $value ) ) {
							return false;
						}

						ob_start();

						$value();

						return (string) ob_get_clean() === 'content';
					}
				),
				null,
				'context',
				'priority'
			);
		$this->post_type_mock->method( 'meta_boxes' )->will( $this->returnValue( $this->meta_box_collection_mock ) );

		$this->registry->register( $this->post_type_mock );
	}
}
