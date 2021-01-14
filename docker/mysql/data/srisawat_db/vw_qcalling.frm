TYPE=VIEW
query=select `srisawat_db`.`tb_caller`.`caller_ids` AS `caller_ids`,`srisawat_db`.`tb_caller`.`q_ids` AS `q_ids`,`srisawat_db`.`tb_quequ`.`q_num` AS `q_num`,`srisawat_db`.`tb_caller`.`call_timestp` AS `call_timestp`,`srisawat_db`.`tb_counterservice`.`counterservice_name` AS `counterservice_name`,`srisawat_db`.`tb_counterservice`.`counterservice_callnumber` AS `counterservice_callnumber`,`srisawat_db`.`tb_caller`.`call_status` AS `call_status`,`srisawat_db`.`tb_counterservice`.`servicegroupid` AS `servicegroupid`,`srisawat_db`.`tb_counterservice`.`serviceid` AS `serviceid` from ((`srisawat_db`.`tb_caller` join `srisawat_db`.`tb_quequ` on(`srisawat_db`.`tb_quequ`.`q_ids` = `srisawat_db`.`tb_caller`.`q_ids`)) join `srisawat_db`.`tb_counterservice` on(`srisawat_db`.`tb_counterservice`.`counterserviceid` = `srisawat_db`.`tb_caller`.`counter_service_id`)) where `srisawat_db`.`tb_caller`.`call_status` = \'calling\' order by `srisawat_db`.`tb_caller`.`call_timestp` desc limit 10
md5=0eca63b32437afce26e40928e78e2487
updatable=0
algorithm=0
definer_user=root
definer_host=%
suid=1
with_check_option=0
timestamp=2020-08-22 04:12:24
create-version=2
source=select `tb_caller`.`caller_ids` AS `caller_ids`,`tb_caller`.`q_ids` AS `q_ids`,`tb_quequ`.`q_num` AS `q_num`,`tb_caller`.`call_timestp` AS `call_timestp`,`tb_counterservice`.`counterservice_name` AS `counterservice_name`,`tb_counterservice`.`counterservice_callnumber` AS `counterservice_callnumber`,`tb_caller`.`call_status` AS `call_status`,`tb_counterservice`.`servicegroupid` AS `servicegroupid`,`tb_counterservice`.`serviceid` AS `serviceid` from ((`tb_caller` join `tb_quequ` on(`tb_quequ`.`q_ids` = `tb_caller`.`q_ids`)) join `tb_counterservice` on(`tb_counterservice`.`counterserviceid` = `tb_caller`.`counter_service_id`)) where `tb_caller`.`call_status` = \'calling\' order by `tb_caller`.`call_timestp` desc limit 10;
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_general_ci
view_body_utf8=select `srisawat_db`.`tb_caller`.`caller_ids` AS `caller_ids`,`srisawat_db`.`tb_caller`.`q_ids` AS `q_ids`,`srisawat_db`.`tb_quequ`.`q_num` AS `q_num`,`srisawat_db`.`tb_caller`.`call_timestp` AS `call_timestp`,`srisawat_db`.`tb_counterservice`.`counterservice_name` AS `counterservice_name`,`srisawat_db`.`tb_counterservice`.`counterservice_callnumber` AS `counterservice_callnumber`,`srisawat_db`.`tb_caller`.`call_status` AS `call_status`,`srisawat_db`.`tb_counterservice`.`servicegroupid` AS `servicegroupid`,`srisawat_db`.`tb_counterservice`.`serviceid` AS `serviceid` from ((`srisawat_db`.`tb_caller` join `srisawat_db`.`tb_quequ` on(`srisawat_db`.`tb_quequ`.`q_ids` = `srisawat_db`.`tb_caller`.`q_ids`)) join `srisawat_db`.`tb_counterservice` on(`srisawat_db`.`tb_counterservice`.`counterserviceid` = `srisawat_db`.`tb_caller`.`counter_service_id`)) where `srisawat_db`.`tb_caller`.`call_status` = \'calling\' order by `srisawat_db`.`tb_caller`.`call_timestp` desc limit 10
mariadb-version=100505
