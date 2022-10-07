<?php
/**
 * Post Types: post type registry class
 *
 * @since 0.1.0
 * @author Martin Pettersson
 * @license GPL-2.0
 * @package Moonwalking_Bits\Post_Types
 */

namespace Moonwalking_Bits\Post_Types;

use WP_Post;

/**
 * Represents a registry of post types.
 *
 * @since 0.1.0
 */
class Post_Type_Registry {

	/**
	 * Available post type features.
	 *
	 * @var string[]
	 */
	private static array $available_features = array(
		'title',
		'editor',
		'excerpt',
		'author',
		'trackbacks',
		'thumbnail',
		'custom-fields',
		'comments',
		'revisions',
		'page-attributes',
		'post-formats',
	);

	/**
	 * Registered post types.
	 *
	 * @var \Moonwalking_Bits\Post_Types\Abstract_Post_Type[]
	 */
	private array $post_types = array();

	/**
	 * Registers a post type.
	 *
	 * @since 0.1.0
	 * @param \Moonwalking_Bits\Post_Types\Abstract_Post_Type $post_type Post type instance.
	 */
	public function register( Abstract_Post_Type $post_type ): void {
		add_action( 'init', fn() => $this->register_post_type( $post_type ) );
	}

	/**
	 * Returns all registered post types.
	 *
	 * @since 0.1.0
	 * @return \Moonwalking_Bits\Post_Types\Abstract_Post_Type[] All registered post types.
	 */
	public function all(): array {
		return $this->post_types;
	}

	/**
	 * Registers the given post type.
	 *
	 * @param \Moonwalking_Bits\Post_Types\Abstract_Post_Type $post_type Post type instance.
	 */
	private function register_post_type( Abstract_Post_Type $post_type ): void {
		register_post_type(
			$post_type->key(),
			array(
				'description'           => $post_type->description(),
				'labels'                => $post_type->labels(),
				'public'                => $post_type->is_public(),
				'hierarchical'          => $post_type->is_hierarchical(),
				'exclude_from_search'   => ! $post_type->is_included_in_search(),
				'publicly_queryable'    => $post_type->is_publicly_queryable(),
				'show_ui'               => $post_type->is_showing_ui(),
				'show_in_nav_menus'     => $post_type->is_visible_in_nav_menus(),
				'show_in_admin_bar'     => $post_type->is_visible_in_admin_bar(),
				'show_in_rest'          => $post_type->is_included_in_rest(),
				'map_meta_cap'          => $post_type->is_using_default_meta_capability_handling(),
				'can_export'            => $post_type->is_exported(),
				'delete_with_user'      => $post_type->delete_with_user(),
				'has_archive'           => $post_type->archive(),
				'show_in_menu'          => $post_type->menu_location(),
				'rest_base'             => $post_type->rest_base_path(),
				'rest_controller_class' => $post_type->rest_controller_class_name(),
				'menu_position'         => $post_type->menu_position(),
				'menu_icon'             => $post_type->menu_icon(),
				'capability_type'       => $post_type->capability_nouns(),
				'capabilities'          => $post_type->capabilities(),
				'supports'              => $post_type->features(),
				'taxonomies'            => array_map(
					fn( $taxonomy ) => $taxonomy->key(),
					iterator_to_array( $post_type->taxonomies() )
				),
				'rewrite'               => $post_type->rewrites(),
				'query_var'             => $post_type->query_parameter_name(),
				'template'              => $post_type->template_blocks(),
				'template_lock'         => $post_type->template_lock_strategy(),
				'register_meta_box_cb'  => fn( WP_Post $post ) => $this->register_meta_boxes( $post_type, $post ),
			)
		);

		foreach ( array_diff( self::$available_features, $post_type->features() ) as $feature ) {
			remove_post_type_support( $post_type->key(), $feature );
		}

		/**
		 * Taxonomy instance.
		 *
		 * @var \Moonwalking_Bits\Post_Types\Abstract_Taxonomy $taxonomy
		 */
		foreach ( $post_type->taxonomies() as $taxonomy ) {
			$this->register_taxonomy( $taxonomy, $post_type );
		}

		$this->post_types[] = $post_type;
	}

	/**
	 * Registers the given post type's meta boxes.
	 *
	 * @param \Moonwalking_Bits\Post_Types\Abstract_Post_Type $post_type Post type instance.
	 * @param \WP_Post                                        $post Post instance.
	 */
	private function register_meta_boxes( Abstract_Post_Type $post_type, WP_Post $post ): void {
		foreach ( $post_type->meta_boxes() as $meta_box ) {
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			add_meta_box(
				$meta_box->id(),
				$meta_box->title(),
				fn() => print $meta_box->render( $post ),
				// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal
				null,
				$meta_box->context(),
				$meta_box->priority()
			);
			// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Registers a taxonomy for the given post type.
	 *
	 * @param \Moonwalking_Bits\Post_Types\Abstract_Taxonomy  $taxonomy Taxonomy instance.
	 * @param \Moonwalking_Bits\Post_Types\Abstract_Post_Type $post_type Post type instance.
	 */
	private function register_taxonomy( Abstract_Taxonomy $taxonomy, Abstract_Post_Type $post_type ): void {
		register_taxonomy(
			$taxonomy->key(),
			$post_type->key(),
			array(
				'labels'                => $taxonomy->labels(),
				'description'           => $taxonomy->description(),
				'public'                => $taxonomy->is_public(),
				'publicly_queryable'    => $taxonomy->is_publicly_queryable(),
				'hierarchical'          => $taxonomy->is_hierarchical(),
				'show_ui'               => $taxonomy->is_showing_ui(),
				'show_in_menu'          => $taxonomy->is_showing_in_menu(),
				'show_in_nav_menus'     => $taxonomy->is_visible_in_nav_menus(),
				'show_in_rest'          => $taxonomy->is_included_in_rest(),
				'rest_base'             => $taxonomy->rest_base_path(),
				'rest_namespace'        => $taxonomy->rest_namespace_path(),
				'rest_controller_class' => $taxonomy->rest_controller_class_name(),
				'show_tag_cloud'        => $taxonomy->is_showing_tag_cloud(),
				'show_in_quick_edit'    => $taxonomy->is_showing_in_quick_edit(),
				'show_admin_column'     => $taxonomy->is_showing_admin_column(),
				'capabilities'          => $taxonomy->capabilities(),
				'rewrite'               => $taxonomy->rewrite(),
				'query_var'             => $taxonomy->query_parameter_name(),
				'default_term'          => $taxonomy->default_term(),
				'sort'                  => $taxonomy->is_sorted(),
			)
		);
	}
}
