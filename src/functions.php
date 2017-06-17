<?php

declare( strict_types = 1 );

/**
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

function iterable_to_array( iterable $iterable ): array {
	if ( is_array( $iterable ) ) {
		return $iterable;
	}

	return iterator_to_array( $iterable );
}
