<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/17
 * Time: 下午1:40
 */
define('ROOT_PATH', str_replace('interface/google/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";


$key_arr = array(
    8=>array(
        'appId'     =>'409589426094-encph12ih4c21is76aiek88nkvi7t9dp.apps.googleusercontent.com',
        'appSecret' =>'ijt-WyyWhm8-2yqJT1tlGsj6',
        'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArjHj2tEuMZuh4kcid13AvUK2AE6R/7kgAmZ4N4x44fS3BsojOi2086qj9wAjifycrzGng7UQrg0Z+jVoE+qdLloHrlIWjTaIInGgkbf81jGE/RgjP/IuxVnOXZ/0zhcQdPYv5LgZziJIF3nuaWjzLI/OLQI5QAAqR344YMTuwfr3Hmxi3LiJOSkhzu6jbB5yI+9T54Udsdij1s5b0W4/FGfmevtbSF4r66qnw2SvJLTC+eJY0C35zNjsHxhAniBLyk8iA/6lOAtB7vLuflsW/84ICcJxVuYAURGEfdF9wSk9aKKy/Pos2yGwMhVygF5uVpzAtN6z5aAF4/9n3X0+jQIDAQAB',

        'yuenan'=>array(
            'appId'     =>'460214801887-71mb1h1bllkeq9svl8rovi577r74hho8.apps.googleusercontent.com',
        	'appSecret' =>'rzn8GbILzbEv1TjGWjhc_6h3',
        	'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAlDY7Fog97GVOdnabtrWVt18X1YeunT4zdIgmY48S7OKSkCP3tEQXV9zm79gmngzGVErsM/rq7qxtIQspaPtlIB39vLyoa3ZHIumVOTJZdacwYn7Eq7DKKaCOP9r9VzaWhzF1pjtLOt3IYnwi2JfCEKatwekbVEi9DtgsEllfwUf7Q1bI3wGc5VPlnFQYBzhOpMefwpHzw1BHs/CMPl1zWGjmlnihydATaqQ1Ba6P9Pzjz7eenCDGshPLxR2ZgAKV+r49DQL2O+NOadpBNm9eSjZ1e1wSodocS+qysJo+2byrjDzKIdQI0To7HQ03GxOnz/0/KAxjwSW8vgg3z2iPgwIDAQAB',
        		
        ),
    		'yuenanios1'=>array(
    				'appId'     =>'868661371046-05fo8jo43lghe5okj1rlu02ip57bei78.apps.googleusercontent.com',
    				'appSecret' =>'qIlnSx5aP0922PiVDhMZmfH2',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAootoTa8Cse/OGtV6Qyu0MkfOpxIXpWfu0CLX00L5S6ozEi+E7+/xE9ByQln6KkEhGb7ardFWKbJ9q9Ix16yLOMU1MJwDz37tPNA6qR8VxAk+di8wjm0Sw+E7HkwrFqMqkYXgkfw5czpsyq/3ycgVeFyIrQFrKuhDh/YRWzSdj7wYv5AdfuqTd9vCzYa3pWU6FYLaWkCIWEQMVGaKxrfy9lf1b0/U75YZ7n8KB/d1IBOiL41vD/WorjGoWg0XrKir21d6yrdz/7WWEaHEhQfrDYuUwNPccRa7F+FHne1RsxZ4hGYSjgIRdbW2kFc//ENkrismy3r/7U/rUAOCfub4tQIDAQAB',
    		),
    		'yuenan2'=>array(
    				'appId'     =>'663870778067-e0khth0val6neuuuteaehvo064i63lb3.apps.googleusercontent.com',
    				'appSecret' =>'HXGhr9XBYPbXCPOaCOq22y1T',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAootoTa8Cse/OGtV6Qyu0MkfOpxIXpWfu0CLX00L5S6ozEi+E7+/xE9ByQln6KkEhGb7ardFWKbJ9q9Ix16yLOMU1MJwDz37tPNA6qR8VxAk+di8wjm0Sw+E7HkwrFqMqkYXgkfw5czpsyq/3ycgVeFyIrQFrKuhDh/YRWzSdj7wYv5AdfuqTd9vCzYa3pWU6FYLaWkCIWEQMVGaKxrfy9lf1b0/U75YZ7n8KB/d1IBOiL41vD/WorjGoWg0XrKir21d6yrdz/7WWEaHEhQfrDYuUwNPccRa7F+FHne1RsxZ4hGYSjgIRdbW2kFc//ENkrismy3r/7U/rUAOCfub4tQIDAQAB',
    		),
    		'yuenan3'=>array(
    				'appId'     =>'982530327631-bk0m3m3jpbofdd9og940sbk1fft6ilo3.apps.googleusercontent.com',
    				'appSecret' =>'bCypJHqC9OnqyYdBOWGqgycQ',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAysrJKPt5jseVBOyAMRivcMpFl/Mlra1yzwxqm+x0qqI/rWXCC6J2p/2MTSkXvl77gtcwEDH/6WjPDJ8MEGDSBseaTtMECe0ZOZB61DdP3/PkBXLlcs1O/0ktyYVZu+ffNQ8/uJYa9/fzuIoR/QB4NQ7K7+fsjzWksca8tke511482S8wVwHGtmz0R4w260IRCZOYnG7VgQfp1dwuhurEpNLywjBqSVjwtuetmulHEaTjnPmVrfeydNmFCdA6iO6zvr+h2Vgq6KKfZUPfDOl/sUDhsMOeg4tqxsz2rd1XU1F1kENYh1fc+kE4rir4H4WSTGtdqnpIrmCsJRtU6sfqgQIDAQAB',
    		),
        'xinma'=>array(
            'appId'     =>'409589426094-encph12ih4c21is76aiek88nkvi7t9dp.apps.googleusercontent.com',
            'appSecret' =>'ijt-WyyWhm8-2yqJT1tlGsj6',
            'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6WpcSYMnMkbmf1kZd6vm+Du4tJUirbUf+09//jXJoZEVxkTvkfETgKOZNef/ZUCpN7+ORkRdIV+UjQP75TmEHnj49qmoGCW7KVrnXmsDHFBotJvPVR9F1lee7/ymiGiV5ke5R/CpQplZ/lA7/HGsZKoBZCX95YZhDtmT8Es/FaStzgxpRgQmArMpOQ4N1zNOyEck+ZdP8RxN6MrC9P9tpgz1yjDNjed3uWyaq72YnCoeV0tGIt4VIAvgP1lSPUekZirYvEZhPScv7SRKeXxlBLgE/+9Na2iNWcaVTljHn6kP4dNSXNm8KCt6bx3FjbfL81fb/yz2JOxHL1BKlORv+QIDAQAB',
        ),
    		'nanmei'=>array(
    				'appId'     =>'902164192500-5keu21re1nebfj6pvn2rvbnk6oudti6q.apps.googleusercontent.com',
    				'appSecret' =>'8PcIDbgxILTI8aSQdtdY3D7y',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6WpcSYMnMkbmf1kZd6vm+Du4tJUirbUf+09//jXJoZEVxkTvkfETgKOZNef/ZUCpN7+ORkRdIV+UjQP75TmEHnj49qmoGCW7KVrnXmsDHFBotJvPVR9F1lee7/ymiGiV5ke5R/CpQplZ/lA7/HGsZKoBZCX95YZhDtmT8Es/FaStzgxpRgQmArMpOQ4N1zNOyEck+ZdP8RxN6MrC9P9tpgz1yjDNjed3uWyaq72YnCoeV0tGIt4VIAvgP1lSPUekZirYvEZhPScv7SRKeXxlBLgE/+9Na2iNWcaVTljHn6kP4dNSXNm8KCt6bx3FjbfL81fb/yz2JOxHL1BKlORv+QIDAQAB',
    		),

    ),
);
$google_id_value = array(
    'scgpokevs_84'     => array('0.99', 80	, 'USD'),
    'scgpokevs_425'    => array('4.99', 430,'USD'),
    'scgpokevs_851'    => array('9.99', 850,'USD'),
    'scgpokevs_1703'    => array('19.99', 1700, 'USD'),
    'scgpokevs_4259'   => array('49.99', 4260, 'USD'),
    'scgpokevs_8519'   => array('99.99', 8520, 'USD'),
		'dvfsdv_6'     => array('0.99', 80	, 'USD'),
		'vkcgvkz_30'    => array('4.99', 430,'USD'),
		'vgkfcsz_68'    => array('9.99', 850,'USD'),
		'zvsa_138'    => array('19.99', 1700, 'USD'),
		'huuyk_348'   => array('49.99', 4260, 'USD'),
		'kygmv_698'   => array('99.99', 8520, 'USD'),
		'cdewva_6'     => array('0.99', 80	, 'USD'),
		'vszcvrg_30'    => array('4.99', 430,'USD'),
		'grtyhb_68'    => array('9.99', 850,'USD'),
		'ntnfbxs_138'    => array('19.99', 1700, 'USD'),
		'bhnrtg_348'   => array('49.99', 4260, 'USD'),
		'jmuyn_698'   => array('99.99', 8520, 'USD'),
);
