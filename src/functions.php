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

function iterable_to_iterator( iterable $iterable ): Iterator {
	if ( $iterable instanceof Iterator ) {
		return $iterable;
	}

	if ( is_array( $iterable ) ) {
		return new ArrayIterator( $iterable );
	}

	return new \WMDE\TraversableIterator\TraversableIterator( $iterable );
}

/**
 * array_merge clone for iterables using lazy evaluation
 *
 * As with array_merge, numeric elements with keys are assigned a fresh key,
 * starting with key 0. Unlike array_merge, elements with duplicate non-numeric
 * keys are kept in the Generator. Beware that when converting the Generator
 * to an array with a function such as iterator_to_array, these duplicates will
 * be dropped, resulting in identical behaviour as array_merge.
 *
 *
 * @param iterable ...$iterables
 * @return Generator
 */
function iterable_merge( iterable ...$iterables ): Generator {
	$numericIndex = 0;

	foreach ( $iterables as $iterable ) {
		foreach ( $iterable as $key => $value ) {
			yield is_int( $key ) ? $numericIndex++ : $key => $value;
		}
	}
}