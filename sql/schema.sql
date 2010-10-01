DROP TABLE IF EXISTS {TABLE_PREFIX}lw_events ;

CREATE  TABLE IF NOT EXISTS {TABLE_PREFIX}lw_events (
  id INT NOT NULL AUTO_INCREMENT,
  event_name VARCHAR(255) NOT NULL,
  event_status TINYINT NOT NULL DEFAULT 1,
  event_created DATETIME NOT NULL,
  event_created_by INT NOT NULL,
  event_modified DATETIME NULL,
  event_modified_by INT NULL,
  PRIMARY KEY (id)
) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;

CREATE INDEX fk_{TABLE_PREFIX}lw_events_modified ON {TABLE_PREFIX}lw_events (event_modified_by ASC);
CREATE INDEX fk_{TABLE_PREFIX}lw_events_created ON {TABLE_PREFIX}lw_events (event_created_by ASC);