<?
//获取会员等级
//$rid为 rank字段的值
function getRank($rid){
	switch($rid)
	{
		case 0:
			$str = "普通会员";
			break;
		case 1:
			$str = "黄金会员";
			break;
		case 2:
			$str = "白金会员";
			break;
		case 3:
			$str = "钻石会员";
			break;
		default:
			$str = "普通会员";
	}
	return $str;
}

?>