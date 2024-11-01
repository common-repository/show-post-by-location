<?php
/**
 * show-post-location-public
 *
 * Add taxonomy and field to post
 *
 * @author   Manuel Muñoz <mmr010496@gmail.com>
 * @category WordPress
 * @package  admin
 * @version  0.1
 */

defined( 'ABSPATH' ) || exit;

add_shortcode( 'spl_show_posts_by_location', 'spl_show_posts_by_location_function' );
/**
 * Show posts by location
 *
 * @return string return posts by location
 */
function spl_show_posts_by_location_function( $atts, $content = null ) {
	$atributes = shortcode_atts( array(
		'num_posts'   => -1,
		'order'       => 'ASC',
		'slug_cats'   => '',
		'columns'     => 0,
		'link_post'   => 'true',
		'show_date'   => 'true',
		'show_author' => 'true',
		'filter'      => 'region',
		'slug_posts'  => '',
		'idiom'       => 'es',
	), $atts );

	$categoies_slug = explode(",", $atributes['slug_cats']);

	$posts = '';

	$args = array(
		'posts_per_page' => $atributes['num_posts'],
		'post_type'      => 'post',
		'order'          => $atributes['order'],
	);

	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		
		if ( $atributes['columns'] != 0 ) {
			$columns = '';
			for ( $i=0; $i < $atributes['columns']; $i++ ) { 
				$columns = $columns . '1fr ';
			}
			$posts = '<div style="display: grid;grid-template-columns:' . $columns . ';" class="spl-posts-loop-container">';
		} else {
			$posts = '<div class="spl-posts-loop-container ">';
		}

		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$id_post         = get_the_ID();
			$post_object     = get_post( $id_post );
			$terms           = get_the_terms( $id_post, 'location' );
			$location_region = get_term_meta( $terms[0]->term_id, 'spl-location-region', true );
			$location_city   = get_term_meta( $terms[0]->term_id, 'spl-location-city', true );
			// Get geolocation
			$idiom = $atributes['idiom'];
			$geolocation = spl_get_geolocation( $idiom );

			if ( $atributes['slug_posts'] != '' ) {
				$slugs_posts = explode( ',', $atributes['slug_posts'] );
				
				if ( in_array( $post_object->post_name, $slugs_posts ) ) {
					if ( isset( $atributes['slug_cat'] ) ) {
						foreach ( get_the_category( $id_post ) as $key => $category ) {
							if ( $atributes['filter'] == 'city' ) {
								if ( in_array( $category->slug, $categoies_slug ) && $geolocation->city == $location_city ) {
									$posts = $posts . '<div class="item">';
									if ( $atributes['link_post'] == 'false' ) {
										$posts = $posts . '<p class="title">' . esc_html( get_the_title() ) . '</p>';
									} else {
										$posts = $posts . '<a href="' . esc_url( get_the_permalink() ) . '" class="title">' . esc_html( get_the_title() ) . '</a>';
									}
									$posts = $posts . '<div class="content">' . get_the_content() . '</div>';
									if ( $atributes['show_date'] == 'true' ) {
										$posts = $posts . '<p class="date">' . get_the_date( 'Y-m-d' ) . '</p>';
									}
									if ( $atributes['show_author'] == 'true' ) {
										$posts = $posts . '<p class="author">' . get_the_author() . '</p>';
									}
									$posts = $posts . '</div>';
								}
		
							} else {
								if ( in_array( $category->slug, $categoies_slug ) && $geolocation->regionName == $location_region ) {
									$posts = $posts . '<div class="item">';
									if ( $atributes['link_post'] == 'false' ) {
										$posts = $posts . '<p class="title">' . esc_html( get_the_title() ) . '</p>';
									} else {
										$posts = $posts . '<a href="' . esc_url( get_the_permalink() ) . '" class="title">' . esc_html( get_the_title() ) . '</a>';
									}
									$posts = $posts . '<div class="content">' . get_the_content() . '</div>';
									if ( $atributes['show_date'] == 'true' ) {
										$posts = $posts . '<p class="date">' . get_the_date( 'Y-m-d' ) . '</p>';
									}
									if ( $atributes['show_author'] == 'true' ) {
										$posts = $posts . '<p class="author">' . get_the_author() . '</p>';
									}
									$posts = $posts . '</div>';
								}
							}
						}
					} elseif ( $atributes['slug_cats'] == '' ) {
						if ( $atributes['filter'] == 'city' ) {
							if ( $geolocation->city == $location_city ) {
								$posts = $posts . '<div class="item">';
								if ( $atributes['link_post'] == 'false' ) {
									$posts = $posts . '<p class="title">' . esc_html( get_the_title() ) . '</p>';
								} else {
									$posts = $posts . '<a href="' . esc_url( get_the_permalink() ) . '" class="title">' . esc_html( get_the_title() ) . '</a>';
								}
								$posts = $posts . '<div class="content">' . get_the_content() . '</div>';
								if ( $atributes['show_date'] == 'true' ) {
									$posts = $posts . '<p class="date">' . get_the_date( 'Y-m-d' ) . '</p>';
								}
								if ( $atributes['show_author'] == 'true' ) {
									$posts = $posts . '<p class="author">' . get_the_author() . '</p>';
								}
								$posts = $posts . '</div>';
							}
		
						} else {
							if ( $geolocation->regionName == $location_region ) {
								$posts = $posts . '<div class="item">';
								if ( $atributes['link_post'] == 'false' ) {
									$posts = $posts . '<p class="title">' . esc_html( get_the_title() ) . '</p>';
								} else {
									$posts = $posts . '<a href="' . esc_url( get_the_permalink() ) . '" class="title">' . esc_html( get_the_title() ) . '</a>';
								}
								$posts = $posts . '<div class="content">' . get_the_content() . '</div>';
								if ( $atributes['show_date'] == 'true' ) {
									$posts = $posts . '<p class="date">' . get_the_date( 'Y-m-d' ) . '</p>';
								}
								if ( $atributes['show_author'] == 'true' ) {
									$posts = $posts . '<p class="author">' . get_the_author() . '</p>';
								}
								$posts = $posts . '</div>';
							}
						}
					}
				}
			} else {
				if ( isset( $atributes['slug_cat'] ) ) {
					foreach ( get_the_category( $id_post ) as $key => $category ) {
						if ( $atributes['filter'] == 'city' ) {
							if ( in_array( $category->slug, $categoies_slug ) && $geolocation->city == $location_city ) {
								$posts = $posts . '<div class="item">';
								if ( $atributes['link_post'] == 'false' ) {
									$posts = $posts . '<p class="title">' . esc_html( get_the_title() ) . '</p>';
								} else {
									$posts = $posts . '<a href="' . esc_url( get_the_permalink() ) . '" class="title">' . esc_html( get_the_title() ) . '</a>';
								}
								$posts = $posts . '<div class="content">' . get_the_content() . '</div>';
								if ( $atributes['show_date'] == 'true' ) {
									$posts = $posts . '<p class="date">' . get_the_date( 'Y-m-d' ) . '</p>';
								}
								if ( $atributes['show_author'] == 'true' ) {
									$posts = $posts . '<p class="author">' . get_the_author() . '</p>';
								}
								$posts = $posts . '</div>';
							}
	
						} else {
							if ( in_array( $category->slug, $categoies_slug ) && $geolocation->regionName == $location_region ) {
								$posts = $posts . '<div class="item">';
								if ( $atributes['link_post'] == 'false' ) {
									$posts = $posts . '<p class="title">' . esc_html( get_the_title() ) . '</p>';
								} else {
									$posts = $posts . '<a href="' . esc_url( get_the_permalink() ) . '" class="title">' . esc_html( get_the_title() ) . '</a>';
								}
								$posts = $posts . '<div class="content">' . get_the_content() . '</div>';
								if ( $atributes['show_date'] == 'true' ) {
									$posts = $posts . '<p class="date">' . get_the_date( 'Y-m-d' ) . '</p>';
								}
								if ( $atributes['show_author'] == 'true' ) {
									$posts = $posts . '<p class="author">' . get_the_author() . '</p>';
								}
								$posts = $posts . '</div>';
							}
						}
					}
				} elseif ( $atributes['slug_cats'] == '' ) {
					if ( $atributes['filter'] == 'city' ) {
						if ( $geolocation->city == $location_city ) {
							$posts = $posts . '<div class="item">';
							if ( $atributes['link_post'] == 'false' ) {
								$posts = $posts . '<p class="title">' . esc_html( get_the_title() ) . '</p>';
							} else {
								$posts = $posts . '<a href="' . esc_url( get_the_permalink() ) . '" class="title">' . esc_html( get_the_title() ) . '</a>';
							}
							$posts = $posts . '<div class="content">' . get_the_content() . '</div>';
							if ( $atributes['show_date'] == 'true' ) {
								$posts = $posts . '<p class="date">' . get_the_date( 'Y-m-d' ) . '</p>';
							}
							if ( $atributes['show_author'] == 'true' ) {
								$posts = $posts . '<p class="author">' . get_the_author() . '</p>';
							}
							$posts = $posts . '</div>';
						}
	
					} elseif ( $atributes['filter'] == 'region' ) {
						if ( $geolocation->regionName == $location_region ) {
							$posts = $posts . '<div class="item">';
							if ( $atributes['link_post'] == 'false' ) {
								$posts = $posts . '<p class="title">' . esc_html( get_the_title() ) . '</p>';
							} else {
								$posts = $posts . '<a href="' . esc_url( get_the_permalink() ) . '" class="title">' . esc_html( get_the_title() ) . '</a>';
							}
							$posts = $posts . '<div class="content">' . get_the_content() . '</div>';
							if ( $atributes['show_date'] == 'true' ) {
								$posts = $posts . '<p class="date">' . get_the_date( 'Y-m-d' ) . '</p>';
							}
							if ( $atributes['show_author'] == 'true' ) {
								$posts = $posts . '<p class="author">' . get_the_author() . '</p>';
							}
							$posts = $posts . '</div>';
						}
					}
				}
			}
		}
		$posts = $posts . '</div>';
	}

	return $posts;
}

/**
 * Get geolocation with groPlugin
 *
 * @return object the geolocation values
 */
function spl_get_geolocation( $idiom ) {

	$geolocation_info = wp_remote_retrieve_body( wp_remote_get( 'http://ip-api.com/json/' . get_the_user_ip() . '?lang=' . $idiom ) );
	$decode_info      = json_decode( $geolocation_info );

	if ( is_wp_error( $geolocation_info ) ) {
		return 'Error to read geolocation';
	} else {
		return $decode_info;
	}

	/* VALUES TO GEOLOCATION
	"query": "95.129.155.2",
	"status": "success",
	"country": "España",
	"countryCode": "ES",
	"region": "AN",
	"regionName": "Andalucía",
	"city": "Málaga",
	"zip": "29007",
	"lat": 36.7162,
	"lon": -4.4161,
	"timezone": "Europe/Madrid",
	"isp": "CEMI-MALAGA.",
	"org": "",
	"as": "AS48864 Centro Municipal de Informatica de Malaga"
	*/
}

/**
 * Get user IP
 *
 * @return string return the user IP
 */
function get_the_user_ip() {

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	return apply_filters( 'wpb_get_ip', $ip );
	
}
