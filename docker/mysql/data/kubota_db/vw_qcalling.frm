TYPE=VIEW
query=select `kubota_db`.`tb_caller`.`caller_ids` AS `caller_ids`,`kubota_db`.`tb_caller`.`q_ids` AS `q_ids`,`kubota_db`.`tb_quequ`.`q_num` AS `q_num`,`kubota_db`.`tb_caller`.`call_timestp` AS `call_timestp`,`kubota_db`.`tb_counterservice`.`counterservice_name` AS `counterservice_name`,`kubota_db`.`tb_counterservice`.`counterservice_callnumber` AS `counterservice_callnumber`,`kubota_db`.`tb_caller`.`call_status` AS `call_status`,`kubota_db`.`tb_counterservice`.`servicegroupid` AS `servicegroupid`,`kubota_db`.`tb_counterservice`.`serviceid` AS `serviceid` from ((`kubota_db`.`tb_caller` join `kubota_db`.`tb_quequ` on(`kubota_db`.`tb_quequ`.`q_ids` = `kubota_db`.`tb_caller`.`q_ids`)) join `kubota_db`.`tb_counterservice` on(`kubota_db`.`tb_counterservice`.`counterserviceid` = `kubota_db`.`tb_caller`.`counter_service_id`)) where `kubota_db`.`tb_caller`.`call_status` = \'calling\' order by `kubota_db`.`tb_caller`.`call_timestp` desc limit 10
md5=0c427bc4eeec31e1a1c3ba4325a6a2e1
updatable=0
algorithm=0
definer_user=root
definer_host=%
suid=1
with_check_option=0
timestamp=2020-08-13 17:03:22
create-version=2
source=select `tb_caller`.`caller_ids` AS `caller_ids`,`tb_caller`.`q_ids` AS `q_ids`,`tb_quequ`.`q_num` AS `q_num`,`tb_caller`.`call_timestp` AS `call_timestp`,`tb_counterservice`.`counterservice_name` AS `counterservice_name`,`tb_counterservice`.`counterservice_callnumber` AS `counterservice_callnumber`,`tb_caller`.`call_status` AS `call_status`,`tb_counterservice`.`servicegroupid` AS `servicegroupid`,`tb_counterservice`.`serviceid` AS `serviceid` from ((`tb_caller` join `tb_quequ` on(`tb_quequ`.`q_ids` = `tb_caller`.`q_ids`)) join `tb_counterservice` on(`tb_counterservice`.`counterserviceid` = `tb_caller`.`counter_service_id`)) where `tb_caller`.`call_status` = \'calling\' order by `tb_caller`.`call_timestp` desc limit 10;
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_general_ci
view_body_utf8=select `kubota_db`.`tb_caller`.`caller_ids` AS `caller_ids`,`kubota_db`.`tb_caller`.`q_ids` AS `q_ids`,`kubota_db`.`tb_quequ`.`q_num` AS `q_num`,`kubota_db`.`tb_caller`.`call_timestp` AS `call_timestp`,`kubota_db`.`tb_counterservice`.`counterservice_name` AS `counterservice_name`,`kubota_db`.`tb_counterservice`.`counterservice_callnumber` AS `counterservice_callnumber`,`kubota_db`.`tb_caller`.`call_status` AS `call_status`,`kubota_db`.`tb_counterservice`.`servicegroupid` AS `servicegroupid`,`kubota_db`.`tb_counterservice`.`serviceid` AS `serviceid` from ((`kubota_db`.`tb_caller` join `kubota_db`.`tb_quequ` on(`kubota_db`.`tb_quequ`.`q_ids` = `kubota_db`.`tb_caller`.`q_ids`)) join `kubota_db`.`tb_counterservice` on(`kubota_db`.`tb_counterservice`.`counterserviceid` = `kubota_db`.`tb_caller`.`counter_service_id`)) where `kubota_db`.`tb_caller`.`call_status` = \'calling\' order by `kubota_db`.`tb_caller`.`call_timestp` desc limit 10
mariadb-version=100505
