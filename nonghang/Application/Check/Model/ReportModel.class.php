<?php
// +----------------------------------------------------------------------
// | 系统配置模型
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

class ReportModel extends Model {

    protected function _initialize(){
        $this->tab_pre = C('DB_PREFIX');
    }

    /**
     * 获取票卷订单列表
     * @param  arry $map  查询条件类型
     * @return obj 对象
     * @author 
     */
    public function order_group_lists($map, $field=''){
       return M('ticket_use_order')->field($this->tab_pre . 'ticket_use_order.*,cinema_name, count(item_id) as ticket_num, feature_date, feature_time, film_name, hall_name, copy_type,type_id, type_name')
       ->join('LEFT JOIN ' . $this->tab_pre . 'cinema_plan ON ' . $this->tab_pre . 'ticket_use_order.plan_id = ' . $this->tab_pre . 'cinema_plan.plan_id')
       ->join('LEFT JOIN ' . $this->tab_pre . 'ticket_list ON ' . $this->tab_pre . 'ticket_use_order.voucher_number like CONCAT("%",' . $this->tab_pre . 'ticket_list.voucher_number,"%") ')
       ->join('LEFT JOIN ' . $this->tab_pre . 'ticket_order ON ' . $this->tab_pre . 'ticket_order.id = ' . $this->tab_pre . 'ticket_list.order_id')
       ->join('LEFT JOIN ' . $this->tab_pre . 'ticket_type ON ' . $this->tab_pre . 'ticket_type.id =' . $this->tab_pre . 'ticket_list.type_id ')
       ->where($map)->group('order_no');
    }
    /**
     * 获取票卷柜台订单列表
     * @param  arry $map  查询条件类型
     * @return obj 对象
     * @author 
     */
    public function order_office_lists($map, $field=''){
      // if(!empty($map['admin_id'])){
      //     $map[$this->tab_pre . 'ticket_use_order.admin_id'] = $map['admin_id'];
      //     unset($map['admin_id']);
        
      // }
      // if(!empty($map['cinema_id'])){
      //     $map[$this->tab_pre . 'users.cinema_id'] = $map['cinema_id'];
      //     unset($map['cinema_id']);
        
      // }
      // if(!empty($map['add_time'])){
      //     $map[$this->tab_pre . 'ticket_use_order.add_time'] = $map['add_time'];
      //     unset($map['add_time']);
      // }
      // if(!empty($map['voucher_number'])){
      //     $map[$this->tab_pre . 'ticket_use_order.voucher_number'] = $map['voucher_number'];
      //     unset($map['voucher_number']);
      // }
      return M('ticket_use_order')->field('*')->where($map);
    }
        /**
     * 获取票卷订单列表
     * @param  arry $map  查询条件类型
     * @return obj 对象
     * @author 
     */
    public function order_lists($map, $field=''){
       return M('ticket_use_order')->field($field)->where($map);
    }

     /**
     * 获取票卷适用影院列表
     * @param  array  配置值
     * @author 
     */
    public function get_cinema(){
        return M('cinema_info')->field('cinema_id,cinema_name')->where("state=1")->select();
    }
     /**
     * 获取票卷适用影院列表
     * @param  array  配置值
     * @author 
     */
    public function get_issue(){
        return M('ticket_issue')->field('id,issue_name');
    }
     /**
     * 获取票卷适用影院列表
     * @param  array  配置值
     * @author 
     */
    public function get_type(){
        return M('ticket_type')->field('id,type_name');
    }
}