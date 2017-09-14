<?php
/**
 * 利用simpleXML 处理 xml 数据类
 * @author LSH 2013-02-17 
 */
namespace Think;
class SimpleXML{
	
	public $arrNeedKey = array(); // 需要变成并列子项的key,如不指定，则有程序自己判断
	
	public $attr = false; // 为true时表示需要将属性值读取
	
	public function __construct($config = array())
	{
		if (isset($config['arrNeedKey']) && is_array($config['arrNeedKey'])) 
		{
			$this->arrNeedKey = $config['arrNeedKey'];
		}
		else 
		{
			$this->arrNeedKey = array(
					'Cinema'     => 1,
					'CinemaPlan' => 1,
					'SeatInfo'   => 1,
					'hall'       => 1,
					'takeEffect' => 1,
					'play'       => 1
					);
		}
		
		if (isset($config['attr'])) 
		{
			$this->attr = $config['attr'];
		}
	}
	
	public function setNeedKey($arr = array())
	{
		$this->arrNeedKey = $arr;
	}
	
	/**
	 * 将simpleXML 对象转换为PHP 数组..
	 * @author  LSH 2013-02-17 
	 * 
	 * @param object $simplexml_obj   传入的simpleXML 数据对象
	 * @param INT $strip_white             是否清除左右空格
	 * @return array                                转换好的数组..
	 *
	 */
	public function xml2array($simplexml_obj, $strip_white = 1)
	{
		$arr_ret		= array();
		$array_all_key  = array();
		
		$array_need_key = $this->arrNeedKey;
		if( $simplexml_obj )
		{
			if($simplexml_obj->attributes()){
				foreach ($simplexml_obj->attributes() as $k=>$v){
					$simplexml_obj->$k=$v;
				}
			}
			if( count($simplexml_obj) == 0)
			{
				
				return $strip_white?trim((string)$simplexml_obj):(string)$simplexml_obj;
			}
			$arrNeedKey = $this->getNeedKey($simplexml_obj);
			foreach ($simplexml_obj as $k => $val)
			{
				if ($this->attr === true) 
				{
					
					if( !empty($arrNeedKey) && isset($arrNeedKey[$k]) )
					{					
						$arrNew = array(
							'list' => $this->xml2array($val, $strip_white),
							'attr' => $this->getAttr($val),
						);
						
						$arr_ret[$k][] = $arrNew;
					}
					else
					{
						$arr_ret[$k]['list'] = $this->xml2array($val, $strip_white);
						$arr_ret[$k]['attr'] = $this->getAttr($val);
					}
				}
				else 
				{
					if( !empty($arrNeedKey) && isset($arrNeedKey[$k]) )
					{
							
						$arr_ret[$k][] = $this->xml2array($val, $strip_white);
					}
					else
					{
						$arr_ret[$k] = $this->xml2array($val, $strip_white);
					}
				}

			}
			return $arr_ret;
		}
		
		return $strip_white?trim((string)$simplexml_obj):(string)$simplexml_obj;
	}

	/**
	 * 将PHP 数组转换为 xml 数据..  
	 * 
	 * @param array $data             待转换的PHP 数组
	 * @param string $encoding    		编码
	 * @param string $root             根节点..
	 * @param string $item             需要将数字编号给为字符的item..
	 * @param string $isAll             是否输出完整的Xml 
	 * @return string                       转换好的xml数据
	 */
	public function xml_encode($data, $encoding='utf-8', $root="root",$item ="item",$isAll = TRUE) 
	{
		$xml = "";
		if ($isAll)
		{
			$xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
			$xml.= '<' . $root . '>';
			$xml.= $this->data_to_xml($data,$item);
			$xml.= '</' . $root . '>';
		}
		else 
		{
			$xml.= $this->data_to_xml($data,$item);
		}
		
		return $xml;
	}
	
	
	private  function data_to_xml($data,$item)
	 {
		if (is_object($data)) 
		{
			$data = get_object_vars($data);
		}
		$xml = '';
		foreach ($data as $key => $val)
		 {
			is_numeric($key) && $key = $item;
			$xml.="<$key>";
			$xml.= ( is_array($val) || is_object($val)) ? $this->data_to_xml($val,$item) : $val;
			list($key, ) = explode(' ', $key);
			$xml.="</$key>";
		}
		
		return $xml;
	}
	
	/**
	 * 获取属性值
	 * @param obj $objXml
	 * @return multitype:string 
	 */
	private function getAttr($objXml)
	{
		$arrRet  = array();
		$arrAttr = $objXml->attributes();
		
		
		foreach ($arrAttr as $k => $v)
		{
			$arrRet[$k] = strval($v);
		}
		
		return $arrRet;
	}
	
	/**
	 * 获取需要合并为子项数组的key值
	 * @param obh $objXml
	 * @return multitype:number 
	 */
	private function getNeedKey($objXml)
	{
		$arrAllKey  = array();
		$arrNeedKey = array();
		foreach ($objXml as $k => $val)
		{
			if (isset($arrAllKey[$k]))
			{
				if (!isset($this->arrNeedKey[$k]))
				{
					$this->arrNeedKey[$k] = 1;
				}
			}
			else
			{
				$arrAllKey[$k] =1;
			}
		}
		
		return $this->arrNeedKey;
	}
	
}