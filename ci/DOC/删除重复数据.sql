-- delete from u_register where accountid in (select id from reg_tmp_acc) and id not in (select id from reg_tmp)

-- select * from u_register where accountid=95267;
-- select accountid from u_register where appid=10002 group by accountid having count(accountid)>1 ;

-- create table reg_tmp_acc(`id` int(10) not null,PRIMARY key (`id`)) ENGINE=INNODB;
-- insert into reg_tmp_acc select accountid from u_register group by accountid having count(accountid)>1;
-- and id not in (
-- create table reg_tmp(`id` int(10) not null,PRIMARY key (`id`)) ENGINE=INNODB;
-- insert into reg_tmp select min(id) as id from u_register group by accountid having count(accountid)>1 order by id asc;

-- delete from u_roles where accountid in (select id from reg_tmp_acc) and id not in (select id from reg_tmp) and appid=10001;
-- select count(*) from u_register;
-- truncate reg_tmp;
-- truncate reg_tmp_acc;
-- insert into reg_tmp select min(id) as id from u_roles group by accountid having count(accountid)>1 order by id asc;
-- insert into reg_tmp_acc select accountid from u_roles group by accountid having count(accountid)>1;
-- select count(*) from u_roles where 1=1;

-- select count(*) as cnt,accountid from u_roles where appid=10001 group by accountid,serverid HAVING cnt>1 ;
-- select * from u_roles where accountid=2105776;
-- show create table u_roles;