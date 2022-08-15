<?php
/**
 *
 * Setup Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

use Pluginbazar\Client;
use Pluginbazar\Utils;

if ( ! class_exists( 'PBSettings' ) ) {
	class PBSettings {

		// Default constants
		public static $premium = true;
		public static $version = '2.2.8';
		public static $dir = '';
		public static $url = '';
		public static $css = '';
		public static $file = '';
		public static $enqueue = false;
		public static $webfonts = array();
		public static $subsets = array();
		public static $inited = array();
		public static $fields = array();
		public static $args = array(
			'admin_options'     => array(),
			'customize_options' => array(),
			'metabox_options'   => array(),
			'nav_menu_options'  => array(),
			'profile_options'   => array(),
			'taxonomy_options'  => array(),
			'widget_options'    => array(),
			'comment_options'   => array(),
			'shortcode_options' => array(),
		);

		// Shortcode instances
		public static $shortcode_instances = array();

		private static $instance = null;

		public static function init( Client $client, $file = __FILE__ ) {

			// Set file constant
			self::$file    = $file;
			self::$version = $client->plugin_version;

			// Set constants
			self::constants();

			// Include files
			self::includes();

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		// Initalize
		public function __construct() {

			// Init action
			do_action( 'pb_settings_init' );

			// Setup textdomain
			self::textdomain();

			add_action( 'after_setup_theme', array( 'PBSettings', 'setup' ) );
			add_action( 'init', array( 'PBSettings', 'setup' ) );
			add_action( 'switch_theme', array( 'PBSettings', 'setup' ) );
			add_action( 'admin_enqueue_scripts', array( 'PBSettings', 'add_admin_enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( 'PBSettings', 'add_typography_enqueue_styles' ), 80 );
			add_action( 'wp_head', array( 'PBSettings', 'add_custom_css' ), 80 );
			add_filter( 'admin_body_class', array( 'PBSettings', 'add_admin_body_class' ) );

		}

		// Setup frameworks
		public static function setup() {

			// Welcome
			self::include_plugin_file( 'views/welcome.php' );

			// Setup admin option framework
			$params = array();
			if ( class_exists( 'PBSettings_Options' ) && ! empty( self::$args['admin_options'] ) ) {
				foreach ( self::$args['admin_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						PBSettings_Options::instance( $key, $params );

						if ( ! empty( $value['show_in_customizer'] ) ) {
							$value['output_css']                     = false;
							$value['enqueue_webfont']                = false;
							self::$args['customize_options'][ $key ] = $value;
							self::$inited[ $key ]                    = null;
						}

					}
				}
			}

			// Setup customize option framework
			$params = array();
			if ( class_exists( 'PBSettings_Customize_Options' ) && ! empty( self::$args['customize_options'] ) ) {
				foreach ( self::$args['customize_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						PBSettings_Customize_Options::instance( $key, $params );

					}
				}
			}

			// Setup metabox option framework
			$params = array();
			if ( class_exists( 'PBSettings_Metabox' ) && ! empty( self::$args['metabox_options'] ) ) {
				foreach ( self::$args['metabox_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						PBSettings_Metabox::instance( $key, $params );

					}
				}
			}

			// Setup nav menu option framework
			$params = array();
			if ( class_exists( 'PBSettings_Nav_Menu_Options' ) && ! empty( self::$args['nav_menu_options'] ) ) {
				foreach ( self::$args['nav_menu_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						PBSettings_Nav_Menu_Options::instance( $key, $params );

					}
				}
			}

			// Setup profile option framework
			$params = array();
			if ( class_exists( 'PBSettings_Profile_Options' ) && ! empty( self::$args['profile_options'] ) ) {
				foreach ( self::$args['profile_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						PBSettings_Profile_Options::instance( $key, $params );

					}
				}
			}

			// Setup taxonomy option framework
			$params = array();
			if ( class_exists( 'PBSettings_Taxonomy_Options' ) && ! empty( self::$args['taxonomy_options'] ) ) {
				$taxonomy = ( isset( $_GET['taxonomy'] ) ) ? sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) ) : '';
				foreach ( self::$args['taxonomy_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						PBSettings_Taxonomy_Options::instance( $key, $params );

					}
				}
			}

			// Setup widget option framework
			if ( class_exists( 'PBSettings_Widget' ) && class_exists( 'WP_Widget_Factory' ) && ! empty( self::$args['widget_options'] ) ) {
				$wp_widget_factory = new WP_Widget_Factory();
				global $wp_widget_factory;
				foreach ( self::$args['widget_options'] as $key => $value ) {
					if ( ! isset( self::$inited[ $key ] ) ) {

						self::$inited[ $key ] = true;
						$wp_widget_factory->register( PBSettings_Widget::instance( $key, $value ) );

					}
				}
			}

			// Setup comment option framework
			$params = array();
			if ( class_exists( 'PBSettings_Comment_Metabox' ) && ! empty( self::$args['comment_options'] ) ) {
				foreach ( self::$args['comment_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						PBSettings_Comment_Metabox::instance( $key, $params );

					}
				}
			}

			// Setup shortcode option framework
			$params = array();
			if ( class_exists( 'PBSettings_Shortcoder' ) && ! empty( self::$args['shortcode_options'] ) ) {
				foreach ( self::$args['shortcode_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						PBSettings_Shortcoder::instance( $key, $params );

					}
				}

				// Once editor setup for gutenberg and media buttons
				if ( class_exists( 'PBSettings_Shortcoder' ) && ! empty( self::$shortcode_instances ) ) {
					foreach ( self::$shortcode_instances as $instance ) {
						if ( ! empty( $instance['show_in_editor'] ) ) {
							PBSettings_Shortcoder::once_editor_setup();
							break;
						}
					}
				}

			}

			do_action( 'pb_settings_loaded' );
		}

		/**
		 * Generate settings page
		 *
		 * @param $id
		 * @param array $args
		 * @param array $sections
		 */
		public static function createSettingsPage( $id, $args = array(), $sections = array() ) {

			self::createOptions( $id, $args );

			foreach ( $sections as $section_index => $pages ) {

				self::createSection( $id, array(
					'id'    => 'primary_' . $section_index,
					'title' => Utils::get_args_option( 'title', $pages ),
					'icon'  => Utils::get_args_option( 'icon', $pages ),
				) );

				foreach ( Utils::get_args_option( 'sections', $pages, array() ) as $section ) {
					self::createSection( $id, array_merge(
						array(
							'parent' => 'primary_' . $section_index,
							'icon'   => Utils::get_args_option( 'icon', $section, 'fa fa-hand-o-right' ),
						), $section ) );
				}
			}

			// Adding custom data
			self::$args['pro_url']             = isset( $args['pro_url'] ) ? $args['pro_url'] : '';
			self::$args['product_url']         = isset( $args['product_url'] ) ? $args['product_url'] : '';
			self::$args['product_version_pro'] = isset( $args['product_version_pro'] ) ? $args['product_version_pro'] : '';
		}

		// Create options
		public static function createOptions( $id, $args = array() ) {
			self::$args['admin_options'][ $id ] = $args;
		}

		// Create customize options
		public static function createCustomizeOptions( $id, $args = array() ) {
			self::$args['customize_options'][ $id ] = $args;
		}

		// Create metabox options
		public static function createMetabox( $id, $args = array() ) {
			self::$args['metabox_options'][ $id ] = wp_parse_args( $args, array( 'class' => 'pbsettings-meta-box' ) );
		}

		// Create menu options
		public static function createNavMenuOptions( $id, $args = array() ) {
			self::$args['nav_menu_options'][ $id ] = $args;
		}

		// Create shortcoder options
		public static function createShortcoder( $id, $args = array() ) {
			self::$args['shortcode_options'][ $id ] = $args;
		}

		// Create taxonomy options
		public static function createTaxonomyOptions( $id, $args = array() ) {
			self::$args['taxonomy_options'][ $id ] = $args;
		}

		// Create profile options
		public static function createProfileOptions( $id, $args = array() ) {
			self::$args['profile_options'][ $id ] = $args;
		}

		// Create widget
		public static function createWidget( $id, $args = array() ) {
			self::$args['widget_options'][ $id ] = $args;
			self::set_used_fields( $args );
		}

		// Create comment metabox
		public static function createCommentMetabox( $id, $args = array() ) {
			self::$args['comment_options'][ $id ] = $args;
		}

		// Create section
		public static function createSection( $id, $sections ) {
			self::$args['sections'][ $id ][] = $sections;
			self::set_used_fields( $sections );
		}

		// Set directory constants
		public static function constants() {

			// We need this path-finder code for set URL of framework
			$dirname        = str_replace( '//', '/', wp_normalize_path( dirname( dirname( self::$file ) ) ) );
			$theme_dir      = str_replace( '//', '/', wp_normalize_path( get_parent_theme_file_path() ) );
			$plugin_dir     = str_replace( '//', '/', wp_normalize_path( WP_PLUGIN_DIR ) );
			$plugin_dir     = str_replace( '/opt/bitnami', '/bitnami', $plugin_dir );
			$located_plugin = ( preg_match( '#' . self::sanitize_dirname( $plugin_dir ) . '#', self::sanitize_dirname( $dirname ) ) ) ? true : false;
			$directory      = ( $located_plugin ) ? $plugin_dir : $theme_dir;
			$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_parent_theme_file_uri();
			$foldername     = str_replace( $directory, '', $dirname );
			$protocol_uri   = ( is_ssl() ) ? 'https' : 'http';
			$directory_uri  = set_url_scheme( $directory_uri, $protocol_uri );

			self::$dir = $dirname;
			self::$url = $directory_uri . $foldername;

		}

		// Include file helper
		public static function include_plugin_file( $file, $load = true ) {

			$path     = '';
			$file     = ltrim( $file, '/' );
			$override = apply_filters( 'pb_settings_override', 'pbsettings-override' );

			if ( file_exists( get_parent_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_parent_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( get_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( self::$dir . '/' . $override . '/' . $file ) ) {
				$path = self::$dir . '/' . $override . '/' . $file;
			} elseif ( file_exists( self::$dir . '/' . $file ) ) {
				$path = self::$dir . '/' . $file;
			}

			if ( ! empty( $path ) && ! empty( $file ) && $load ) {

				global $wp_query;

				if ( is_object( $wp_query ) && function_exists( 'load_template' ) ) {

					load_template( $path, true );

				} else {

					require_once( $path );

				}

			} else {

				return self::$dir . '/' . $file;

			}

		}

		// Is active plugin helper
		public static function is_active_plugin( $file = '' ) {
			return in_array( $file, (array) get_option( 'active_plugins', array() ) );
		}

		// Sanitize dirname
		public static function sanitize_dirname( $dirname ) {
			return preg_replace( '/[^A-Za-z]/', '', $dirname );
		}

		// Set url constant
		public static function include_plugin_url( $file ) {
			return esc_url( self::$url ) . '/' . ltrim( $file, '/' );
		}

		// Include files
		public static function includes() {

			// Include common functions
			self::include_plugin_file( 'functions/actions.php' );
			self::include_plugin_file( 'functions/helpers.php' );
			self::include_plugin_file( 'functions/sanitize.php' );
			self::include_plugin_file( 'functions/validate.php' );

			// Include free version classes
			self::include_plugin_file( 'classes/abstract.class.php' );
			self::include_plugin_file( 'classes/fields.class.php' );
			self::include_plugin_file( 'classes/admin-options.class.php' );

			// Include premium version classes
			if ( self::$premium ) {
				self::include_plugin_file( 'classes/customize-options.class.php' );
				self::include_plugin_file( 'classes/metabox-options.class.php' );
				self::include_plugin_file( 'classes/nav-menu-options.class.php' );
				self::include_plugin_file( 'classes/profile-options.class.php' );
				self::include_plugin_file( 'classes/shortcode-options.class.php' );
				self::include_plugin_file( 'classes/taxonomy-options.class.php' );
				self::include_plugin_file( 'classes/widget-options.class.php' );
				self::include_plugin_file( 'classes/comment-options.class.php' );
			}

			// Include all framework fields
			$fields = apply_filters( 'pb_settings_fields', array(
				'accordion',
				'background',
				'backup',
				'border',
				'button_set',
				'callback',
				'checkbox',
				'code_editor',
				'color',
				'color_group',
				'content',
				'date',
				'datetime',
				'dimensions',
				'fieldset',
				'gallery',
				'group',
				'heading',
				'icon',
				'image_select',
				'link',
				'link_color',
				'map',
				'media',
				'notice',
				'number',
				'palette',
				'radio',
				'repeater',
				'select',
				'slider',
				'sortable',
				'sorter',
				'spacing',
				'spinner',
				'subheading',
				'submessage',
				'switcher',
				'tabbed',
				'text',
				'textarea',
				'typography',
				'upload',
				'wp_editor',
			) );

			if ( ! empty( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( ! class_exists( 'PBSettings_Field_' . $field ) && class_exists( 'PBSettings_Fields' ) ) {
						self::include_plugin_file( 'fields/' . $field . '/' . $field . '.php' );
					}
				}
			}

		}

		// Setup textdomain
		public static function textdomain() {
			load_textdomain( 'pb_settings', self::$dir . '/languages/' . get_locale() . '.mo' );
		}

		// Set all of used fields
		public static function set_used_fields( $sections ) {

			if ( ! empty( $sections['fields'] ) ) {

				foreach ( $sections['fields'] as $field ) {

					if ( ! empty( $field['fields'] ) ) {
						self::set_used_fields( $field );
					}

					if ( ! empty( $field['tabs'] ) ) {
						self::set_used_fields( array( 'fields' => $field['tabs'] ) );
					}

					if ( ! empty( $field['accordions'] ) ) {
						self::set_used_fields( array( 'fields' => $field['accordions'] ) );
					}

					if ( ! empty( $field['type'] ) ) {
						self::$fields[ $field['type'] ] = $field;
					}

				}

			}

		}

		// Enqueue admin and fields styles and scripts
		public static function add_admin_enqueue_scripts() {

			if ( ! self::$enqueue ) {

				// Loads scripts and styles only when needed
				$wpscreen = get_current_screen();

				if ( ! empty( self::$args['admin_options'] ) ) {
					foreach ( self::$args['admin_options'] as $argument ) {
						if ( substr( $wpscreen->id, - strlen( $argument['menu_slug'] ) ) === $argument['menu_slug'] ) {
							self::$enqueue = true;
						}
					}
				}

				if ( ! empty( self::$args['metabox_options'] ) ) {
					foreach ( self::$args['metabox_options'] as $argument ) {
						if ( in_array( $wpscreen->post_type, (array) $argument['post_type'] ) ) {
							self::$enqueue = true;
						}
					}
				}

				if ( ! empty( self::$args['taxonomy_options'] ) ) {
					foreach ( self::$args['taxonomy_options'] as $argument ) {
						if ( in_array( $wpscreen->taxonomy, (array) $argument['taxonomy'] ) ) {
							self::$enqueue = true;
						}
					}
				}

				if ( ! empty( self::$shortcode_instances ) ) {
					foreach ( self::$shortcode_instances as $argument ) {
						if ( ( $argument['show_in_editor'] && $wpscreen->base === 'post' ) || $argument['show_in_custom'] ) {
							self::$enqueue = true;
						}
					}
				}

				if ( ! empty( self::$args['widget_options'] ) && ( $wpscreen->id === 'widgets' || $wpscreen->id === 'customize' ) ) {
					self::$enqueue = true;
				}

				if ( ! empty( self::$args['customize_options'] ) && $wpscreen->id === 'customize' ) {
					self::$enqueue = true;
				}

				if ( ! empty( self::$args['nav_menu_options'] ) && $wpscreen->id === 'nav-menus' ) {
					self::$enqueue = true;
				}

				if ( ! empty( self::$args['profile_options'] ) && ( $wpscreen->id === 'profile' || $wpscreen->id === 'user-edit' ) ) {
					self::$enqueue = true;
				}

				if ( ! empty( self::$args['comment_options'] ) && $wpscreen->id === 'comment' ) {
					self::$enqueue = true;
				}

				if ( $wpscreen->id === 'tools_page_pbsettings-welcome' ) {
					self::$enqueue = true;
				}

			}

			if ( ! apply_filters( 'pb_settings_enqueue_assets', self::$enqueue ) ) {
				return;
			}

			// Admin utilities
			wp_enqueue_media();

			// Wp color picker
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			// Font awesome 4 and 5 loader
			if ( apply_filters( 'pb_settings_fa4', false ) ) {
				wp_enqueue_style( 'pbsettings-fa', 'https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css', array(), '4.7.0', 'all' );
			} else {
				wp_enqueue_style( 'pbsettings-fa5', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css', array(), '5.15.5', 'all' );
				wp_enqueue_style( 'pbsettings-fa5-v4-shims', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/v4-shims.min.css', array(), '5.15.5', 'all' );
			}

			$version = defined( 'PB_CLEAN_CACHE' ) && PB_CLEAN_CACHE ? time() : self::$version;


			// Main style
			wp_enqueue_style( 'pb_settings', self::include_plugin_url( 'assets/css/style.css' ), array(), $version );

			// Main RTL styles
			if ( is_rtl() ) {
				wp_enqueue_style( 'pbsettings-rtl', self::include_plugin_url( 'assets/css/style-rtl.css' ), array(), $version, 'all' );
			}

			// Main scripts
			wp_enqueue_script( 'pbsettings-plugins', self::include_plugin_url( 'assets/js/plugins.js' ), array(), $version, true );
			wp_enqueue_script( 'pb_settings', self::include_plugin_url( 'assets/js/main.js' ), array( 'pbsettings-plugins' ), $version, true );

			// Main variables
			wp_localize_script( 'pb_settings', 'pb_settings_vars', array(
				'color_palette' => apply_filters( 'pb_settings_color_palette', array() ),
				'i18n'          => array(
					'confirm'         => esc_html__( 'Are you sure?' ),
					'typing_text'     => esc_html__( 'Please enter %s or more characters' ),
					'searching_text'  => esc_html__( 'Searching...' ),
					'no_results_text' => esc_html__( 'No results found.' ),
				),
			) );

			// Enqueue fields scripts and styles
			$enqueued = array();

			if ( ! empty( self::$fields ) ) {
				foreach ( self::$fields as $field ) {
					if ( ! empty( $field['type'] ) ) {
						$classname = 'PBSettings_Field_' . $field['type'];
						if ( class_exists( $classname ) && method_exists( $classname, 'enqueue' ) ) {
							$instance = new $classname( $field );
							if ( method_exists( $classname, 'enqueue' ) ) {
								$instance->enqueue();
							}
							unset( $instance );
						}
					}
				}
			}

			do_action( 'pb_settings_enqueue' );

		}

		// Add typography enqueue styles to front page
		public static function add_typography_enqueue_styles() {

			if ( ! empty( self::$webfonts ) ) {

				if ( ! empty( self::$webfonts['enqueue'] ) ) {

					$query = array();
					$fonts = array();

					foreach ( self::$webfonts['enqueue'] as $family => $styles ) {
						$fonts[] = $family . ( ( ! empty( $styles ) ) ? ':' . implode( ',', $styles ) : '' );
					}

					if ( ! empty( $fonts ) ) {
						$query['family'] = implode( '%7C', $fonts );
					}

					if ( ! empty( self::$subsets ) ) {
						$query['subset'] = implode( ',', self::$subsets );
					}

					$query['display'] = 'swap';

					wp_enqueue_style( 'pbsettings-google-web-fonts', esc_url( add_query_arg( $query, '//fonts.googleapis.com/css' ) ), array(), null );

				}

				if ( ! empty( self::$webfonts['async'] ) ) {

					$fonts = array();

					foreach ( self::$webfonts['async'] as $family => $styles ) {
						$fonts[] = $family . ( ( ! empty( $styles ) ) ? ':' . implode( ',', $styles ) : '' );
					}

					wp_enqueue_script( 'pbsettings-google-web-fonts', esc_url( '//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js' ), array(), null );

					wp_localize_script( 'pbsettings-google-web-fonts', 'WebFontConfig', array( 'google' => array( 'families' => $fonts ) ) );

				}

			}

		}

		// Add admin body class
		public static function add_admin_body_class( $classes ) {

			if ( apply_filters( 'pb_settings_fa4', false ) ) {
				$classes .= 'pbsettings-fa5-shims';
			}

			return $classes;

		}

		// Add custom css to front page
		public static function add_custom_css() {

			if ( ! empty( self::$css ) ) {
				echo '<style type="text/css">' . wp_strip_all_tags( self::$css ) . '</style>';
			}

		}

		// Add a new framework field
		public static function field( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {

			// Check for unallow fields
			if ( ! empty( $field['_notice'] ) ) {

				$field_type = $field['type'];

				$field            = array();
				$field['content'] = esc_html__( 'Oops! Not allowed.' ) . ' <strong>(' . $field_type . ')</strong>';
				$field['type']    = 'notice';
				$field['style']   = 'danger';

			}

			$depend           = '';
			$visible          = '';
			$unique           = ( ! empty( $unique ) ) ? $unique : '';
			$class            = ( ! empty( $field['class'] ) ) ? ' ' . esc_attr( $field['class'] ) : '';
			$is_pseudo        = ( ! empty( $field['pseudo'] ) ) ? ' pbsettings-pseudo-field' : '';
			$field_type       = ( ! empty( $field['type'] ) ) ? esc_attr( $field['type'] ) : '';
			$availability     = ( ! empty( $field['availability'] ) ) ? esc_attr( $field['availability'] ) : '';
			$has_availability = empty( $availability ) ? '' : ' availability ' . $availability;

			if ( ! empty( $field['dependency'] ) ) {
				$dependency = $field['dependency'];
				if ( is_array( $dependency[0] ) ) {
					$data_controller = implode( '|', array_column( $dependency, 0 ) );
					$data_condition  = implode( '|', array_column( $dependency, 1 ) );
					$data_value      = implode( '|', array_column( $dependency, 2 ) );
					$data_global     = implode( '|', array_column( $dependency, 3 ) );
					$depend_visible  = implode( '|', array_column( $dependency, 4 ) );
				} else {
					$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
					$data_condition  = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
					$data_value      = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
					$data_global     = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
					$depend_visible  = ( ! empty( $dependency[4] ) ) ? $dependency[4] : '';
				}

				$depend .= ' data-controller="' . esc_attr( $data_controller ) . '"';
				$depend .= ' data-condition="' . esc_attr( $data_condition ) . '"';
				$depend .= ' data-value="' . esc_attr( $data_value ) . '"';
				$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

				$visible = ( ! empty( $depend_visible ) ) ? ' pbsettings-depend-visible' : ' pbsettings-depend-hidden';
			}

			// These attributes has been sanitized above.
			echo '<div class="pbsettings-field pbsettings-field-' . esc_attr( $field_type . $is_pseudo . $class . $visible . $has_availability ) . '"' . $depend . '>';

			if ( ! empty( $field_type ) ) {

				if ( ! empty( $field['title'] ) ) {
					echo '<div class="pbsettings-title">';
					echo '<h4>' . esc_html( $field['title'] ) . '</h4>';
					echo ( ! empty( $field['subtitle'] ) ) ? '<div class="pbsettings-subtitle-text">' . esc_html( $field['subtitle'] ) . '</div>' : '';
					echo '</div>';
				}

				echo ( ! empty( $field['title'] ) ) ? '<div class="pbsettings-fieldset">' : '';

				$value = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
				$value = ( isset( $field['value'] ) ) ? $field['value'] : $value;

				$classname = 'PBSettings_Field_' . $field_type;

				if ( class_exists( $classname ) ) {
					$instance = new $classname( $field, $value, $unique, $where, $parent );
					$instance->render();

					do_action( 'PBSettings/after_field/field_' . $field['id'] );
				} else {
					echo '<p>' . esc_html__( 'Field not found!' ) . '</p>';
				}

			} else {
				echo '<p>' . esc_html__( 'Field not found!' ) . '</p>';
			}

			echo ( ! empty( $field['title'] ) ) ? '</div>' : '';

			if ( ! empty( $has_availability ) ) {

				$notice_url = '';

				if ( 'pro' == $availability ) {
					$notice_url = isset( self::$args['pro_url'] ) ? self::$args['pro_url'] : '';
				}

				echo '<a class="pbsettings-availability-notice" href="' . esc_url( $notice_url ) . '" target="_blank">' . esc_html( $availability ) . '</a>';
			}

			echo '<div class="clear"></div>';
			echo '</div>';
		}

	}

}

//PBSettings::init();
