<?
//��ȡ��Ա�ȼ�
//$ridΪ rank�ֶε�ֵ
function getRank($rid){
	switch($rid)
	{
		case 0:
			$str = "��ͨ��Ա";
			break;
		case 1:
			$str = "�ƽ��Ա";
			break;
		case 2:
			$str = "�׽��Ա";
			break;
		case 3:
			$str = "��ʯ��Ա";
			break;
		default:
			$str = "��ͨ��Ա";
	}
	return $str;
}

?>