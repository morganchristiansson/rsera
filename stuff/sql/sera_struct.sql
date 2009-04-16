CREATE TABLE keyphrases (
  zt_id int(11) NOT NULL auto_increment,
  ws_id int(11) NOT NULL default '0',
  langcode char(2) NOT NULL default 'en',
  keyphrase varchar(255) NOT NULL default '',
  datetag date NOT NULL default '0000-00-00',
  is_active tinyint(1) NOT NULL default '1',
  priority tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (zt_id),
  UNIQUE KEY zt_id (zt_id)
) TYPE=MyISAM COMMENT='All keyphrases for use with phpSERA';


CREATE TABLE reportrules (
  rr_id int(11) NOT NULL auto_increment,
  mt_id int(11) NOT NULL default '0',
  zt_id int(11) NOT NULL default '0',
  zm_id int(11) NOT NULL default '0',
  ranking tinyint(4) NOT NULL default '0',
  indexed_page varchar(255) default NULL,
  PRIMARY KEY  (rr_id),
  KEY mt_id (mt_id),
  KEY zt_id (zt_id),
  KEY zm_id (zm_id),
  KEY ranking (ranking)
) TYPE=MyISAM COMMENT='Analysis results from phpSERA';


CREATE TABLE reports (
  mt_id int(11) NOT NULL auto_increment,
  ws_id int(11) NOT NULL default '0',
  name varchar(50) NOT NULL default '',
  rankdate date NOT NULL default '0000-00-00',
  remark varchar(255) NOT NULL default '',
  PRIMARY KEY  (mt_id)
) TYPE=MyISAM COMMENT='Performed analysis by phpSERA';


CREATE TABLE searchengines (
  zm_id int(50) NOT NULL auto_increment,
  title varchar(50) NOT NULL default '',
  startkey varchar(255) NOT NULL default '',
  endkey varchar(255) NOT NULL default '',
  hit_separator varchar(255) NOT NULL default '',
  host varchar(50) NOT NULL default '',
  script varchar(50) NOT NULL default '',
  data varchar(255) NOT NULL default '',
  addurl varchar(255) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  langcode char(2) NOT NULL default '@@',
  noresult varchar(100) default NULL,
  datetag_insert date NOT NULL default '0000-00-00',
  datetag_lastupdate date NOT NULL default '0000-00-00',
  utf8_support tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (zm_id),
  UNIQUE KEY title (title)
) TYPE=MyISAM COMMENT=' All searchengines for use with phpSERA';


CREATE TABLE websites (
  ws_id int(11) NOT NULL auto_increment,
  url varchar(100) NOT NULL default '',
  datetag date NOT NULL default '0000-00-00',
  PRIMARY KEY  (ws_id),
  UNIQUE KEY url (url)
) TYPE=MyISAM COMMENT=' All websites/domains for use with phpSERA';

