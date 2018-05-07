<?php
define('ROOT_PATH', str_replace('interface/jinli/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		9=>array(
				'android'=>array(
						'appKey'=>'F6B13B586A244BCF9E902C0DB5585DBA',
						'appSecret'=>'0ED11B374C7241CA9F1808736F72A4F5',
						'notifyUrl'=>'http://fhweb.u776.com:86/interface/jinli/callback.php',
						'publicKey'=>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDI9dm3NP8QAQ71gQtvuPH/7FzYpR4Kxm0A14yobQK4r5d4uTPq1ho9K0Fpruy3MTdFa9x8llB7YpQrHa/HQhpSK/2Ew8k2YNAy4zEvfH7ubkmQAE17KZ4MgMQEDFaB/JxH0D4rtBOP6/GG4Vzpwxx+8RkDPjHbUirkt7fFx+dWYwIDAQAB',
						'privateKey'=>'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIS09BbrpOQkcxXA94hPrRv46gTyod/94wdruwyhYr+nP8fIuk1K/DdrqbVdVqq+ChygrsbDjI1Y3PDumFDvoijD7NfE5kiHdPLf5sdJhcbyEhklnZUzBuxMoxBIC/uKQi9NFUd373d1S4TNpEM3ZXQQ5q+39dnS++JArvzjShm1AgMBAAECgYAviwCToZJuZZyRY5OK0ziqd8+nPCHANJ38T50wljThgpK4CXKtNDsNw9WF802UWAcZYHudG/uju/1Tl7cb7+4A9rS8HHvxEr5gmzHh/9job5yax5Jf4sOJ0Dzczw0mgCb/sdd1Hv7zmFjbE+1bNJtGsSpYjz6Oo+FjrIbpcP5U1QJBALpzkZWlJqq70auYnR8J6xnHCXt4KN7sABo+hSD59jCT4JbRQnkF1wzPPmPvQ7uG7tJqpnv22IKwZwdNwkfX4oMCQQC2NULQJNIx3NLQVoaFNjGxOdzBQnb3/SwHDjA9yFR5Av7bhVS/t0eCgcE7EZDpzXQWmX/8wV6kQuT0dtQunH1nAkB1pIvcVusR2RYPZmjk97YeeqZyADwRg+kApigLyYvb1MJlhr2hbNzmmTDtjz82aInxvBc1qmer9i/bvOVzvTSPAkBOevpgNLvkhjy3R82BKyqUL1wKUdp0TjPchhv5QIRB6yxi2Tb7rLG8YK8eBh7o1XmfElayQ4fEMJv1QAl8WiMZAkEAolEd6aj0kn0fBHz6W/4jIK3zmvQxWRN63fXBhIb2Uub4zrxxUlcAgh2o0Wi9ZGlRzld0MshtLLGL1KTLQnYKWg=='
				),
		)
);
?>
