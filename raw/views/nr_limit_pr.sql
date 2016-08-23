CREATE VIEW nr_limit_pr AS

SELECT 
	u.id AS user_id,
	u.user_package_deal_id AS limit_id,
	pc.package AS package,
	updd.type_qty AS amount_total,
	updd.used AS amount_used,
	updd.type_duration AS duration,  
	if (updd.type_id = 3, "PREMIUM", "BASIC") AS type,
	if (updd.type_duration IN ("DAILY", "WEEKLY"), 1, 0) 
		AS uses_calculated,
	/* this loses information due to UTC conversion 
	   but it is close enough for legacy */
	upd.end_date AS date_expires
FROM
	dev_inw_legacy.users u 
INNER JOIN 
	dev_inw_legacy.user_package_deals upd 
	ON u.user_package_deal_id = upd.id
	/* uses server timezone */
	AND upd.start_date <= NOW() 
	AND upd.end_date >= NOW()
INNER JOIN 
	dev_inw_legacy.user_package_deal_details updd
	ON u.user_package_deal_id = updd.user_package_id
LEFT JOIN 
	nr_package_conversion pc
	ON upd.deal_id = pc.legacy_deal_id