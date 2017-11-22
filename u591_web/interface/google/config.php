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
    		'yuenan4'=>array(
    				'appId'     =>'857043849994-n0qa0cmrqi4tn96gde0vdiqccq4ih4ue.apps.googleusercontent.com',
    				'appSecret' =>'b3GpliXx0pXdK0tSitTVZQkT',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6cm42U8Y6uhTT9HM+tHeYAJQKVVc2AJ8hVy/atr+SvK4D/YFffMV5Stc9WBC61hGI/hWUfnVaC7lHtUwFtqJe/jEsdNfLud1YmU5/wN5UUGSmoMuLTFQ4UvZdrbYpJKlLEKqM+Oc6V441B2iOEP+EtbQiE4f1jXABzB87j+Genxawmy/hgKNWnqL1CHCl5cVYzbhmB3xW6+Kbi3xuFgHVC1xpI7oOv7l0Vd9pbsOhGOY244zPfAv3OzjG9pQwFhfISDZ1d19LJjMBZJ7Frd22BW7No76vSS6WkIMEQ1JPSe4gz0NA7cGkIwzXGqqgH9f1KuNcXCVMrM5OTopBxIebwIDAQAB',
    		),
        'xinma'=>array(
            'appId'     =>'409589426094-encph12ih4c21is76aiek88nkvi7t9dp.apps.googleusercontent.com',
            'appSecret' =>'ijt-WyyWhm8-2yqJT1tlGsj6',
            'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6WpcSYMnMkbmf1kZd6vm+Du4tJUirbUf+09//jXJoZEVxkTvkfETgKOZNef/ZUCpN7+ORkRdIV+UjQP75TmEHnj49qmoGCW7KVrnXmsDHFBotJvPVR9F1lee7/ymiGiV5ke5R/CpQplZ/lA7/HGsZKoBZCX95YZhDtmT8Es/FaStzgxpRgQmArMpOQ4N1zNOyEck+ZdP8RxN6MrC9P9tpgz1yjDNjed3uWyaq72YnCoeV0tGIt4VIAvgP1lSPUekZirYvEZhPScv7SRKeXxlBLgE/+9Na2iNWcaVTljHn6kP4dNSXNm8KCt6bx3FjbfL81fb/yz2JOxHL1BKlORv+QIDAQAB',
        ),
    		'nanmei'=>array(
    				'appId'     =>'893867583164-t9tv9am66h1kg0ghkbajnnfns44sfks9.apps.googleusercontent.com',
    				'appSecret' =>'dECCAP4NUy6l_3DpSP5MIqpO',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6WpcSYMnMkbmf1kZd6vm+Du4tJUirbUf+09//jXJoZEVxkTvkfETgKOZNef/ZUCpN7+ORkRdIV+UjQP75TmEHnj49qmoGCW7KVrnXmsDHFBotJvPVR9F1lee7/ymiGiV5ke5R/CpQplZ/lA7/HGsZKoBZCX95YZhDtmT8Es/FaStzgxpRgQmArMpOQ4N1zNOyEck+ZdP8RxN6MrC9P9tpgz1yjDNjed3uWyaq72YnCoeV0tGIt4VIAvgP1lSPUekZirYvEZhPScv7SRKeXxlBLgE/+9Na2iNWcaVTljHn6kP4dNSXNm8KCt6bx3FjbfL81fb/yz2JOxHL1BKlORv+QIDAQAB',
    		),
    		'nanmeiandroid'=>array(
    				'appId'     =>'727081987525-ieel6e13oo1qfor0lk6161u0phils2pg.apps.googleusercontent.com',
    				'appSecret' =>'ukbFJvBf8xYvigieNv72D9TU',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiKqdoMj+9eUeEYTcJGeBI3mfIkilXF/nMnUsTdqdUAGqUa6iQ1ncaDZaOEhr1GoH9PC9v7pVlBkBZGj0wVkSxS0H79e2br/ut7oOGCmxwt4h6Xmi+/UUJAd0MWPmUYI9XqJcVj+1mZAXRGSnMHKUtOi4CdP4BuNsuB3nsgi78WGQdTPq65blCLORqTNzDA9A64/Nw1/3vVoZxgYY+g6xh30ZmSK81Gec0QMoQEL0mLuZoKrJjED5ZnaCJFCVeV8PlaQCQE9eNpOAZiFJnuQU2C1eY3eIv687K6pzl38ikM9mqTHN0PCnfCPvsCB1BCV127L+5LUBVN4EoiJjiR72hQIDAQAB',
    		),
    		'androidnm1'=>array(
    				'appId'     =>'784371448524-695gh11kaomegj6vb2m6jeljkc8n3b6i.apps.googleusercontent.com',
    				'appSecret' =>'k84Rg1YMflRd8O3xFTUGL96O',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvbEMpLNRQlAFt8ujfCZQl17ROHx1OuKzwO7nWUFInhUzxYe+OlApwOr9kBGr49ZrBaNRnpVhC7L87ckjkCnwRqTo+T71uL/AXpK1ohbFSCnGS0Vz/PzkgNy//1ZPElxkShU7HwGe03BADiBwizQv/eLxbtOZFi0G86wlcKOvUhonY6mjCThc2XbMZxmWWeX/8q47oPtkzoB/W+1pZW+jokJR8CiiTC2WP8IerUJ1FdRsIEsYr0n3/aLCeIE+YWgi10G5CwIXVSAy6azlRwO4hE8GMPn6stnHzNANNV0QWe3IFFMugYWi6gtfUMk3kHdkk6odVd/Pd7cNkfTRBlWM0QIDAQAB',
    		),
    		'iosels'=>array(
    				'appId'     =>'464598782374-hbm1d41oog290gtbaje56eb6r85rl8pr.apps.googleusercontent.com',
    				'appSecret' =>'q4rAXBp-BAWG4wf2Ht4MFLN3',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiKqdoMj+9eUeEYTcJGeBI3mfIkilXF/nMnUsTdqdUAGqUa6iQ1ncaDZaOEhr1GoH9PC9v7pVlBkBZGj0wVkSxS0H79e2br/ut7oOGCmxwt4h6Xmi+/UUJAd0MWPmUYI9XqJcVj+1mZAXRGSnMHKUtOi4CdP4BuNsuB3nsgi78WGQdTPq65blCLORqTNzDA9A64/Nw1/3vVoZxgYY+g6xh30ZmSK81Gec0QMoQEL0mLuZoKrJjED5ZnaCJFCVeV8PlaQCQE9eNpOAZiFJnuQU2C1eY3eIv687K6pzl38ikM9mqTHN0PCnfCPvsCB1BCV127L+5LUBVN4EoiJjiR72hQIDAQAB',
    		),
    		'androidels'=>array(
    				'appId'     =>'764064794233-jjcu00c98s1d653ol2nrruv9q2qcaoas.apps.googleusercontent.com',
    				'appSecret' =>'qL5PRO_Fcp5pliZ83gMoapaj',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjCLznhfpPKiEkc//n+EcLSXoSFS+SzRaNruC52GXmQGIC82+aAnM8D25ioWjy34DD/1gTNRCxNuSvavKpDjPXTG3BIIvpQ/tM4kGn/pijI82N74YbWDGMdLQaqo90nsr8k+/rXaA1UF5rahl8vLaw8ngzBb849ZPaSCl9uVbifUZZucIeSRpNKtX0IAKC3vu9LgbEGMuvBchuuS0Idm7mrqy64F9YwxOxAt+xH9Y8Dp87lM2P5XVm8wxpXaIBh3wV3HMys6Tnme2+2lg93ELnLOM3yjGy6IgPQJfCvGM3P0RK6EnLyRGMrJbwU2QSKcDXHtXlxxHfGlU4DHWIqwqiwIDAQAB',
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
		'vfdrfvg_6'     => array('0.99', 80	, 'USD'),
		'ferf_30'    => array('4.99', 430,'USD'),
		'njsvfwf_68'    => array('9.99', 850,'USD'),
		'xvscdevf_138'    => array('19.99', 1700, 'USD'),
		'ceffz_348'   => array('49.99', 4260, 'USD'),
		'vrege_698'   => array('99.99', 8520, 'USD'),
		'cevwvc_60'     => array('0.99', 60	, 'USD'),
		'vfwcvvbe_300'    => array('4.99', 300,'USD'),
		'brgbdb_600'    => array('9.99', 600,'USD'),
		'btyhngb_900'    => array('14.99', 900, 'USD'),
		'hdfvsz_1500'   => array('24.99', 1500, 'USD'),
		'brtyesgvv_3000'   => array('49.99', 3000, 'USD'),
		'yhrebbgf_6000'   => array('99.99', 6000, 'USD'),
		'ngdsf'   => array('5.99', 360, 'USD'),
		'cfhudw_60'     => array('0.99', 60	, 'USD'),
		'vfevre_300'    => array('4.99', 300,'USD'),
		'vrgbht_600'    => array('9.99', 600,'USD'),
		'nthyn_900'    => array('14.99', 900, 'USD'),
		'dsgvrtfb_1500'   => array('24.99', 1500, 'USD'),
		'sgvfs_3000'   => array('49.99', 3000, 'USD'),
		'rther_6000'   => array('99.99', 6000, 'USD'),
		'wbvg'   => array('5.99', 360, 'USD'),

);
