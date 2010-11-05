<?php
/*
 * Database-Manager
 * @see http://codex.wordpress.org/Function_Reference/wpdb_Class
 * @see http://codex.wordpress.org/Creating_Tables_with_Plugins
 */
class ScreenLetsWipeDbm {

	/**
	 * Wordpress Database Object
	 * @var object
	 */
	public $db;

	/**
	 * Plugin Database-Schema Version
	 * @param string
	 */
	private $dbVersion = '1.0';

	/**
	 * Plugin-Tables
	 * @param array
	 */
	public $dbTables;

	public function  __construct() {
		global $wpdb;
		$this->db = $wpdb;
		$this->dbTables = array(
			'events' => $this->db->prefix.'lets_wipe_events',
			'chars' => $this->db->prefix.'lets_wipe_chars',
			'raids' => $this->db->prefix.'lets_wipe_raids',
			'char2raid' => $this->db->prefix.'lets_wipe_char2raid',
			'items' => $this->db->prefix.'lets_wipe_items',
			'item2raid' => $this->db->prefix.'lets_wipe_item2raid',
			'boss_instance' => $this->db->prefix.'lets_wipe_boss_instance',
			'boss2raid' => $this->db->prefix.'lets_wipe_boss2raid'
		);
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	}

	/**
	 * get the db version
	 * @return string
	 */
	public function getDbm() {
		return $this->dbVersion;
	}

	/**
	 * Install Plugin Schema
	 */
	public function install() {
		// events
		if ($this->db->get_var("SHOW TABLES LIKE '{$this->dbTables['events']}'") != $this->dbTables['events']) {
			$sql = "CREATE TABLE IF NOT EXISTS {$this->dbTables['events']} (
						id INT NOT NULL AUTO_INCREMENT,
						event_name VARCHAR(255) NOT NULL,
						event_status TINYINT NOT NULL DEFAULT 1,
						event_created DATETIME NOT NULL,
						event_created_by INT NOT NULL,
						event_modified DATETIME NULL,
						event_modified_by INT NULL,
						PRIMARY KEY (id)
					) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
			dbDelta($sql);
			$this->db->query("CREATE INDEX fk_{$this->dbTables['events']}_modified ON {$this->dbTables['events']} (event_modified_by ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['events']}_created ON {$this->dbTables['events']} (event_created_by ASC)");
		}

		// chars
		if ($this->db->get_var("SHOW TABLES LIKE '{$this->dbTables['chars']}'") != $this->dbTables['chars']) {
			$sql = "CREATE TABLE IF NOT EXISTS {$this->dbTables['chars']} (
						id INT NOT NULL AUTO_INCREMENT,
						user_id INT NOT NULL,
						char_name VARCHAR(255) NOT NULL,
						char_status TINYINT NOT NULL DEFAULT 1,
						char_gender TINYINT NOT NULL,
						char_race TINYINT NOT NULL,
						char_class TINYINT NOT NULL,
						char_created DATETIME NOT NULL,
						char_created_by INT NOT NULL,
						char_modified DATETIME NULL,
						char_modified_by INT NULL,
						PRIMARY KEY (id)
					) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
			dbDelta($sql);
			$this->db->query("CREATE INDEX fk_{$this->dbTables['chars']}_users ON {$this->dbTables['chars']} (user_id ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['chars']}_created ON {$this->dbTables['chars']} (char_created_by ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['chars']}_modified ON {$this->dbTables['chars']} (char_modified_by ASC)");
		}

		// raids
		if ($this->db->get_var("SHOW TABLES LIKE '{$this->dbTables['raids']}'") != $this->dbTables['raids']) {
			$sql = "CREATE TABLE IF NOT EXISTS {$this->dbTables['raids']} (
						id INT NOT NULL AUTO_INCREMENT,
						event_id INT NOT NULL,
						raid_status TINYINT NOT NULL DEFAULT 1,
						raid_start DATETIME NOT NULL,
						raid_end DATETIME NOT NULL,
						raid_invite DATETIME NOT NULL,
						raid_leader INT NOT NULL,
						raid_info TEXT NULL,
						raid_created DATETIME NOT NULL,
						raid_created_by INT NOT NULL,
						raid_modified DATETIME NULL,
						raid_modified_by INT NULL,
						PRIMARY KEY (id)
					) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
			dbDelta($sql);
			$this->db->query("CREATE INDEX fk_{$this->dbTables['raids']}_events ON {$this->dbTables['raids']} (event_id ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['raids']}_chars ON {$this->dbTables['raids']} (raid_leader ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['raids']}_modified ON {$this->dbTables['raids']} (raid_modified_by ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['raids']}_created ON {$this->dbTables['raids']} (raid_created_by ASC)");
		}

		// char2raid
		if ($this->db->get_var("SHOW TABLES LIKE '{$this->dbTables['char2raid']}'") != $this->dbTables['char2raid']) {
			$sql = "CREATE TABLE IF NOT EXISTS {$this->dbTables['char2raid']} (
						char_id INT NOT NULL,
						raid_id INT NOT NULL,
						char_raid_status TINYINT NOT NULL DEFAULT 1,
						char_comment TEXT NULL,
						char_raid_created DATETIME NOT NULL,
						char_raid_created_by INT NOT NULL,
						char_raid_modified DATETIME NULL,
						char_raid_modified_by INT NULL,
						PRIMARY KEY (char_id, raid_id)
					) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
			dbDelta($sql);
			$this->db->query("CREATE INDEX fk_{$this->dbTables['char2raid']}_chars ON {$this->dbTables['char2raid']} (char_id ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['char2raid']}_raids ON {$this->dbTables['char2raid']} (raid_id ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['char2raid']}_modified ON {$this->dbTables['char2raid']} (char_raid_modified_by ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['char2raid']}_created ON {$this->dbTables['char2raid']} (char_raid_created_by ASC)");
		}

		// items
		if ($this->db->get_var("SHOW TABLES LIKE '{$this->dbTables['items']}'") != $this->dbTables['items']) {
			$sql = "CREATE TABLE IF NOT EXISTS {$this->dbTables['items']} (
						id INT NOT NULL AUTO_INCREMENT,
						source_id VARCHAR(255) NOT NULL,
						item_name VARCHAR(255) NULL,
						PRIMARY KEY (id)
					) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
			dbDelta($sql);
		}

		// item2raid
		if ($this->db->get_var("SHOW TABLES LIKE '{$this->dbTables['item2raid']}'") != $this->dbTables['item2raid']) {
			$sql = "CREATE TABLE IF NOT EXISTS {$this->dbTables['item2raid']} (
						item_id INT NOT NULL,
						raid_id INT NOT NULL,
						char_id INT NOT NULL,
						PRIMARY KEY (item_id, raid_id, char_id)
					) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
			dbDelta($sql);
			$this->db->query("CREATE INDEX fk_{$this->dbTables['item2raid']}_items ON {$this->dbTables['item2raid']} (item_id ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['item2raid']}_raids ON {$this->dbTables['item2raid']} (raid_id ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['item2raid']}_chars ON {$this->dbTables['item2raid']} (char_id ASC)");
		}

		// boss_instance
		if ($this->db->get_var("SHOW TABLES LIKE '{$this->dbTables['boss_instance']}'") != $this->dbTables['boss_instance']) {
			$sql = "CREATE TABLE IF NOT EXISTS {$this->dbTables['boss_instance']} (
						id INT NOT NULL AUTO_INCREMENT,
						boss_instance_id INT NOT NULL DEFAULT 0,
						boss_name VARCHAR(255) NOT NULL,
						boss_alias VARCHAR(255) NOT NULL,
						PRIMARY KEY (id)
					) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
			dbDelta($sql);
		}

		// boss_instance
		if ($this->db->get_var("SHOW TABLES LIKE '{$this->dbTables['boss2raid']}'") != $this->dbTables['boss2raid']) {
			$sql = "CREATE TABLE IF NOT EXISTS {$this->dbTables['boss2raid']} (
						boss_id INT NOT NULL,
						raid_id TINYINT NOT NULL,
						boss_raid_status TINYINT NOT NULL,
						boss_level TINYINT NOT NULL,
						PRIMARY KEY (boss_id, raid_id)
					) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
			dbDelta($sql);
			$this->db->query("CREATE INDEX fk_{$this->dbTables['boss2raid']}_boss ON {$this->dbTables['boss2raid']} (boss_id ASC)");
			$this->db->query("CREATE INDEX fk_{$this->dbTables['boss2raid']}_raids ON {$this->dbTables['boss2raid']} (raid_id ASC)");
		}
	}

	/**
	 * update
	 * @param string $dbVersion current installed db version
	 * @return void
	 */
	public function update($dbVersion) {
		
	}

	/**
	 * Uninstall Plugin Schema
	 */
	public function uninstall() {
		foreach ($this->dbTables as $table) $this->db->query("DROP TABLE $table");
	}
}
?>