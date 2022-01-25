<?php
/**
 * Post Types: Abstract post type meta box class
 *
 * @package Moonwalking_Bits\Post_Types
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Post_Types;

use WP_Post;

/**
 * Class representing an abstract post type meta box.
 *
 * @since 0.1.0
 */
abstract class Abstract_Meta_Box {

	/**
	 * Meta box ID (used in the 'id' attribute for the meta box).
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected string $id;

	/**
	 * Title of the meta box.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected string $title;

	/**
	 * The context within screen where the meta box should be displayed.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected string $context = 'advanced';

	/**
	 * The priority within the context where the meta box should be displayed.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected string $priority = 'default';

	/**
	 * Returns the meta box ID (used in the 'id' attribute for the meta box).
	 *
	 * @since 0.1.0
	 * @return string Meta box ID (used in the 'id' attribute for the meta box).
	 */
	public function id(): string {
		return $this->id;
	}

	/**
	 * Returns the title of the meta box.
	 *
	 * @since 0.1.0
	 * @return string Title of the meta box.
	 */
	public function title(): string {
		return $this->title;
	}

	/**
	 * Returns the context within screen where the meta box should be displayed.
	 *
	 * @since 0.1.0
	 * @return string The context within screen where the meta box should be displayed.
	 */
	public function context(): string {
		return $this->context;
	}

	/**
	 * Returns the priority within the context where the meta box should be displayed.
	 *
	 * @since 0.1.0
	 * @return string The priority within the context where the meta box should be displayed.
	 */
	public function priority(): string {
		return $this->priority;
	}

	/**
	 * Returns the meta box content.
	 *
	 * @since 0.1.0
	 * @param \WP_Post $post Post instance.
	 * @return string The meta box content.
	 */
	abstract public function render( WP_Post $post ): string;
}
