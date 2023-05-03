<?php
/**
 * Post Types: Abstract post type class
 *
 * @since 0.1.0
 * @author Martin Pettersson
 * @license GPL-2.0
 * @package Moonwalking_Bits\Post_Types
 */

namespace Moonwalking_Bits\Post_Types;

/**
 * Class representing an abstract post type.
 *
 * This class is intended to be extended to create a post type.
 * It provides sensible default values for the most common parameters.
 *
 * @since 0.1.0
 */
abstract class Abstract_Post_Type {

	/**
	 * Post type key.
	 *
	 * Must not exceed 20 characters and may only contain lowercase alphanumeric
	 * characters, dashes, and underscores.
	 *
	 * @see https://developer.wordpress.org/reference/functions/sanitize_key/
	 * @since 0.1.0
	 * @var string
	 */
	protected string $post_type;

	/**
	 * Whether the post type is intended for use publicly either via the admin
	 * interface or by front-end users.
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $public = false;

	/**
	 * Whether the post type is hierarchical (e.g. page).
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $hierarchical = false;

	/**
	 * Whether to exclude posts with this post type from front end search results.
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $exclude_from_search = true;

	/**
	 * Whether queries can be performed on the front end for the post type as part of {@see parse_request()}.
	 *
	 * Endpoints would include:
	 * - ?post_type={post_type_key}
	 * - ?{post_type_key}={single_post_slug}
	 * - ?{post_type_query_var}={single_post_slug}
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $publicly_queryable = false;

	/**
	 * Whether to generate and allow a UI for managing this post type in
	 * the admin panel.
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $show_ui = false;

	/**
	 * Makes this post type available for selection in navigation menus.
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $show_in_nav_menus = false;

	/**
	 * Makes this post type available via the admin bar.
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $show_in_admin_bar = false;

	/**
	 * Whether to include the post type in the REST API.
	 *
	 * Set this to true for the post type to be available in the block editor.
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $show_in_rest = false;

	/**
	 * Whether to use the internal default meta capability handling.
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $map_meta_cap = false;

	/**
	 * Whether to allow this post type to be exported.
	 *
	 * @since 0.1.0
	 * @var bool
	 */
	protected bool $can_export = false;

	/**
	 * Whether to delete posts of this type when deleting a user.
	 *
	 * If true, posts of this type belonging to the user will be moved to Trash
	 * when the user is deleted.
	 * If false, posts of this type belonging to the user will *not* be trashed or
	 * deleted.
	 * If not set, posts are trashed if post type supports the 'author' feature.
	 * Otherwise, posts are not trashed or deleted.
	 *
	 * @since 0.1.0
	 * @var bool|null
	 */
	protected ?bool $delete_with_user = null;

	/**
	 * Whether there should be post type archives, or if a string, the archive
	 * slug to use.
	 *
	 * Will generate the proper rewrite rules if {@see static::$rewrites} is
	 * enabled.
	 *
	 * @since 0.1.0
	 * @var string|bool
	 */
	protected $has_archive = false;

	/**
	 * Where to show the post type in the admin menu.
	 *
	 * To work, {@see static::$show_ui} must be true. If true, the post type is
	 * shown in its own top level menu. If false, no menu is shown. If a string of
	 * an existing top level menu (eg. 'tools.php' or 'edit.php?post_type=page'),
	 * the post type will be placed as a sub-menu of that.
	 *
	 * @since 0.1.0
	 * @var string|bool
	 */
	protected $show_in_menu = false;

	/**
	 * Base path of the REST API route.
	 *
	 * @since 0.1.0
	 * @var string|null
	 */
	protected ?string $rest_base;

	/**
	 * REST API controller class name.
	 *
	 * Default is 'WP_REST_Posts_Controller'.
	 *
	 * @since 0.1.0
	 * @var string|null
	 */
	protected ?string $rest_controller_class;

	/**
	 * The position in the menu order the post type should appear.
	 *
	 * To work, {@see static::$show_in_menu} must be true.
	 *
	 * @since 0.1.0
	 * @var int|null
	 */
	protected ?int $menu_position = null;

	/**
	 * The icon to be used for this menu.
	 *
	 * Pass a base64-encoded SVG using a data URI, which will be colored to match
	 * the color scheme -- this should begin with 'data:image/svg+xml;base64,'.
	 * Pass the name of a Dashicons helper class to use a font icon, e.g.
	 * 'dashicons-chart-pie'. Pass 'none' to leave div.wp-menu-image empty so an
	 * icon can be added via CSS.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected string $menu_icon = 'none';

	/**
	 * The nouns to use to build the read, edit, and delete capabilities.
	 *
	 * @since 0.1.0
	 * @var string[]
	 */
	protected array $capability_type = array( 'post', 'posts' );

	/**
	 * Array of capabilities for this post type.
	 *
	 * Capability nouns are used as a base to construct capabilities.
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_post_type_capabilities/
	 * @since 0.1.0
	 * @var string[]
	 */
	protected array $capabilities = array();

	/**
	 * Core feature(s) the post type supports.
	 *
	 * Core features include:
	 * - 'title'
	 * - 'editor'
	 * - 'comments'
	 * - 'revisions'
	 * - 'trackbacks'
	 * - 'author'
	 * - 'excerpt'
	 * - 'page-attributes'
	 * - 'thumbnail'
	 * - 'custom-fields'
	 * - 'post-formats'
	 *
	 * Additionally, the 'revisions' feature dictates whether the post type will
	 * store revisions, and the 'comments' feature dictates whether the comments
	 * count will show on the edit screen. A feature can also be specified as an
	 * array of arguments to provide additional information about supporting that
	 * feature. Example: array( 'my_feature', array( 'field' => 'value' ) ).
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected array $supports = array(
		'title',
		'editor',
	);

	/**
	 * Triggers the handling of rewrites for this post type.
	 *
	 * To prevent rewrite, set to false. To specify rewrite rules, an array can be
	 * passed.
	 *
	 * @since 0.1.0
	 * @var array|bool {
	 * @type string $slug Customize the permastruct slug.
	 * @type bool   $with_front Whether the permastruct should be prepended with {@see WP_Rewrite::$front}.
	 *                              Default true.
	 * @type bool   $feeds Whether the feed permastruct should be built for this post type. Default is value of
	 *                              {@see static::$has_archive}.
	 * @type bool   $pages Whether the permastruct should provide for pagination. Default true.
	 * @type int    $ep_mask Endpoint mask to assign. If not specified and permalink_epmask is set, inherits from
	 *                              {@see $permalink_epmask}. If not specified and {@see $permalink_epmask} is not set,
	 *                              defaults to {@see EP_PERMALINK}.
	 * }
	 */
	protected $rewrite = true;

	/**
	 * Sets the query_var key for this post type.
	 *
	 * Defaults to {@see static::$post_type}. If false, a post type cannot be
	 * loaded at ?{query_var}={post_slug}. If specified as a string, the query
	 * ?{query_var_string}={post_slug} will be valid.
	 *
	 * @since 0.1.0
	 * @var string|bool
	 */
	protected $query_var = true;

	/**
	 * Array of blocks to use as the default initial state for an editor session.
	 *
	 * Each item should be an array containing block name and optional attributes.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected array $template = array();

	/**
	 * Whether the block template should be locked if {@see static::$template} is
	 * set.
	 *
	 * If set to 'all', the user is unable to insert new blocks, move existing
	 * blocks and delete blocks.
	 * If set to 'insert', the user is able to move existing blocks but is unable
	 * to insert new blocks and delete blocks.
	 *
	 * @since 0.1.0
	 * @var string|bool
	 */
	protected $template_lock = false;

	/**
	 * Registered post type meta boxes.
	 *
	 * @since 0.1.0
	 * @var \Moonwalking_Bits\Post_Types\Meta_Box_Collection
	 */
	protected Meta_Box_Collection $meta_boxes;

	/**
	 * Registered taxonomies.
	 *
	 * @since 0.2.0
	 * @var \Moonwalking_Bits\Post_Types\Taxonomy_Collection
	 */
	protected Taxonomy_Collection $taxonomies;

	/**
	 * Creates a new post type instance.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		$this->meta_boxes = new Meta_Box_Collection();
		$this->taxonomies = new Taxonomy_Collection();
	}

	/**
	 * Returns the post type key.
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function key(): string {
		return $this->post_type;
	}

	/**
	 * Determines whether the post type is intended for use publicly either via
	 * the admin interface or by front-end users.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function is_public(): bool {
		return $this->public;
	}

	/**
	 * Determines whether the post type is hierarchical (e.g. page).
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function is_hierarchical(): bool {
		return $this->hierarchical;
	}

	/**
	 * Determines whether to include posts with this post type in front end search results.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function is_included_in_search(): bool {
		return ! $this->exclude_from_search;
	}

	/**
	 * Determines whether queries can be performed on the front end for the post
	 * type as part of {@see parse_request()}.
	 *
	 * Endpoints would include:
	 * - ?post_type={post_type_key}
	 * - ?{post_type_key}={single_post_slug}
	 * - ?{post_type_query_var}={single_post_slug}
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function is_publicly_queryable(): bool {
		return $this->publicly_queryable;
	}

	/**
	 * Determines whether to generate and allow a UI for managing this post type
	 * in the admin panel.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function is_showing_ui(): bool {
		return $this->show_ui;
	}

	/**
	 * Determines whether this post type is available for selection in navigation
	 * menus.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function is_visible_in_nav_menus(): bool {
		return $this->show_in_nav_menus;
	}

	/**
	 * Determines whether this post type is available via the admin bar.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function is_visible_in_admin_bar(): bool {
		return $this->show_in_admin_bar;
	}

	/**
	 * Determines whether the post type is included in the REST API.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function is_included_in_rest(): bool {
		return $this->show_in_rest;
	}

	/**
	 * Determines whether to use the internal default meta capability handling.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function is_using_default_meta_capability_handling(): bool {
		return $this->map_meta_cap;
	}

	/**
	 * Determines whether to allow this post type to be exported.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function can_be_exported(): bool {
		return $this->can_export;
	}

	/**
	 * Returns whether to delete posts of this type when deleting a user.
	 *
	 * @since 0.1.0
	 * @return bool|null
	 */
	public function delete_with_user(): ?bool {
		return $this->delete_with_user;
	}

	/**
	 * Returns whether there should be post type archives, or if a string, the
	 * archive slug to use.
	 *
	 * @since 0.1.0
	 * @return string|bool
	 */
	public function archive() {
		return $this->has_archive;
	}

	/**
	 * Returns where to show the post type in the admin menu.
	 *
	 * @since 0.1.0
	 * @return string|bool
	 */
	public function menu_location() {
		return $this->show_in_menu;
	}

	/**
	 * Returns the base path of the REST API route.
	 *
	 * @since 0.1.0
	 * @return bool|string
	 */
	public function rest_base_path() {
		return $this->rest_base ?? false;
	}

	/**
	 * Returns the REST API controller class name.
	 *
	 * @since 0.1.0
	 * @return string|bool
	 */
	public function rest_controller_class_name() {
		return $this->rest_controller_class ?? false;
	}

	/**
	 * Returns the position in the menu order the post type should appear.
	 *
	 * @since 0.1.0
	 * @return int|null
	 */
	public function menu_position(): ?int {
		return $this->menu_position;
	}

	/**
	 * Returns the icon to be used for this menu.
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function menu_icon(): string {
		return $this->menu_icon;
	}

	/**
	 * Returns the nouns to use to build the read, edit, and delete capabilities.
	 *
	 * @since 0.1.0
	 * @return string[]
	 */
	public function capability_nouns(): array {
		return $this->capability_type;
	}

	/**
	 * Returns an array of capabilities for this post type.
	 *
	 * @since 0.1.0
	 * @return string[]
	 */
	public function capabilities(): array {
		return $this->capabilities;
	}

	/**
	 * Returns core feature(s) the post type supports.
	 *
	 * @since 0.1.0
	 * @return string[]
	 */
	public function features(): array {
		return $this->supports;
	}

	/**
	 * Returns the rewrites for this post type.
	 *
	 * @since 0.1.0
	 * @return array|bool
	 */
	public function rewrites() {
		return $this->rewrite;
	}

	/**
	 * Returns the query_var key for this post type.
	 *
	 * @since 0.1.0
	 * @return string|bool
	 */
	public function query_parameter_name() {
		return $this->query_var;
	}

	/**
	 * Returns an array of blocks to use as the default initial state for an
	 * editor session.
	 *
	 * @since 0.1.0
	 * @return array
	 */
	public function template_blocks(): array {
		return $this->template;
	}

	/**
	 * Returns the lock strategy to use for the block template.
	 *
	 * @since 0.1.0
	 * @return string|bool
	 */
	public function template_lock_strategy() {
		return $this->template_lock;
	}

	/**
	 * Returns registered post type meta boxes.
	 *
	 * @since 0.1.0
	 * @return \Moonwalking_Bits\Post_Types\Meta_Box_Collection Registered post type meta boxes.
	 */
	public function meta_boxes(): Meta_Box_Collection {
		return $this->meta_boxes;
	}

	/**
	 * Returns registered post type taxonomies.
	 *
	 * @since 0.2.0
	 * @return \Moonwalking_Bits\Post_Types\Taxonomy_Collection Registered post type taxonomies.
	 */
	public function taxonomies(): Taxonomy_Collection {
		return $this->taxonomies;
	}

	/**
	 * Returns a short descriptive summary of what the post type is.
	 *
	 * @since 0.1.0
	 * @return string A short descriptive summary of what the post type is.
	 */
	abstract public function description(): string;

	/**
	 * Returns an array of labels for this post type.
	 *
	 * If empty, post labels are inherited for non-hierarchical types and page
	 * labels for hierarchical ones.
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_post_type_labels/
	 * @since 0.1.0
	 * @return array<string, string> An array of labels for this post type.
	 */
	abstract public function labels(): array;
}
