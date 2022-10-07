<?php

namespace Moonwalking_Bits\Post_Types;

use PHPUnit\Framework\TestCase;

class Taxonomy_Collection_Test extends TestCase {

	private Taxonomy_Collection $collection;

	/**
	 * @before
	 */
	public function set_up(): void {
		$this->collection = new Taxonomy_Collection();
	}

	/**
	 * @test
	 */
	public function should_be_iterable(): void {
		$this->assertIsIterable( $this->collection );
	}

	/**
	 * @test
	 */
	public function should_be_empty_by_default(): void {
		$this->assertEmpty( $this->collection->getIterator() );
	}

	/**
	 * @test
	 */
	public function should_add_meta_box(): void {
		$taxonomy_mock = $this->getMockBuilder( Abstract_Taxonomy::class )->getMock();

		$this->collection->add( $taxonomy_mock );

		$this->assertCount( 1, $this->collection->getIterator() );

		foreach ( $this->collection->getIterator() as $meta_box ) {
			$this->assertSame( $taxonomy_mock, $meta_box );
		}
	}

	/**
	 * @test
	 */
	public function add_should_be_chainable(): void {
		$this->collection
			->add( $this->getMockBuilder( Abstract_Taxonomy::class )->getMock() )
			->add( $this->getMockBuilder( Abstract_Taxonomy::class )->getMock() );

		$this->assertCount( 2, $this->collection->getIterator() );
	}
}
