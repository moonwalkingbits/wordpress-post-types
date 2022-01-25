<?php

namespace Moonwalking_Bits\Post_Types\Fixtures;

use Moonwalking_Bits\Post_Types\Abstract_Meta_Box;
use WP_Post;

class Meta_Box extends Abstract_Meta_Box {

	protected string $id = 'meta-box';
	protected string $title = 'Title';

	public function render( WP_Post $post ): string {
		return '';
	}
}
