<?php
/**
 * Post Types: Abstract taxonomy class
 *
 * @since 0.2.0
 * @author Martin Pettersson
 * @license GPL-2.0
 * @package Moonwalking_Bits\Post_Types
 */

namespace Moonwalking_Bits\Post_Types;

/**
 * Class representing an abstract taxonomy.
 *
 * This class is intended to be extended to create a post type taxonomy. It
 * provides sensible default values for the most common parameters.
 *
 * @since 0.2.0
 */
abstract class Abstract_Taxonomy {

	/**
	 * Taxonomy key.
	 *
	 * Must not exceed 32 characters.
	 *
	 * @since 0.2.0
	 * @var string
	 */
	protected string $taxonomy;

	/**
	 * Whether the taxonomy is intended for use publicly either via the admin
	 * interface or by front-end users.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $public = false;

	/**
	 * Whether the taxonomy is hierarchical.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $hierarchical = false;

	/**
	 * Whether the taxonomy is publicly queryable.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $publicly_queryable = false;

	/**
	 * Whether to generate and allow a UI for managing terms in this taxonomy in the
	 * admin panel.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $show_ui = false;

	/**
	 * Makes this taxonomy available for selection in navigation menus.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $show_in_nav_menus = false;

	/**
	 * Whether to include the taxonomy in the REST API.
	 *
	 * Set this to true for the taxonomy to be available in the block editor.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $show_in_rest = false;

	/**
	 * Default term to be used for the taxonomy.
	 *
	 * @since 0.2.0
	 * @var array|null {
	 * @type string $name Name of default term.
	 * @type string $slug Slug for default term.
	 * @type string $description Description for default term.
	 * }
	 */
	protected ?array $default_term = array();

	/**
	 * Whether terms in this taxonomy should be sorted in the order they are
	 * provided to {@see \wp_set_object_terms()}.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $is_sorted = false;

	/**
	 * Whether to show the taxonomy in the admin menu. If true, the taxonomy is
	 * shown as a submenu of the object type menu. If false, no menu is shown.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $show_in_menu = false;

	/**
	 * Base path of the REST API route.
	 *
	 * @since 0.2.0
	 * @var string|null
	 */
	protected ?string $rest_base;

	/**
	 * Namespace of the REST API route.
	 *
	 * @since 0.2.0
	 * @var string|null
	 */
	protected ?string $rest_namespace;

	/**
	 * REST API controller class name.
	 *
	 * Default is 'WP_REST_Terms_Controller'.
	 *
	 * @since 0.2.0
	 * @var string|null
	 */
	protected ?string $rest_controller_class;

	/**
	 * Whether to list the taxonomy in the Tag Cloud Widget controls.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $show_tag_cloud = false;

	/**
	 * Whether to show the taxonomy in the quick/bulk edit panel.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $show_in_quick_edit = false;

	/**
	 * Whether to display a column for the taxonomy on its post type listing screens.
	 *
	 * @since 0.2.0
	 * @var bool
	 */
	protected bool $show_admin_column = false;

	/**
	 * Array of capabilities for this taxonomy.
	 *
	 * @since 0.2.0
	 * @var string[]
	 */
	protected array $capabilities = array();

	/**
	 * Triggers the handling of rewrites for this taxonomy.
	 *
	 * To prevent rewrite, set to false. To specify rewrite rules, an array can be
	 * passed.
	 *
	 * @since 0.2.0
	 * @var array|bool {
	 * @type string $slug Customize the permastruct slug. Default {@see static::$taxonomy}.
	 * @type bool   $with_front Whether the permastruct should be prepended with
	 *                                {@see WP_Rewrite::$front}. Default true.
	 * @type bool   $hierarchical Either hierarchical rewrite tag or not. Default false.
	 * @type int    $ep_mask Assign an endpoint mask. Default EP_NONE.
	 * }
	 */
	protected $rewrite = true;

	/**
	 * Sets the query var key for this taxonomy.
	 *
	 * Defaults to {@see static::$taxonomy}. If false, a taxonomy cannot be loaded
	 * at ?{query_var}={term_slug}. If a string, the query ?{query_var}={term_slug}
	 * will be valid.
	 *
	 * @since 0.2.0
	 * @var string|bool
	 */
	protected $query_var = true;

	/**
	 * Returns the taxonomy key.
	 *
	 * @since 0.2.0
	 * @return string
	 */
	public function key(): string {
		return $this->taxonomy;
	}

	/**
	 * Determines whether the taxonomy is intended for use publicly either via
	 * the admin interface or by front-end users.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_public(): bool {
		return $this->public;
	}

	/**
	 * Determines whether the taxonomy is hierarchical.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_hierarchical(): bool {
		return $this->hierarchical;
	}

	/**
	 * Determines Whether the taxonomy is publicly queryable.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_publicly_queryable(): bool {
		return $this->publicly_queryable;
	}

	/**
	 * Determines whether to generate and allow a UI for managing terms in this
	 * taxonomy in the admin panel.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_showing_ui(): bool {
		return $this->show_ui;
	}

	/**
	 * Determines whether this taxonomy is available for selection in navigation
	 * menus.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_visible_in_nav_menus(): bool {
		return $this->show_in_nav_menus;
	}

	/**
	 * Determines whether to include the taxonomy in the REST API.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_included_in_rest(): bool {
		return $this->show_in_rest;
	}

	/**
	 * Returns the default term to be used for the taxonomy.
	 *
	 * @since 0.2.0
	 * @return array|bool
	 */
	public function default_term() {
		return $this->default_term ?? false;
	}

	/**
	 * Determines whether terms in this taxonomy should be sorted in the order they
	 * are provided to {@see \wp_set_object_terms()}.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_sorted(): bool {
		return $this->is_sorted;
	}

	/**
	 * Returns whether to show the taxonomy in the admin menu. If true, the taxonomy
	 * is shown as a submenu of the object type menu. If false, no menu is shown.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_showing_in_menu(): bool {
		return $this->show_in_menu;
	}

	/**
	 * Returns the base path of the REST API route.
	 *
	 * @since 0.2.0
	 * @return string|bool
	 */
	public function rest_base_path() {
		return $this->rest_base ?? false;
	}

	/**
	 * Returns the namespace of the REST API route.
	 *
	 * @since 0.2.0
	 * @return string|bool
	 */
	public function rest_namespace_path() {
		return $this->rest_namespace ?? false;
	}

	/**
	 * Returns the REST API controller class name.
	 *
	 * @since 0.2.0
	 * @return string|bool
	 */
	public function rest_controller_class_name() {
		return $this->rest_controller_class ?? false;
	}

	/**
	 * Determines whether to list the taxonomy in the Tag Cloud Widget controls.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_showing_tag_cloud(): bool {
		return $this->show_tag_cloud;
	}

	/**
	 * Determines whether to show the taxonomy in the quick/bulk edit panel.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_showing_in_quick_edit(): bool {
		return $this->show_in_quick_edit;
	}

	/**
	 * Determines whether to display a column for the taxonomy on its post type
	 * listing screens.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function is_showing_admin_column(): bool {
		return $this->show_admin_column;
	}

	/**
	 * Returns an array of capabilities for this taxonomy.
	 *
	 * @since 0.2.0
	 * @return string[]
	 */
	public function capabilities(): array {
		return $this->capabilities;
	}

	/**
	 * Returns the rewrites for this taxonomy.
	 *
	 * @since 0.2.0
	 * @return array|bool
	 */
	public function rewrite() {
		return $this->rewrite;
	}

	/**
	 * Returns the query_var key for this taxonomy.
	 *
	 * @since 0.2.0
	 * @return string|bool
	 */
	public function query_parameter_name() {
		return $this->query_var;
	}

	/**
	 * Returns a short descriptive summary of what the taxonomy is for.
	 *
	 * @since 0.2.0
	 * @return string A short descriptive summary of what the taxonomy is for.
	 */
	abstract public function description(): string;

	/**
	 * Returns an array of labels for this taxonomy.
	 *
	 * By default, Tag labels are used for non-hierarchical taxonomies, and Category
	 * labels are used for hierarchical taxonomies.
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_taxonomy_labels/
	 * @since 0.2.0
	 * @return array<string, string> An array of labels for this taxonomy.
	 */
	abstract public function labels(): array;
}
