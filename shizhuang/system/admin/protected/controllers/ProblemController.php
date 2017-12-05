<?php
class ProblemController extends Controller{

    public function _condition(&$condition){
        $condition = array();
        if (isset($_POST['username']) && !empty($_POST['username']))
            $condition[] = "username like '{$_POST['username']}%'";
        if (isset($_POST['phone']) && !empty($_POST['phone']))
            $condition[] = "phone='{$_POST['phone']}'";
        if (isset($_POST['type']) && $_POST['type'] != '')
            $condition[] = "type='{$_POST['type']}'";
    }


}
