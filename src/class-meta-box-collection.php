<?php
/**
 * Post Types: Meta box collection class
 *
 * @package Moonwalking_Bits\Post_Types
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Post_Types;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * Class representing a meta box collection.
 *
 * @see \IteratorAggregate
 * @since 0.1.0
 */
class Meta_Box_Collection implements IteratorAggregate {

	/**
	 * Registered meta boxes.
	 *
	 * @var \Moonwalking_Bits\Post_Types\Abstract_Meta_Box[]
	 */
	private array $meta_boxes = array();

	/**
	 * Adds the given meta box instance to the collection.
	 *
	 * @since 0.1.0
	 * @param \Moonwalking_Bits\Post_Types\Abstract_Meta_Box $meta_box Meta box instance.
	 * @return $this Same instance for method chaining.
	 */
	public function add( Abstract_Meta_Box $meta_box ): self {
		$this->meta_boxes[] = $meta_box;

		return $this;
	}

	/**
	 * Returns an iterator providing access to the registered meta boxes.
	 *
	 * @since 0.1.0
	 * @return \Traversable Iterator providing access to the registered meta boxes.
	 */
	public function getIterator(): Traversable {
		return new ArrayIterator( $this->meta_boxes );
	}
}
