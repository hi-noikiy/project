<?php

/**
 * rsa
 * 需要 openssl 支持
 * @author andsky
 *
 */
class MyRsa extends Rsa
{

    private static $_instance;

    const private_key = '
-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDA4E8H2qksOnCSoBkq+HH3Dcu0/iWt3iNcpC/BCg0F8tnMhF1Q
OQ98cRUM8eeI9h+S6g/5UmO4hBKMOP3vg/u7kI0ujrCN1RXpsrTbWaqry/xTDgTM
8HkKkNhRSyDJWJVye0mPgbvVnx76en+K6LLzDaQH8yKI/dbswSq65XFcIwIDAQAB
AoGAU+uFF3LBdtf6kSGNsc+lrovXHWoTNOJZWn6ptIFOB0+SClVxUG1zWn7NXPOH
/WSxejfTOXTqpKb6dv55JpSzmzf8fZphVE9Dfr8pU68x8z5ft4yv314qLXFDkNgl
MeQht4n6mo1426dyoOcCfmWc5r7LQCi7WmOsKvATe3nzk/kCQQDp1gyDIVAbUvwe
tpsxZpAd3jLD49OVHUIy2eYGzZZLK3rA1uNWWZGsjrJQvfGf+mW+/zeUMYPBpk0B
XYqlgHJNAkEA0yhhu/2SPJYxIS9umCry1mj7rwji5O2qVSssChFyOctcbysbNJLH
qoF7wumr9PAjjWFWdmCzzEJyxMMurL3gLwJBAIEoeNrJQL0G9jlktY3wz7Ofsrye
j5Syh4kc8EBbuCMnDfOL/iAI8zyzyOxuLhMmNKLtx140h0kkOS6C430M2JUCQCnM
a5RX/JOrs2v7RKwwjENvIqsiWi+w8C/NzPjtPSw9mj2TTd5ZU9bnrMUHlnd09cSt
yPzD5bOAT9GtRVcCexcCQBxXHRleikPTJC90GqrC2l6erYJaiSOtv6QYIh0SEDVm
1o6Whw4FEHUPqMW0Z5PobPFiEQT+fFR02xU3NJrjYy0=
-----END RSA PRIVATE KEY-----';
    const public_key = '
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzb7LN/iPS5bs/T2oRfd6
9dHf6Y8fuS3+zcbSTYrx8Q0SQH4smHKv2MDyr8w5Z6oTq85zRMSg/KQOr259lIK/
mTHOBBkhSWS7kTi5Oa0dBIUSI8ltrQATA3YV3Okx9Nadyc8Xf9DdKhWzNAnPugyl
EyRdrUKMSAxmtL27zjp3REOBEOBOVmPlJbhyk8LtntC00Z6T/yezCm6uvLaQiR0G
9dvHXWpinbO1Ri2ZDQQANPZEW+4ExT75SJpMMkQV3e8yijRmnl4yGMuayb/tfY7e
fAMbjScQXH4oq4lCoiGAOR8Pqp3iKo8ro7ovoJKGZtxekFi2P4D5n2yjqfJ2hdX1
bwIDAQAB
-----END PUBLIC KEY-----';
    
   /* const public_key = '
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3UpkubrGyLrG67tpk1oI
tkE9sGYiXB9dFmH35CZpxz4AGQDxQYPHPGFWD3w6VIoz7WR43IH/s+REL0lHv350
pPKYJ0iuxcJEseRo03QNsrVzTHid0Z9jPhykmc6HUbnS8xe4FX3vosuheTSWi8F5
r/Cwl4QGLlMMVwz6K1xNz/Er4SWsDVtFgQdpmgPgtXGgpQLWXm7h9fu3sa9HCBp7
40WWp3ESzOn7Bdl1V8ut8W/YsWjy17+p7OlAET2ErlkFmtrF7VoKVInVsKJfyJgQ
eCfKUnLf7is4sxHD4zFd02YHYokNXHzJq3CbRiTWfnnXogmQe2t1OukH/QxiUhnr
8wIDAQAB
-----END PUBLIC KEY-----';*/




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


}
?>