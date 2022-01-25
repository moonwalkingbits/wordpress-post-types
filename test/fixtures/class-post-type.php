<?php

namespace Moonwalking_Bits\Post_Types\Fixtures;

use Moonwalking_Bits\Post_Types\Abstract_Post_Type;

class Post_Type extends Abstract_Post_Type {

	protected string $post_type = 'post-type';

	public function description(): string {
		return '';
	}

	public function labels(): array {
		return array();
	}
}
