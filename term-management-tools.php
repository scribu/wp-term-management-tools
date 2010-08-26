<?php
/*
Plugin Name: Term Management Tools
Version: 1.0
Description: Allows you to merge terms and set term parents in bulk
Tags: admin, management, category, tag, term, hierarchy, taxonomy, organize
Author: scribu
Author URI: http://scribu.net/
Plugin URI: http://scribu.net/wordpress/term-management-tools/
Text Domain: term-management-tools
Domain Path: /lang

Copyright (C) 2010 scribu.net (scribu@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
( at your option ) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

class Term_Management_Tools {

	function init() {
		add_action( 'load-edit-tags.php', array( __CLASS__, 'handler' ) );
		add_action( 'admin_notices', array( __CLASS__, 'notice' ) );

		load_plugin_textdomain( 'term-management-tools', '', basename( dirname( __FILE__ ) ) . '/lang' );
	}

	function handler() {
		$taxonomy = @$_REQUEST['taxonomy'];

		if ( empty( $taxonomy ) )
			$taxonomy = 'post_tag';

		if ( !taxonomy_exists( $taxonomy ) )
			return;

		$tax = get_taxonomy( $taxonomy );

		if ( !current_user_can( $tax->cap->manage_terms ) )
			return;

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'script' ) );
		add_action( 'admin_footer', array( __CLASS__, 'inputs' ) );

		$term_ids = @$_REQUEST['delete_tags'];

		if ( empty( $term_ids ) )
			return;

		$actions = array('set_parent', 'merge');

		foreach ( $actions as $key ) {
			if ( 'bulk_' . $key == @$_REQUEST['action'] || 'bulk_' . $key == @$_REQUEST['action2'] ) {
				check_admin_referer( 'bulk-tags' );
				$r = call_user_func( array(__CLASS__, $key ), $term_ids, $taxonomy );
				break;
			}
		}

		if ( !isset($r) )
			return;

		if ( $referer = wp_get_referer() && false !== strpos( $referer, 'edit-tags.php' ) ) {
			$location = $referer;
		} else {
			$location = add_query_arg('taxonomy', $taxonomy, 'edit-tags.php');
		}

		wp_redirect( add_query_arg( 'message', $r ? 'tmt-updated' : 'tmt-error', $location ) );
		die;
	}

	function notice() {
		if ( 'tmt-updated' == @$_GET['message'] )
			echo '<div id="message" class="updated"><p>' . __( 'Terms updated.', 'term-management-tools' ) . '</p></div>';

		if ( 'tmt-error' == @$_GET['message'] )
			echo '<div id="message" class="error"><p>' . __( 'Terms not updated.', 'term-management-tools' ) . '</p></div>';
	}

	function merge($term_ids, $taxonomy) {
		$term_name = $_REQUEST['bulk_to_tag'];
	
		if ( !$term = term_exists( $term_name, $taxonomy ) )
			$term = wp_insert_term( $term_name, $taxonomy );

		if ( is_wp_error( $term ) )
			return false;

		$to_term = $term['term_id'];

		foreach ( $term_ids as $term_id ) {
			if ( $term_id == $to_term )
				continue;

			$ret = wp_delete_term( $term_id, $taxonomy, array( 'default' => $to_term, 'force_default' => true ) );

			if ( is_wp_error( $ret ) )
				return false;
		}

		return true;
	}

	function set_parent($term_ids, $taxonomy) {
		$parent_id = $_REQUEST['parent'];

		foreach ( $term_ids as $term_id ) {
			if ( $term_id == $parent_id )
				continue;

			$ret = wp_update_term( $term_id, $taxonomy, array('parent' => $parent_id) );

			if ( is_wp_error( $ret ) )
				return false;
		}

		return true;
	}

	function script() {
		global $taxonomy;

		$js_dev = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';

		wp_enqueue_script( 'term-management-tools', plugins_url( "script$js_dev.js", __FILE__ ), array( 'jquery' ), '1.0' );

		wp_localize_script( 'term-management-tools', 'tmtL10n', array(
			'set_parent' => __( 'Set parent', 'term-management-tools' ),
			'merge'      => __( 'Merge', 'term-management-tools' ),
			'hierarchical' => is_taxonomy_hierarchical( $taxonomy ),
		) );
	}

	function inputs() {
		global $taxonomy;

		echo "<div id='tmt-input-merge' style='display:none'>\n";
		printf( __( 'into: %s', 'term-management-tools' ), '<input name="bulk_to_tag" type="text" size="20"></input>' );
		echo "</div>\n";

		if ( !is_taxonomy_hierarchical($taxonomy) )
			return;

		echo "<div id='tmt-input-set_parent' style='display:none'>\n";
		wp_dropdown_categories(array('hide_empty' => 0, 'hide_if_empty' => false, 'name' => 'parent', 'orderby' => 'name', 'taxonomy' => $taxonomy, 'hierarchical' => true, 'show_option_none' => __('None', 'term-management-tools')));
		echo "</div>\n";
	}
}

Term_Management_Tools::init();

