<?php
// Set Namespace
namespace WorthCommitting\Events\Utilities;

// Include class were extending.
use Gamajo_Template_Loader;

if ( ! defined( 'WPINC' ) ) { die; }

class TemplateLoader extends Gamajo_Template_Loader {
	/**
	 * Prefix for filter names.
	 *
	 * @var string
	 */
	protected $filter_prefix = 'ccevent';

	/**
	 * Directory name where templates should be found in the theme.
	 *
	 * @var string
	 */
	protected $theme_template_directory = 'ccevent';

	/**
	 * Current plugin's root directory.
	 *
	 * @var string
	 */
	protected $plugin_directory = CCEVENTS_PATH;

	/**
	 * Directory name of where the templates are stored in the plugin.
	 *
	 * @var string
	 */
	protected $plugin_template_directory = 'templates';
}