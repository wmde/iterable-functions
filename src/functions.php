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
 * Similar to array_merge and should have identical behaviour for array inputs
 * after the resulting Generator has been put through iterator_to_array.
 *
 * As with array_merge, numeric elements with keys are assigned a fresh key,
 * starting with key 0.
 *
 * Note that if the iterables have elements with duplicate (non-numeric) keys,
 * they will NOT be omitted in the Generator.
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