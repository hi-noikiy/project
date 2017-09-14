<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/17
 * Time: 下午1:40
 */
define('ROOT_PATH', str_replace('interface/facebook/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";


//$key_arr = array(
//    8=>array(
//        'appId'=>'409589426094-encph12ih4c21is76aiek88nkvi7t9dp.apps.googleusercontent.com',
//        'appSecret'=>'ijt-WyyWhm8-2yqJT1tlGsj6',
//        'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArjHj2tEuMZuh4kcid13AvUK2AE6R/7kgAmZ4N4x44fS3BsojOi2086qj9wAjifycrzGng7UQrg0Z+jVoE+qdLloHrlIWjTaIInGgkbf81jGE/RgjP/IuxVnOXZ/0zhcQdPYv5LgZziJIF3nuaWjzLI/OLQI5QAAqR344YMTuwfr3Hmxi3LiJOSkhzu6jbB5yI+9T54Udsdij1s5b0W4/FGfmevtbSF4r66qnw2SvJLTC+eJY0C35zNjsHxhAniBLyk8iA/6lOAtB7vLuflsW/84ICcJxVuYAURGEfdF9wSk9aKKy/Pos2yGwMhVygF5uVpzAtN6z5aAF4/9n3X0+jQIDAQAB',
//    ),
//);
//$google_id_value = array(
//    'gas'=>array(1,10),
//);
