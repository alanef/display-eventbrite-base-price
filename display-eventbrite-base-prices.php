<?php
/*
Plugin Name: Display Eventbrite Base Prices
Plugin URI: https://fullworksplugins.com/products/widget-for-eventbrite/
Description: Custom code to alter the display prices to include base prices for the Display Eventbrite Events Plugin
Version: 1.0
Author: Fullworks
Author URI: https://fullworksplugins.com
License: GPL2

Copyright (C) Fullworks Digital Ltd

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

The license does not entitle the owner to free support, maintenance, enhancement or upgrades of any kind.
*/

/**
 * Filter 'wfea_price_display'
 */
add_filter(
	'wfea_price_display',
	/**
	 * Returns the altered display price appended
	 * to the current price display
	 *
	 * @param $price_display
	 *
	 * @return string
	 */
	function ( $price_display ) {
		return $price_display . debp_get_base_price_range();
	},
	10,
	1
);

/**
 * Examine the current post object to find the min and max base prices
 * and format them for output
 *
 * @return string
 */
function debp_get_base_price_range() {
	global $post;
	$min_display     = '';
	$min             = 9999999;
	$max_display     = '';
	$max             = 0;
	$min_max_display = '';
	if ( ! property_exists( $post, 'tickets' ) ) {
		return $min_max_display;
	}
	foreach ( $post->tickets as $i => $ticket ) {
		if ( null === $ticket->cost ) {
			$min         = 0;
			$min_display = '$0';
			continue;
		}
		if ( $ticket->cost->value < $min ) {
			$min         = $ticket->cost->value;
			$min_display = $ticket->cost->display;
		}
		if ( $ticket->cost->value > $max ) {
			$max         = $ticket->cost->value;
			$max_display = $ticket->cost->display;
		}
	}
	if ( empty ( $max_display ) && empty ( $min_display ) ) {
		return $min_max_display;
	}
	if ( $max_display == $min_display ) {
		$min_max_display = $min_display;
	} else {
		$min_max_display = $min_display . ' - ' . $max_display;
	}

	return '<br><span class="base-price">(' . $min_max_display . ' base price)</span>';
}