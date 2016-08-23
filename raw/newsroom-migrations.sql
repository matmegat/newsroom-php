/* ======= transformations for newsroom database ======= */

ALTER TABLE nr_limit_pr_held ADD date_expires DATETIME NOT NULL,
ADD INDEX (date_expires); 

ALTER TABLE nr_limit_email_held ADD date_expires DATETIME NOT NULL,
ADD INDEX (date_expires);

/* ===================================================== */

ALTER TABLE nr_country ADD is_common TINYINT(1) NOT NULL,
ADD INDEX (is_common);

UPDATE nr_country SET is_common = 1 
WHERE id IN (287, 473, 474);

CREATE TABLE IF NOT EXISTS nr_report_sent (
  content_id int(11) NOT NULL,
  date_sent datetime NOT NULL,
  PRIMARY KEY (content_id),
  KEY date_sent (date_sent)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE nr_content DROP is_report_email_sent;

ALTER TABLE nr_bar_stage ADD requires_credit 
ENUM('PREMIUM_PR','BASIC_PR','EMAIL','NEWSROOM') 
CHARACTER SET utf8 COLLATE utf8_general_ci 
NULL DEFAULT NULL;

INSERT INTO nr_bar_stage (id, bar_id, name, 
display_name, info_link, requires_credit) 
VALUES (null, 1, 'activate-newsroom', 
'Activate Newsroom', 'manage/companies', 
'NEWSROOM');

/* ===================================================== */

INSERT INTO nr_bar_stage (id, bar_id, name, 
display_name, info_link, requires_credit) VALUES 
(NULL, 1, 'add-press-contact', 'Add Press Contact', 
'manage/newsroom/contact/edit', NULL);

/* ===================================================== */

CREATE TABLE IF NOT EXISTS nr_package_conversion (
  legacy_deal_id int(11) NOT NULL,
  package int(11) NOT NULL,
  PRIMARY KEY (legacy_deal_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO nr_package_conversion (legacy_deal_id, package) 
VALUES (1, 1), (2, 2), (3, 3), (7, 3), (12, 2);

------ nr_limit_pr.sql ----- 
------ nr_user.sql ----- 

/* ===================================================== */

ALTER TABLE nr_content_data ADD summary VARCHAR(512) NOT NULL AFTER content;

/* ===================================================== */

UPDATE nr_content_data cd
INNER JOIN nr_pb_video tl ON cd.content_id = tl.content_id 
SET cd.summary = tl.summary;

UPDATE nr_content_data cd
INNER JOIN nr_pb_audio tl ON cd.content_id = tl.content_id 
SET cd.summary = tl.summary;

UPDATE nr_content_data cd
INNER JOIN nr_pb_news tl ON cd.content_id = tl.content_id 
SET cd.summary = tl.summary;

UPDATE nr_content_data cd
INNER JOIN nr_pb_pr tl ON cd.content_id = tl.content_id 
SET cd.summary = tl.summary;

UPDATE nr_content_data cd
INNER JOIN nr_pb_event tl ON cd.content_id = tl.content_id 
SET cd.summary = tl.summary;

UPDATE nr_content_data cd
INNER JOIN nr_pb_image tl ON cd.content_id = tl.content_id 
SET cd.summary = tl.summary;

ALTER TABLE nr_pb_pr DROP summary;
ALTER TABLE nr_pb_news DROP summary;
ALTER TABLE nr_pb_event DROP summary;
ALTER TABLE nr_pb_image DROP summary;
ALTER TABLE nr_pb_audio DROP summary;
ALTER TABLE nr_pb_video DROP summary;

DROP TABLE nr_setting;

CREATE TABLE IF NOT EXISTS nr_setting (
  name varchar(64) NOT NULL,
  description varchar(256) NOT NULL,
  type enum('INTEGER','STRING','BOOLEAN') NOT NULL,
  value varchar(2048) NOT NULL,
  PRIMARY KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO nr_setting (name, description, type, value) VALUES
('bundled_email_credits', 'The number of email credits that are bundled with each PR.', 'IsNTEGER', '50'),
('extra_email_credits_gold', 'The number of extra email credits included with the gold package.', 'INTEGER', '200'),
('extra_email_credits_platinum', 'The number of extra email credits included with the platinum package.', 'INTEGER', '750'),
('extra_email_credits_silver', 'The number of extra email credits included with the silver package.', 'INTEGER', '100'),
('free_basic_pr_count', 'The number of basic press releases allowed for free users per period.', 'INTEGER', '1'),
('free_basic_pr_period', 'The number of days in each period used by free_basic_pr_count. ', 'INTEGER', '7'),
('latest_member_news', 'The news to show on the sidebar of the control panel.', 'STRING', 'This value is taken from the database and supports html. Lorem ipsum Ut ut sint esse magna tempor Excepteur fugiat velit id dolore ea nisi do sint ea irure non.'),
('newsroom_credits_free', 'The number of allowed newsrooms for free users.', 'INTEGER', '0'),
('newsroom_credits_gold', 'The number of allowed newsrooms for gold users.', 'INTEGER', '1'),
('newsroom_credits_platinum', 'The number of allowed newsrooms for platinum users.', 'INTEGER', '5'),
('newsroom_credits_silver', 'The number of allowed newsrooms for silver users.', 'INTEGER', '1'),
('press_release_links_basic', 'The maximum number of embedded links in a basic press release.', 'INTEGER', '0'),
('press_release_links_premium', 'The maximum number of embedded links in a premium press release.', 'INTEGER', '3'),
('press_release_max_length', 'The maximum length (in characters) of the body text of press releases.', 'INTEGER', '4000'),
('press_release_min_words', 'The minimum number of words allowed in a press release.', 'INTEGER', '250'),
('summary_max_length', 'The maximum length (in characters) of the summary text on content.', 'INTEGER', '250'),
('title_max_length', 'The maximum length (in characters) of the title text on content.', 'INTEGER', '160');

ALTER TABLE nr_content CHANGE slug slug VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE nr_company ADD is_legacy TINYINT(1) NOT NULL AFTER is_archived, ADD INDEX (is_legacy);

ALTER TABLE nr_setting CHANGE type type ENUM('INTEGER', 'STRING', 'BOOLEAN', 'TEXT') 
CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE nr_video_guide ADD date_updated DATETIME NOT NULL AFTER stored_image_id;

------ nr_newsroom.sql ----- 

/* ===================================================== */

ALTER TABLE nr_content DROP INDEX list_on_status;
ALTER TABLE nr_content ADD INDEX (company_id);
ALTER TABLE nr_content ADD INDEX (type);

ALTER TABLE nr_content ADD is_rejected TINYINT(1) 
NOT NULL AFTER is_approved;

------ nr_user.sql ----- 

/* ===================================================== */

ALTER TABLE nr_setting CHANGE type type ENUM('INTEGER', 'STRING', 'BOOLEAN', 'TEXT', 'VIDEO') 
CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

INSERT INTO nr_setting (name, description, type, value) VALUES ('overview_video', 
'The YouTube video used for the control panel overview.', 'VIDEO', 'zZPJPuaK4vQ');

------ nr_limit_pr.sql -----

CREATE TABLE IF NOT EXISTS nr_id_store (
  name varchar(64) NOT NULL,
  next int(11) NOT NULL DEFAULT 1073741824,
  PRIMARY KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO nr_id_store (name) VALUES ('user');

CREATE TABLE IF NOT EXISTS nr_user_base (
  id int(11) NOT NULL,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  password char(60) NOT NULL,
  email varchar(254) NOT NULL,
  is_active tinyint(4) NOT NULL,
  is_admin tinyint(4) NOT NULL,
  is_verified tinyint(4) NOT NULL,
  is_reseller tinyint(4) NOT NULL,
  date_created datetime NOT NULL,
  remote_addr varchar(45),
  notes text,
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  KEY is_active (is_active),
  KEY is_admin (is_admin),
  KEY is_reseller (is_reseller),
  KEY is_verified (is_verified),
  KEY date_created (date_created)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

------ nr_user.sql -----

INSERT INTO nr_user_base
SELECT 
  u.id AS id,
  u.fname AS first_name,
  u.lname AS last_name,
  u.password AS password,
  u.email AS email,
  u.active AS is_active,
  u.admin AS is_admin,
  if ((u.verify_code = ''), 1, 0) AS is_verified,
  u.reseller AS is_reseller,
  created AS date_created,
  NULL AS remote_addr,
  notes AS notes  
FROM 
freepr_inews.users u
LEFT JOIN 
freepr_inews.user_package_deals upd
  ON u.user_package_deal_id = upd.id
  AND upd.start_date <= NOW() 
  AND upd.end_date >= NOW()
LEFT JOIN 
nr_package_conversion pc 
  ON upd.deal_id = pc.legacy_deal_id
WHERE u.is_migrated = 1;

CREATE TABLE IF NOT EXISTS nr_blocked (
  addr varchar(45) NOT NULL,
  date_blocked date NOT NULL,
  PRIMARY KEY (addr),
  KEY date_blocked (date_blocked)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* ===================================================== */

CREATE TABLE nr_iella_event (
  name VARCHAR(64) NOT NULL,
  method VARCHAR(128) NOT NULL,
  PRIMARY KEY (name, method)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS nr_content_docsite (
  content_id int(11) NOT NULL,
  docsite_scribd varchar(1024) DEFAULT NULL,
  docsite_issuu varchar(1024) DEFAULT NULL,
  PRIMARY KEY (content_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO nr_setting (name, description, type, value) VALUES 
('dist_header_text', 'Text to be displayed at the top of the distribution report. ', 'TEXT', 
'<p>Thank you again for purchasing a premium press release submission with iNewswire.</p>
 <p>You made a wise investment and this report will provide all the vital information regarding your press release submission. This report was custom created to ensure all the bases were covered and give you a full comprehensive report on the top sites your releases have been published to. iNewswire syndication partners include sites such as Twitter.com, NYDailyNews.com, Boston.com, BNet.com, Topix.com and dozens more high authority sites.</p>'); 

INSERT INTO nr_setting (name, description, type, value) VALUES 
('dist_footer_text', 'Text to be displayed at the bottom of the distribution report. ', 'TEXT', '');

CREATE TABLE nr_credit_notification (
  user_id INT NOT NULL,
  date_sent DATETIME NOT NULL,
  type ENUM("PR", "EMAIL") NOT NULL,
  INDEX (user_id, date_sent, type)
) ENGINE = InnoDB;

ALTER TABLE nr_user_base ADD date_active DATE NOT NULL AFTER date_created,
ADD INDEX (date_active);

------ nr_user.sql -----

/* ===================================================== */

ALTER TABLE nr_setting CHANGE value value VARCHAR(16384) 
CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
