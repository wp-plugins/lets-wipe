<?php
/*
Plugin Name:	Lets Wipe
Plugin URI:		http://screennetz.de/develop/lets-wipe/
Description:	Dieses Plugin bietet einen kompletten Raidplaner für World of Warcraft.
Author:			tumichnix
Version:		0.1
Author URI:		http://screennetz.de/
*/

/*  Copyright 2010-2010 tumichnix (email: tumichnix at screennetz.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
class ScreenLetsWipe {

	/**
	 * Plugin key
	 * @var string
	 */
	const pluginKey = 'plugin-screen-lets-wipe';

	/**
	 * plugin path
	 * @var string
	 */
	private $pluginPath;

	/**
	 * Wordpress Database Object
	 * @var object
	 */
	public $db;

	/**
	 * Constructor
	 * @param object $wpdb
	 */
	public function  __construct() {
		global $wpdb;
		$this->db = $wpdb;
		$this->pluginPath = WP_PLUGIN_DIR.'/lets-wipe';
	}
	
	/**
	 * init the plugin
	 * @return void
	 */
	public function init() {
		

//		print('<pre>');
//		print_r( @get_defined_constants() );
//		print('</pre>');


		if (!is_admin()) {
						
		} else {
			add_action('admin_menu', array($this, 'menu'));
		}
	}
	
	/**
	 * activate this plugin
	 * @return void
	 */
	public function activation() {

		if (!class_exists('ScreenLetsWipeDbm')) {
			require_once $this->pluginPath.'/lib/dbm.php';
		}
		$screenLetsWipeDbm = new ScreenLetsWipeDbm();
		$screenLetsWipeDbm->install();

		add_option(self::pluginKey.'-dbm', '1.0', null, 'no');
		$currentDbVersion = get_option(self::pluginKey);
		if ($currentDbVersion != $screenLetsWipeDbm->getDbm()) $screenLetsWipeDbm->update($currentDbVersion);
		unset($screenLetsWipeDbm);
	}
	
	/**
	 * deactivate this plugin
	 * @return viod
	 */
	public function deactivation() {
		
	}

	/**
	 * uninstall this plugin
	 */
	public function uninstall() {
		if (!class_exists('ScreenLetsWipeDbm')) {
			require_once $this->pluginPath.'/lib/dbm.php';
		}
		$screenLetsWipeDbm = new ScreenLetsWipeDbm();
		$screenLetsWipeDbm->uninstall();
		unset($screenLetsWipeDbm);
		delete_option(self::pluginKey.'-dbm');
	}

	/**
	 * Index
	 */
	public function index() {
	
	}
	
	/**
	 * set the backend navigation link
	 * @return void
	 */
	public function menu() {
		add_options_page('Lets Wipe', 'Lets Wipe', 'manage_options', self::pluginKey, array($this, 'index'));
	}
}
$screenLetsWipe = new ScreenLetsWipe();
register_activation_hook(__FILE__, array($screenLetsWipe, 'activation'));
register_deactivation_hook(__FILE__, array($screenLetsWipe, 'deactivation'));
register_uninstall_hook(__FILE__, array($screenLetsWipe, 'uninstall'));
add_action('init', array($screenLetsWipe, 'init'));
?>
