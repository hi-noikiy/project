<?php

/**
 * Created by PhpStorm.
 * User: pan
 * Date: 15/9/15
 * Time: 上午11:57
 */
abstract class KSBaseEntity
{
    /**
     * 使用数组进行初始化，只初始化合法字段
     * @param array $data key => value 形式，key为字段名，value为字段值
     */
    public function initByArray(array $data)
    {
        $fields = $this->getFields();
        foreach ($data as $name => $value) {
            if (in_array($name, $fields)) {
                $setMethod = $this->getMethodSet($name);
                $this->$setMethod($value);
            }
        }
    }

    /**
     * 获取当前的所有字段
     * @return array
     */
    public function getFields()
    {
        $clazz = new ReflectionClass($this);
        $properties = $clazz->getProperties();
        $resultArray = array();
        foreach ($properties as $property) {
            $var = $property->getName();
            $resultArray[] = $var;
        }
        return $resultArray;
    }

    /**
     * 将field转换为方法需要的样子
     * e.g. : userName => userName
     *        user_name => userName
     * 暂时支持两种，后续可以扩展
     * @param $field
     * @return mixed
     */
    protected function changeFieldToMethod($field)
    {
        if (strpos($field, '_') > 0) {
            $parts = explode('_', $field);
            $method = '';
            foreach ($parts as $part) {
                $method .= ucfirst($part);
            }
            return $method;
        }
        return $field;
    }

    /**
     * 根据字段获取get方法名
     * @param $field
     * @return string
     */
    protected function getMethodGet($field)
    {
        return 'get' . $this->changeFieldToMethod($field);
    }

    /**
     * 根据字段获取set方法名
     * @param $field
     * @return string
     */
    protected function getMethodSet($field)
    {
        return 'set' . $this->changeFieldToMethod($field);
    }

    public function toMiniArray()
    {
        $data = array();
        $fields = $this->getFields();
        foreach ($fields as $field) {
            $getMethod = $this->getMethodGet($field);
            $value = $this->$getMethod();
            if (isset($value)) {
                $data[$field] = $value;
            }
        }
        return $data;
    }

    public abstract function toString();

}
