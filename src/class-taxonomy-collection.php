<?php
/**
 * Post Types: Taxonomy collection class
 *
 * @package Moonwalking_Bits\Post_Types
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.2.0
 */

namespace Moonwalking_Bits\Post_Types;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * Class representing a taxonomy collection.
 *
 * @see \IteratorAggregate
 * @since 0.2.0
 */
class Taxonomy_Collection implements IteratorAggregate {

	/**
	 * Registered taxonomies.
	 *
	 * @var \Moonwalking_Bits\Post_Types\Abstract_Taxonomy[]
	 */
	private array $taxonomies = array();

	/**
	 * Adds the given taxonomy instance to the collection.
	 *
	 * @since 0.2.0
	 * @param \Moonwalking_Bits\Post_Types\Abstract_Taxonomy $taxonomy Taxonomy instance.
	 * @return $this Same instance for method chaining.
	 */
	public function add( Abstract_Taxonomy $taxonomy ): self {
		$this->taxonomies[] = $taxonomy;

		return $this;
	}

	/**
	 * Returns an iterator providing access to the registered meta boxes.
	 *
	 * @since 0.2.0
	 * @return \Traversable Iterator providing access to the registered meta boxes.
	 */
	public function getIterator(): Traversable {
		return new ArrayIterator( $this->taxonomies );
	}
}
