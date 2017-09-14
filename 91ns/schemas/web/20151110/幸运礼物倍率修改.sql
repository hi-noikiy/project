update pre_lucky_gift_configs set count=0,pointer=0;
delete from pre_lucky_gift_odds;
alter table pre_lucky_gift_odds auto_increment=1;
update pre_gift_configs set description='赠送人有一定几率(低)获得10/50/100/500倍的奖励' where id in

(40,41);
update pre_gift_configs set description='赠送人有一定几率(中)获得10/50/100/500倍的奖励' where id in

(42,43);
update pre_gift_configs set description='赠送人有一定几率(高)获得10/50/100/500倍的奖励' where id in

(44);