CREATE VIEW nr_user AS 
SELECT ub.*, pc.package, 1 AS is_migrated FROM 
nr_user_base ub
LEFT JOIN 
dev_inw_legacy.users lu
	ON ub.id = lu.id
LEFT JOIN 
dev_inw_legacy.user_package_deals upd
	ON lu.user_package_deal_id = upd.id
	AND upd.start_date <= NOW() 
	AND upd.end_date >= NOW()
LEFT JOIN 
nr_package_conversion pc 
	ON upd.deal_id = pc.legacy_deal_id