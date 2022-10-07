<?php

namespace Moonwalking_Bits\Post_Types\Fixtures;

use Moonwalking_Bits\Post_Types\Abstract_Taxonomy;

class Taxonomy extends Abstract_Taxonomy {

	protected string $taxonomy = 'taxonomy';

	public function description(): string {
		return '';
	}

	public function labels(): array {
		return array();
	}
}
