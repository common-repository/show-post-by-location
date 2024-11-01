<?php
/**
 * class-show-psot-location-setting
 *
 * Show configuration menu in administrator part
 *
 * @author   Manuel Muñoz Rodríguez
 * @category admin
 * @package  admin
 * @version  0.1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings
 */
class SPL_Settings {
	/**
	 * Settings
	 *
	 * @var array
	 */
	private $spl_settings;

	/**
	 * Construct of class
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_head', array( $this, 'custom_css' ) );
	}

	/**
	 * Adds plugin page.
	 *
	 * @return void
	 */
	public function add_plugin_page() {
		add_menu_page(
			'Filter by post location',
			'Filter by post location',
			'manage_options',
			'spl',
			array( $this, 'create_admin_page' ),
			esc_url( plugins_url( 'admin/images/icono.svg', dirname( __FILE__ ) ) ),
			99
		);
	}

	/**
	 * Create admin page.
	 *
	 * @return void
	 */
	public function create_admin_page() {
		$this->spl_settings = get_option( 'spl_settings' );
		?>

		<div class="wrap">
			<h2><?php echo esc_html__( 'Configuración de la localización de los post', 'show-post-location' ) ?></h2>
			<p></p>
			<?php 
			settings_errors();
			?>
		    
			<h2 class="nav-tab-wrapper">
				<a href="?page=<?php echo esc_html( $_GET['page'] ); ?>" class="nav-tab <?php
				if ( $_GET['tab'] === null ) {
					?>
					nav-tab-active
					<?php

				}
				?>"><?php echo esc_html__( 'Atributos del shortcode', 'show-post-location' ) ?></a>
			</h2>

			<?php	if ( null === $_GET['tab'] ) { ?>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'spl_settings' );
					do_settings_sections( 'spl-general-settings' );
					?>
				</form>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Init for page
	 *
	 * @return void
	 */
	public function page_init() {
		// General Settings.
		register_setting(
			'spl_settings',
			'spl_settings',
			array( $this, 'sanitize_fields' )
		);

		// ***************************************************** INIT CONFIGURATIONS *****************************************************
		add_settings_section(
			'spl_setting_section',
			__( '[spl_show_posts_by_location]', 'show-post-location' ),
			array( $this, 'spl_section_init' ),
			'spl-general-settings'
		);

	}

	/**
	 * Sanitize fields before saves in DB
	 *
	 * @param array $input Input fields.
	 * @return array
	 */
	public function sanitize_fields( $input ) {
		$sanitary_values = array();

		$settings_keys = array(
			// INIT CONFIGURATIONS
			'',

		);

		foreach ( $settings_keys as $key ) {
			if ( isset( $input[ $key ] ) ) {
				$sanitary_values[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}

		return $sanitary_values;
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function spl_section_init() {
		echo '<p class="spl-setting-description-shortcode">' . esc_html__( 'Usa este shortcode para mostrar las entradas filtradas por localización en cualquier página o entrada. Aquí están los parámetros que se pueden configurar del shortcode.') . '</p>';

		echo '<div class="spl-setting-shortcode-attr">';
		echo '<p class="shortcode">[spl_show_posts_by_location num_posts=-1, order="ASC", slug_cats="", columns=0, link_post="true", show_date="true", show_author="true", filer="region", slug_posts="" ]</p>';

		echo '<div class="attributes">';
		echo '<p class="attr"><span>num_posts</span> ' . esc_html__( 'este argumento indica el número de entradas que se van a mostrar, por defecto su valor es 
		-1 para mostrar todas las entradas.', 'show-post-location' ) . '</p>';
		echo '<p class="attr"><span>order</span> ' . esc_html__( 'este argumento indica el order en el que se van a mostrar las entradas, por defecto su valor es ASC para mostrar las entradas en orden ascendente pero permite también DESC.', 'show-post-location' ) . '</p>';
		echo '<p class="attr"><span>slug_cats</span> ' . esc_html__( 'este argumento indica el slug de las categorías de las entradas que se van a mostrar, por defecto las muestra todas, para mostrar categorías solo tenemos que indicar los slugs separados por comas. Ejemplo: slug_cats="slu-1,slug2"', 'show-post-location' ) . '</p>';
		echo '<p class="attr"><span>columns</span> ' . esc_html__( 'este argumento indica el número de columnas que se van a mostrar, por defecto su valor es 0, para mostrar más columnas solo tenemos que poner un número.', 'show-post-location' ) . '</p>';
		echo '<p class="attr"><span>link_post</span> ' . esc_html__( 'este argumento indica si queremos mostrar el link al post o no, por defecto su valor es true, si queremos que no se muestre el link lo pondremos a false.', 'show-post-location' ) . '</p>';
		echo '<p class="attr"><span>show_date</span> ' . esc_html__( 'este argumento indica si queremos mostrar la fecha de publicación de la entrada, por defecto está en true si queremos que no se muestre lo pondremos en false.', 'show-post-location' ) . '</p>';
		echo '<p class="attr"><span>show_author</span> ' . esc_html__( 'este argumento indica si queremos mostrar el nombre del autor de la entrada, por defecto está en true si queremos que no se muestre lo pondremos en false.', 'show-post-location' ) . '</p>';
		echo '<p class="attr"><span>filer</span> ' . esc_html__( 'este argumento indica si queremos filtrar las entradas por región o ciudad, por defecto está region si queremos filtrar por ciudad pondremos city.', 'show-post-location' ) . '</p>';
		echo '<p class="attr"><span>slug_posts</span> ' . esc_html__( 'este argumento indica el slug de las entradas que vamos a mostrar separado por comas. Ejemplo: slug_posts="slug-post-1,slug-post-2"', 'show-post-location' ) . '</p>';
		echo '<p class="attr"><span>idiom</span> ' . esc_html__( 'este argumento indica el idioma en el que viene la respuesta del servidor por defecto es "es" (España), pero permite los siguientes idiomas: en (Inglés), de (Alemán), pt-BR (Portugués), fr (Francés), ja (Japones), zh-CN (Chino) y ru (Ruso).', 'show-post-location' ) . '</p>';
		echo '</div>';

		echo '</div>';
	}

	/**
	 * Custom CSS for admin
	 *
	 * @return void
	 */
	public function custom_css() {
		// Free Version.
		echo '
			<style>
			.spl-setting-description-shortcode {
				font-weight: bold;
			}
			
			.spl-setting-shortcode-attr .shortcode, .spl-setting-shortcode-attr .attributes p span {
				padding: 3px 5px 2px 5px;
				background: #f0f0f1;
				background: rgba(0,0,0,.07);
				font-size: 13px;
				width: fit-content;
			}

			.spl-setting-shortcode-attr .attributes p {
				margin-left: 60px;
			}
			';
		echo '</style>';
	}

}
if ( is_admin() ) {
	$spl = new SPL_Settings();
}