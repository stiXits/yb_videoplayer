#
# Table structure for table 'tx_ybvideoplayer_domain_model_playlist'
#
CREATE TABLE tx_ybvideoplayer_domain_model_playlist (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	description text NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	videos int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_ybvideoplayer_domain_model_video'
#
CREATE TABLE tx_ybvideoplayer_domain_model_video (

        uid int(11) NOT NULL auto_increment,
        pid int(11) DEFAULT '0' NOT NULL,

	file varchar(255)  NOT NULL,
	description text NOT NULL,
	preview varchar(255) DEFAULT '' NOT NULL,
	endscreen varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	subtitle varchar(255) DEFAULT '' NOT NULL,
	categories int(11) DEFAULT '' NOT NULL,

        tstamp int(11) unsigned DEFAULT '0' NOT NULL,
        crdate int(11) unsigned DEFAULT '0' NOT NULL,
        cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
        deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
        hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
        starttime int(11) unsigned DEFAULT '0' NOT NULL,
        endtime int(11) unsigned DEFAULT '0' NOT NULL,

        t3ver_oid int(11) DEFAULT '0' NOT NULL,
        t3ver_id int(11) DEFAULT '0' NOT NULL,
        t3ver_wsid int(11) DEFAULT '0' NOT NULL,
        t3ver_label varchar(255) DEFAULT '' NOT NULL,
        t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
        t3ver_stage int(11) DEFAULT '0' NOT NULL,
        t3ver_count int(11) DEFAULT '0' NOT NULL,
        t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
        t3ver_move_id int(11) DEFAULT '0' NOT NULL,

        t3_origuid int(11) DEFAULT '0' NOT NULL,
        sys_language_uid int(11) DEFAULT '0' NOT NULL,
        l10n_parent int(11) DEFAULT '0' NOT NULL,
        l10n_diffsource mediumblob,

        PRIMARY KEY (uid),
        KEY parent (pid),
        KEY t3ver_oid (t3ver_oid,t3ver_wsid),
        KEY language (l10n_parent,sys_language_uid)


);


#
# Table structure for table 'tx_ybvideoplayer_playlist_video_mm'
#
CREATE TABLE tx_ybvideoplayer_playlist_video_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
