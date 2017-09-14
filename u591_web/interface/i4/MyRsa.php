<?php

/**
 * rsa
 * 需要 openssl 支持
 * @author shaohua
 *
 */
class MyRsa extends Rsa
{

    private static $_instance;

    const private_key = '
-----BEGIN RSA PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAOO7gcIRq34W8uMafNSgBJHfDrwg
kwN+eQeFDuZbqTCUc09i9DJLl38M/MFO0aZm4BnXONT2WUsRwLG14e12acimnln5AH8aDeeuteRW
QaNd5s6dBPYsRtTts381qXzgtPKD3ttLwsnbpQNDaOTrXyFJx5Q+3G+Vc78WiIGyjON9AgMBAAEC
gYBHkJ43UXw19JpBegpuexbUjGdCAW6Rs4XvMgY7p+RkStJtySf6Qj0KhRdM3CsQAtY76KGIcx4e
dCa88e5cxFB7UOvLYK/zIsbg8cxqCBXNzVS5jeJthUzMnlpIi9r/dk4FWrydouyg7zrEBunqThAY
1lN/l9zgEmSq4tTGlGXNlQJBAPd8Fgg1VqQjunq+/aj6J8deE1z3zUVO6OnwFJwiozInPUE1widq
OYFSuZ5oMws/cH0j0H4lWGYjyFPa/WNCzUcCQQDrkW/5jYTVNFneY/c9eLuNWqRFiIlzc1rXKj2D
fmGjvuYKHa0qoeEDn9+xiHeaXEoCPzrt1+tm/Y+FfPWYr1sbAkEAynfJzo1UhJR2S1VVUJjXUlO0
o6pXVQxpoHm5YsMzoCRaCK9iV7yfrx1unhnBKMKN1NMoDwuednFvwqq2Ai70oQJAcXj4WFycPNtf
4umCgkDzD083TCtOEqfhfT3ircGmJOtjAkJzVNLvggB0D5+uBVQpblWo/EMDKPRBO0sLPwZROwJB
APKu3nMexx7b+7REMQLbo68r/OE/2S1rcKJbgM5/7de22ilzldTSvj2Do1KMateEk47xUCI0VsrD
ijGLkgE+L80=
-----END RSA PRIVATE KEY-----';
    const public_key = '
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0655RQYptAHMD8SZq8IzWzKBh4cZjwhHIN4VN
IkX7mpz8UWunucVJxzXrY1kslt69ZBiBgG3sUzJoXQXyIO5JgR3D9cruEESQGQDRNd+tGLn8cQws
vH7IeDF/vutvg6OsBR/jrHf2SENzwhZloulSiQbaJ1I84Of5LJ2G81Wa9QIDAQAB
-----END PUBLIC KEY-----';
    
    
    function __construct ()
    {
    }


    function __destruct ()
    {
    }

    public static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }


    function sign($sourcestr = NULL)
    {

        return base64_encode(parent::sign($sourcestr, self::private_key));

    }

    function verify($sourcestr = NULL, $signature = NULL)
    {
		
        return parent::verify($sourcestr, $signature, self::public_key);

    }
	
	function rsa_decrypt($data, $key, $rsa_bit = 1024) {
		$inputLen = strlen($data);
		$offSet = 0;
		$i = 0;
	 
		$maxDecryptBlock = $rsa_bit / 8;
	 
		$de = '';
		$cache = '';
	 
		while ($inputLen - $offSet > 0) {
	 
			if ($inputLen - $offSet > $maxDecryptBlock) {
				$cache = parent::publickey_decodeing(substr($data, $offSet, $maxDecryptBlock), $key);
			} else {
				$cache = parent::publickey_decodeing(substr($data, $offSet, $inputLen - $offSet), $key);
			}
	 
			$de = $de . $cache;
	 
			$i = $i + 1;
			$offSet = $i * $maxDecryptBlock;
		}
		return $de;
	}


}
?>
