<?php
/**
	 * * 使用 openssl 实现非对称加密
	 */
class Rsa {
	const PUBKEY = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCmreYIkPwVovKR8rLHWlFVw7YDfm9uQOJKL89Smt6ypXGVdrAKKl0wNYc3/jecAoPi2ylChfa2iRu5gunJyNmpWZzlCNRIau55fxGW0XEu553IiprOZcaw5OuYGlf60ga8QT6qToP0/dpiL/ZbmNUO9kUhosIjEu22uFgR+5cYyQIDAQAB';
	const PRIKEY = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAI3oPTjPvy8yxZhNJlanBgrEdADn+TVkaJkdooWp4nVQ+XYltS3TYu0jMjmRo5yFL0coTvnDeYfC4u1a3XrEa6UfX+Ht8PfvLdgo6xkrXK/nAsXbfIQNwkq3hDHtQvCNm+Rtx/pd31ALF43rPbFgJOZl9yCU7gmVzEWOE4tcdMCDAgMBAAECgYBoeTdBcSkm3XE9zGbSWssG+FUTKbV+Snr7Zyb00vrHNiNCiUZXA46MWWIrof5bSG5jK9jg5rm9aoxZBJGURbGdTdR4JQbIWYN0XBVZN3w0baUWAFtIvH2rsOjXKNfNqVAZ8hq2SgYJUzWPzd9A43REm2O/+F9qxWxY9/2+hz8YUQJBAP8Qt+S9UM37Tftkg/Y2KYy76WOp6TkSWmlzQMVeA1tEqdCNwMgupCJH5JdqX2RzLlj6LZeI6C1sh7BM1MRqQOsCQQCObV1w+SIRqxlaLPdULpL969C9aCdjYSmRRaTtz86HSF8PBeAwRJGxLix6vOuep/Ck8c90x2zXXDuBjraGZVjJAkBWwUX7HFVmqkp87lRgI04Am02n43v7OH3eDiCCwRZdLb6gvAZNUGftbQyYX8rwbKjgBMALIbru8FI6qfdYk1dfAkAR/o9HrrI3OT7CvduhryCzJBADXh1b2PK/f+UOhqq5PNOFumBQuNkPYZ4NA8FhEia9MC/duTRvISADhlxZLoTBAkEAr2gLbiwPtgawrw6Cme/iq1GQvm6wtyYEUAPZ37f+gB/Hw3fi/8y8a3jDnZK2TtvJPHUN6bnFOr2Acr4zXYvDLQ==';
	private $_privKey;
	/**
	 * * private key
	 */
	private $_pubKey;
	/**
	 * * public key
	 */
	private $_keyPath;
	/**
	 * * the keys saving path
	 */
	/**
	 * * the construtor,the param $path is the keys saving path
	 */
	public function __construct() {
	}
	
	/**
	 * * setup the private key
	 */
	private function setupPrivKey() {
		if (is_resource ( $this->_privKey )) {
			return true;
		}
		
		$pem = chunk_split ( self::PRIKEY, 64, "\n" );
		$pem = "-----BEGIN PRIVATE KEY-----\n" . $pem . "-----END PRIVATE KEY-----\n";
		
		$this->_privKey = openssl_pkey_get_private ( $pem );
		return true;
	}
	
	/**
	 * * setup the public key
	 */
	private function setupPubKey() {
		if (is_resource ( $this->_pubKey )) {
			return true;
		}
		
		$pem = chunk_split ( self::PUBKEY, 64, "\n" );
		$pem = "-----BEGIN PUBLIC KEY-----\n" . $pem . "-----END PUBLIC KEY-----\n";
		$this->_pubKey = openssl_pkey_get_public ( $pem );
		return true;
	}
	
	/**
	 * * encrypt with the private key
	 */
	public function privEncrypt($data) {
		if (! is_string ( $data )) {
			return null;
		}
		$this->setupPrivKey ();
		$r = openssl_private_encrypt ( $data, $encrypted, $this->_privKey );
		if ($r) {
			return base64_encode ( $encrypted );
		}
		return null;
	}
	
	/**
	 * * decrypt with the private key
	 */
	public function privDecrypt($encrypted) {
		if (! is_string ( $encrypted )) {
			return null;
		}
		$this->setupPrivKey ();
		$encrypted = base64_decode ( $encrypted );
		$r = openssl_private_decrypt ( $encrypted, $decrypted, $this->_privKey );
		if ($r) {
			return $decrypted;
		}
		return null;
	}
	
	/**
	 * * encrypt with public key
	 */
	public function pubEncrypt($data) {
		if (! is_string ( $data )) {
			return null;
		}
		$this->setupPubKey ();
		$r = openssl_public_encrypt ( $data, $encrypted, $this->_pubKey );
		if ($r) {
			return base64_encode ( $encrypted );
		}
		return null;
	}
	
	/**
	 * * decrypt with the public key
	 */
	public function pubDecrypt($crypted) {
		if (! is_string ( $crypted )) {
			return null;
		}
		$this->setupPubKey ();
		$crypted = base64_decode ( $crypted );
		$r = openssl_public_decrypt ( $crypted, $decrypted, $this->_pubKey );
		if ($r) {
			return $decrypted;
		}
		return null;
	}
	
	/**
	 * 签名
	 * 
	 * @param 被签名数据 $dataString        	
	 * @return string
	 */
	public function sign($dataString) {
		$this->setupPrivKey ();
		$signature = false;
		openssl_sign ( $dataString, $signature, $this->_privKey );
		return base64_encode ( $signature );
	}
	
	/**
	 * 验证签名
	 * 
	 * @param 被签名数据 $dataString        	
	 * @param 已经签名的字符串 $signString        	
	 * @return number
	 */
	public function verify($dataString, $signString) {
		$this->setupPubKey ();
		$signature = base64_decode ( $signString );
		$flg = openssl_verify ( $dataString, $signature, $this->_pubKey );
		return $flg;
	}
	public function __destruct() {
		@ openssl_free_key ( $this->_privKey );
		@ openssl_free_key ( $this->_pubKey );
	}
}
?>