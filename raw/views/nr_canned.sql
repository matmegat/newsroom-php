CREATE VIEW nr_canned AS
SELECT
  cn.id AS id,
  cn.title AS title,
  cn.txt AS content
FROM dev_inw_legacy.canned_messages cn