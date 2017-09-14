INSERT INTO `inv_module` (`parentId`, `moduleName`, `moduleAction`, `moduleSort`, `moduleType`, `createTime`) VALUES ('4', '托', 'tuo', '3', '1', '1450800000');

UPDATE inv_module SET `moduleName` = '推广员/水军/托' WHERE `id` = 4;

UPDATE inv_module SET `moduleSort` = '4' WHERE `parentId` = 4 AND `id` = 53;