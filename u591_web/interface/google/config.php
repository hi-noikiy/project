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
    		'androidyn5'=>array(
    				'appId'     =>'555434017469-b5t619gpulojhdiufbv67h5h9rllhu7t.apps.googleusercontent.com',
    				'appSecret' =>'qAG0QR0PrRuleC0PxkaFH_MQ',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAu+j9xM7LGh/+w/hv2sBTnIH9ranZBr/dQzCtJVeuBJfDW0DpWvn4GpcckFMCZXv00LieaUztATwrECJCOJ2TTdzu/LBTWBQQMOJP5BpZkpI/A5z1XlvzZ+Rx7cYoFj4Z1xiQjwVFdVtleTfUUbw832naJkQHbB8vgCt2BWPxjYNLqozypZzkt5o+WGWyKPQNxsf1hvEK3v2uhezVDuq2b1AH5x5hpXW6bcpT12mH44O4Orqk2FIpoJepw1I3nYSREAyuqC1uSHYNkS7Z3O0r5bAK1Znf4jEcT+9nGrzTchhoLw7OIDApntn96BjQKHNBK85vxYOVJvnbKH422tdWkQIDAQAB',
    		),
    		'androidyn6'=>array(
    				'appId'     =>'444156906236-eoeelgqcnsmri8p3tqk7he10ac15fl8a.apps.googleusercontent.com',
    				'appSecret' =>'Mh9LE_aP7rLP5SCszXkSrGPU',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAygijkhFdMHjFVeQAtE6uauEGDqeXOtnlxx+U/Zrs1v7TCAQ+x7LrLc0hRtz/R1pv7SBKwySTKtvkYNt2RykJYcB4tkczZN1NWFctLIppXcn2Cia0X63F8Y/yuHqODlhYIUq+gTacfOYMzMBzVeWxXeG9yBbbKFcSTbU1X3hOywbVhfpHG+QeLX5dtOx5qW4eb2OGGjZOW8x/oTnSc8EHgzBZQ7wINJuja2vVNTZPVW3JoDya0g7TlpwuG/dlYpVEkWq0CIsWN04W+PwMZC7OEDSPPjT97S0TZ0ui4yp9WTs7rHeOFpAe+nMBL1jXr7Ix3R4WgZm4rcKiRSI2lxKLKwIDAQAB',
    		),
        'xinma'=>array(
            'appId'     =>'409589426094-encph12ih4c21is76aiek88nkvi7t9dp.apps.googleusercontent.com',
            'appSecret' =>'ijt-WyyWhm8-2yqJT1tlGsj6',
            'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6WpcSYMnMkbmf1kZd6vm+Du4tJUirbUf+09//jXJoZEVxkTvkfETgKOZNef/ZUCpN7+ORkRdIV+UjQP75TmEHnj49qmoGCW7KVrnXmsDHFBotJvPVR9F1lee7/ymiGiV5ke5R/CpQplZ/lA7/HGsZKoBZCX95YZhDtmT8Es/FaStzgxpRgQmArMpOQ4N1zNOyEck+ZdP8RxN6MrC9P9tpgz1yjDNjed3uWyaq72YnCoeV0tGIt4VIAvgP1lSPUekZirYvEZhPScv7SRKeXxlBLgE/+9Na2iNWcaVTljHn6kP4dNSXNm8KCt6bx3FjbfL81fb/yz2JOxHL1BKlORv+QIDAQAB',
        ),
    		/*'nanmei'=>array(
    				'appId'     =>'893867583164-t9tv9am66h1kg0ghkbajnnfns44sfks9.apps.googleusercontent.com',
    				'appSecret' =>'dECCAP4NUy6l_3DpSP5MIqpO',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6WpcSYMnMkbmf1kZd6vm+Du4tJUirbUf+09//jXJoZEVxkTvkfETgKOZNef/ZUCpN7+ORkRdIV+UjQP75TmEHnj49qmoGCW7KVrnXmsDHFBotJvPVR9F1lee7/ymiGiV5ke5R/CpQplZ/lA7/HGsZKoBZCX95YZhDtmT8Es/FaStzgxpRgQmArMpOQ4N1zNOyEck+ZdP8RxN6MrC9P9tpgz1yjDNjed3uWyaq72YnCoeV0tGIt4VIAvgP1lSPUekZirYvEZhPScv7SRKeXxlBLgE/+9Na2iNWcaVTljHn6kP4dNSXNm8KCt6bx3FjbfL81fb/yz2JOxHL1BKlORv+QIDAQAB',
    		),*/
    		'nanmei'=>array(
    				'appId'     =>'331845228942-isrhok8d7vpcrpunub8smn30dnkbjii8.apps.googleusercontent.com',
    				'appSecret' =>'2TQomQh8rMH0AG0PU47Thc7t',
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
    		'androidnm2'=>array(
    				'appId'     =>'360630468458-fq83lpoqm8vkjlrgh37t35md1aikqico.apps.googleusercontent.com',
    				'appSecret' =>'clHjMH7BRP-LsjrO1cWIWzh6',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAipGwimgIANO5kPY0VMclAyzsmCQPtIyeKkL5+9+cmapgnrjr5UDbePKWyWw3/RML6oTEO7TTKbMKJC0hPRmWU6UAmKZtTd5LMHAJSJbKucSN22NTzJrsx5a9INY3nVNYLE2lZo+ZzwByCsOsvVYIb0rX+8xsl7BQ70sfg2Rb2MIP/GaMjLj/ZF7nQ8yPa5zu+Ch1kw89By+OtT7pSLUHAcUidNgEP34Gx3BJSzv9HQv3wGahL4EsVn4zVX2EI2CRtVfeBGLDNI0uK4OUx/dfR3Y3HDjtzxMlYU57Mh65pbUp1jwuTBCEpY7Ckqa0JVD5rkpvDPsrsXSn003IB0oIHwIDAQAB',
    		),
    		'androidnm3'=>array(
    				'appId'     =>'744095051267-p4vo0l5o1msk8n544c4tqia0idh29fls.apps.googleusercontent.com',
    				'appSecret' =>'Eb4FXxIHjqH3V3Eifhp1aLL4',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5udRUD7MjZr/ij0HGdqjQ7mKKDBg1JVYuQZOxkkBnYe9ebIkfVyxjT5Bl0Zh2Xh0YDpANi9I3D5vjyEkeWK38eRmGo7Hll0bmVEsYBKemAvR6W/yqOrpTCJln4F1ThcUoxssODo71JY+9J+z7Vax93eqmuTmJVODyWlWYvAfEZjjG+dR/0rShf+5If25API+Lg4Ekojpn86nstnxt74X4F8RxZqd3a4mOAWNKdfsiyG2HWN2hbqtLlPUk/ykK8uGB4r0mKchVNRLjjDMEPRdSEPIcUPtZjDoZA1nIbz1ttCnX7IuWn59ajknTAdXZb+VXDl3FPazv+B3OpW+qtgd5wIDAQAB',
    		),
    		'iosels'=>array(
    				'appId'     =>'756425793624-7cr2t3j83q90msvukff9kc81v0d6ainl.apps.googleusercontent.com',
    				'appSecret' =>'GYQVWA25Glvv6etiePAo_ePk',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiKqdoMj+9eUeEYTcJGeBI3mfIkilXF/nMnUsTdqdUAGqUa6iQ1ncaDZaOEhr1GoH9PC9v7pVlBkBZGj0wVkSxS0H79e2br/ut7oOGCmxwt4h6Xmi+/UUJAd0MWPmUYI9XqJcVj+1mZAXRGSnMHKUtOi4CdP4BuNsuB3nsgi78WGQdTPq65blCLORqTNzDA9A64/Nw1/3vVoZxgYY+g6xh30ZmSK81Gec0QMoQEL0mLuZoKrJjED5ZnaCJFCVeV8PlaQCQE9eNpOAZiFJnuQU2C1eY3eIv687K6pzl38ikM9mqTHN0PCnfCPvsCB1BCV127L+5LUBVN4EoiJjiR72hQIDAQAB',
    		),
    		'androidels'=>array(
    				'appId'     =>'764064794233-jjcu00c98s1d653ol2nrruv9q2qcaoas.apps.googleusercontent.com',
    				'appSecret' =>'qL5PRO_Fcp5pliZ83gMoapaj',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjCLznhfpPKiEkc//n+EcLSXoSFS+SzRaNruC52GXmQGIC82+aAnM8D25ioWjy34DD/1gTNRCxNuSvavKpDjPXTG3BIIvpQ/tM4kGn/pijI82N74YbWDGMdLQaqo90nsr8k+/rXaA1UF5rahl8vLaw8ngzBb849ZPaSCl9uVbifUZZucIeSRpNKtX0IAKC3vu9LgbEGMuvBchuuS0Idm7mrqy64F9YwxOxAt+xH9Y8Dp87lM2P5XVm8wxpXaIBh3wV3HMys6Tnme2+2lg93ELnLOM3yjGy6IgPQJfCvGM3P0RK6EnLyRGMrJbwU2QSKcDXHtXlxxHfGlU4DHWIqwqiwIDAQAB',
    		),
    		'androidels2'=>array(
    				'appId'     =>'263076265365-bn2vmai3ladrdc7igjcirj2c4ps5l8l0.apps.googleusercontent.com',
    				'appSecret' =>'NvMO3PSLzFFhY8Yt-4yQjKIB',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAw++TWFw7xAf/ZlK1vVgVjBXLERTeIN/3wcYFtdNBGq8yMCtxazdkruZZrTuzKwuDI0ag0qENF5ZkYPJ5DKGrcSrotsoVQYuR9ZcYo5/F/V+c8M4IZEWQPH9nlw4xZTZrrPMNzYQ65Aeo5nHeGcwvoFsH9JKMEQ00z81CljJNT7h2RyiAun5WeqvPgM9q6GAvPMmS/L1fYCK0mcJiNVYavlDnb07EWABK2XavJQ5Rvq6GVUTAUMY6857rt3y+g/dl4cTcwneUwhA/jStkUDhOG09lHpHysPRIiRV0UEq7SplFBVJpWXUMxmO6msRRGi0S/fPiDCx7+HrBMIi99NL97wIDAQAB',
    		),
    		'androidels3'=>array(
    				'appId'     =>'1001795515533-akk2k57tgvaa1sauge6loei1u6bd5h4r.apps.googleusercontent.com',
    				'appSecret' =>'m1JLspIFm-j5eOTi-YM2ZHPi',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnsoEyH2LOz2jMTz3Y5Ngj1L8urIZUCwDq/yJzTKsgYEm+Vx1bxlPjevqkHx/rW+C0fVs+OWkHsACQS5yFIgqk7p1r9TPlKQIW/MpIp3W2WmCWm7dVRO+OIVW1m02FhwMSOmSreKZ39jeXAa2PhcCkG6k6nczwrnv3f85sXO8SbQd4aoe+WMwPVWFvexGOsr3eXW3v+fVrPQrgEK6+dQssfRAuwUpxjN/O8QSVTTk6kG83cns4cuF1/oAeMPcf/z+9mLWuzSVLJuS7ZqxenndaUFVauGQB4JkGNoImM+44PVr5eqmHpzHVWlu1CzCm+na5GJuSAZySNSGhV8OMydxYQIDAQAB',
    		),
    		'androidar'=>array(
    				'appId'     =>'13000614965-dhh3qk5ug4agk2vdblluudldi54p60mb.apps.googleusercontent.com',
    				'appSecret' =>'KNCvmRY4cAsF0a84cteDRnn1',
    				'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAp4V3KnuWpwJFaxO54ZKTa4LMBJcBLnD8UIHXJFQms1K7stOh6A2VOd1vImDTP8dHGCn9KvwPlz+eSzJUYtIKdyGqFb9c2jKdb2w2ieih/XEjQ6Mwd9wV8Lcrz6sfl3BV+GgkQHLW81404fOqmKbpD/UQxLvcV3MIH/UXix5mzCX/ZwRWDoCZh9zy1cJowYEIPo9gheAKMpg0FQK9N4mHCyBFJU7V9OYgwv9u4mjL5PZJ6IlYbXJRHNoZnbjJkXgmyzk6iDlXkvHp+FMxH0D1v+B9BBOmFck5Srt+2q9pNGyCGPbXEJczGSo4zs172HRyjNmAQmyOZDufjLlDEfxYqQIDAQAB',
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
		'nmchongzhi_60'     => array('0.99', 60	, 'USD'),
		'nmchongzhi_300'    => array('4.99', 300,'USD'),
		'nmchongzhi_600'    => array('9.99', 600,'USD'),
		'nmchongzhi_900'    => array('14.99', 900, 'USD'),
		'nmchongzhi_1500'   => array('24.99', 1500, 'USD'),
		'nmchongzhi_3000'   => array('49.99', 3000, 'USD'),
		'nmchongzhi_6000'   => array('99.99', 6000, 'USD'),
		'nmchongzhi_card'   => array('5.99', 360, 'USD'),
		'yuenanpay_6'     => array('0.99', 90	, 'USD'),
		'yuenanpay_12'    => array('1.99', 180,'USD'),
		'yuenanpay_18'    => array('3.99', 360,'USD'),
		'yuenanpay_30'    => array('4.99', 450, 'USD'),
		'yuenanpay_68'   => array('9.99', 910, 'USD'),
		'yuenanpay_138'   => array('19.99', 1820, 'USD'),
		'yuenanpay_348'   => array('49.99', 4540, 'USD'),
		'yuenanpay_698'   => array('99.99', 9090, 'USD'),
		'vszhandou_6'     => array('0.99', 90	, 'USD'),
		'vszhandou_12'    => array('1.99', 180,'USD'),
		'vszhandou_18'    => array('3.99', 360,'USD'),
		'vszhandou_30'    => array('4.99', 450, 'USD'),
		'vszhandou_68'   => array('9.99', 910, 'USD'),
		'vszhandou_138'   => array('19.99', 1820, 'USD'),
		'vszhandou_348'   => array('49.99', 4540, 'USD'),
		'vszhandou_698'   => array('99.99', 9090, 'USD'),

		'skypay_60'     => array('0.99', 60	, 'USD'),
		'skypay_300'    => array('4.99', 300,'USD'),
		'skypay_600'    => array('9.99', 600,'USD'),
		'skypay_900'    => array('14.99', 900, 'USD'),
		'skypay_1500'   => array('24.99', 1500, 'USD'),
		'skypay_3000'   => array('49.99', 3000, 'USD'),
		'skypay_6000'   => array('99.99', 6000, 'USD'),
		'skypay_card'   => array('5.99', 360, 'USD'),
		
		'ksmdgsepay_75'      => array('75','80', 'RUB'), //
		'ksmdgsepay_379'      => array('379','390', 'RUB'), //
		'ksmdgsepay_1490'     => array('1490', '1690', 'RUB'),
		'ksmdgsepay_3790'     => array('3790', '4290', 'RUB'),
		'ksmdgsepay_7490'    => array('7490', '8500', 'RUB'),
		'ksmdgsepay_card'    => array('299', '200', 'RUB'),
		
		'kshujd889_75'      => array('75','80', 'RUB'), //
		'kshujd889_379'      => array('379','390', 'RUB'), //
		'kshujd889_1490'     => array('1490', '1690', 'RUB'),
		'kshujd889_3790'     => array('3790', '4290', 'RUB'),
		'kshujd889_7490'    => array('7490', '8500', 'RUB'),
		'kshujd889_card'    => array('299', '200', 'RUB'),
		
		'arpays_60'     => array('0.99', 60	, 'USD'),
		'arpays_300'    => array('4.99', 280,'USD'),
		'arpays_600'    => array('9.99', 600,'USD'),
		'arpays_900'    => array('14.99', 870, 'USD'),
		'arpays_1500'   => array('24.99', 1450, 'USD'),
		'arpays_3000'   => array('49.99', 3000, 'USD'),
		'arpays_6000'   => array('99.99', 5940, 'USD'),
		'arpays_card'   => array('5.99', 360, 'USD'),
);
