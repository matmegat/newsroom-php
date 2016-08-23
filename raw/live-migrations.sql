/* ===================================================== */

ALTER TABLE users CHANGE email email VARCHAR(254) CHARACTER 
SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE users CHANGE password password VARCHAR(60) CHARACTER 
SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE users ADD INDEX (email);
ALTER TABLE users ADD INDEX (lname);
ALTER TABLE users ADD INDEX (fname);
ALTER TABLE users ADD INDEX (user_package_deal_id);
ALTER TABLE users ADD INDEX (active);

ALTER TABLE users CHANGE ip_address ip_address VARCHAR(45) CHARACTER 
SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

/* ===================================================== */

ALTER TABLE prs ADD is_migrated TINYINT(1) NOT NULL,
ADD migrated_content_id INT NOT NULL,
ADD INDEX (is_migrated);

ALTER TABLE users ADD is_migration_finished TINYINT(1) NOT NULL;

/* ===================================================== */