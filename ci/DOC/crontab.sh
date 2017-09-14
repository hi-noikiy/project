# 自动执行程序脚本
05 * * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunHour run
10 * * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunHour reg2
15 * * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunHour reg1
04 03 * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunDay run >> /data/log/web/run/run.log
10 03 * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunDay CliNewPlayer >> /data/log/web/run/CliNewPlayer.log
15 03 * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunDay CliLogin >> /data/log/web/run/CliLogin.log
20 03 * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunDay OnlineTime >> /data/log/web/run/OnlineTime.log
30 03 * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunDay SystemAnalysis >> /data/log/web/run/SystemAnalysis.log
40 03 * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunDay au >> /data/log/web/run/au.log
50 03 * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunDay UserRemain >> /data/log/web/run/UserRemain.log
55 03 * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunDay ActiveAccountCount >> /data/log/web/run/ActiveAccountCount.log
10 04 * * * /usr/local/php/bin/php -f /var/www/html/ci/index.php AutoRunDay UserLost >> /data/log/web/run/UserLost.log
