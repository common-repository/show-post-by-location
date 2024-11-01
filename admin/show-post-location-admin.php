<?php
/**
 * show-post-location-admin
 *
 * Add taxonomy and field to post
 *
 * @author   Manuel Muñoz Rodríguez
 * @category admin
 * @package  admin
 * @version  0.1
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'spl_add_custom_taxonomies', 0 );
/**
 * Add new taxonomy to post
 *
 * @return void
 */
function spl_add_custom_taxonomies() {
	// Add new taxonomy to Posts.
	register_taxonomy('location', 'post', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		'labels'       => array(
			'name'              => _x( 'Localizaciones', 'show-post-location' ),
			'singular_name'     => _x( 'Location', 'spl-locatione' ),
			'search_items'      =>  __( 'Buscar Localizaciones', 'show-post-location' ),
			'all_items'         => __( 'Todas las Localizaciones', 'show-post-location' ),
			'parent_item'       => __( 'Localización Padre', 'show-post-location' ),
			'parent_item_colon' => __( 'Localización Padre:', 'show-post-location' ),
			'edit_item'         => __( 'Editar Localización', 'show-post-location' ),
			'update_item'       => __( 'Actualizar Localización', 'show-post-location' ),
			'add_new_item'      => __( 'Añadir nueva Localización', 'show-post-location' ),
			'new_item_name'     => __( 'Nueva Localización Nombre', 'show-post-location' ),
			'menu_name'         => __( 'Localizaciones', 'show-post-location' ),
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug'         => __( 'locations', 'show-post-location' ), // This controls the base slug that will display before each term
			'with_front'   => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
}

add_action( 'location_edit_form_fields', 'spl_edit_term_fields', 10, 2 );
/**
 * Edit new field in taxonomy
 *
 * @param object $term array to taxonomy terms.
 * @param object $taxonomy the taxonomy object.
 * @return void
 */
function spl_edit_term_fields( $term, $taxonomy ) {

	$region = get_term_meta( $term->term_id, 'spl-location-region', true );
	$city   = get_term_meta( $term->term_id, 'spl-location-city', true );
	
	echo '<tr class="form-field">
	<th>
		<label for="spl-location">' . __( 'Localización.', 'show-post-location' ) . '</label>
	</th>
	<td>
		<input name="spl-location-region" id="spl-location-region" type="text" value="' . esc_attr( $region ) .'" />
		<p class="description">' . __( 'Región donde se mostrarán los posts. Ejemplo -> Andalucía', 'show-post-location' ) . '</p>
		<input name="spl-location-city" id="spl-location-city" type="text" value="' . esc_attr( $city ) .'" />
		<p class="description">' . __( 'Ciudad donde se mostrarán los posts.  Ejemplo -> Málaga', 'show-post-location' ) . '</p>
	</td>
	</tr>';

}

add_action( 'created_location', 'spl_save_term_fields' );
add_action( 'edited_location', 'spl_save_term_fields' );
/**
 * Create and edit fields.
 *
 * @param object $term_id object to terms.
 * @return void
 */
function spl_save_term_fields( $term_id ) {

	update_term_meta(
		$term_id,
		'spl-location-region',
		sanitize_text_field( $_POST[ 'spl-location-region' ] )
	);

	update_term_meta(
		$term_id,
		'spl-location-city',
		sanitize_text_field( $_POST[ 'spl-location-city' ] )
	);

}
