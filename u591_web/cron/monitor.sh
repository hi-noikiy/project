#*************************************************************************
#  FileName     :               monitor.sh
#*************************************************************************
#  Author       :               luoue
#  CreateDate   :               2017-03-10
#  Description  :               this script is mointoring the linux disk
#                               capacity, if disk used more than 90%,
#                               then it will send message
#*************************************************************************

#!/bin/sh
log_path="/root/"
disk_log="${log_path}disk_detail_log.txt"
php_fmp_log="${log_path}php_fmp_start_log.txt"
php_fmp_log2="${log_path}php_fmp_notstart_log.txt"
mysql_log="${log_path}mysql_start_log.txt"
cup_log="${log_path}cpu_log.txt"
httpsqs_log="${log_path}httpsqs_log.txt"
nginx_log="${log_path}nginx_log.txt"
current_ip=$(curl whatismyip.akamai.com)

game_id="8"
hainiu_key="0dbddcc74ed6e1a3c3b9708ec32d0532"
code="${game_id}&${hainiu_key}"
sign=$(echo -n $code|md5sum|cut -d ' ' -f1)


#this script is mointoring the mysql port 3306
#
port=`netstat -nlt|grep 3306|wc -l`
if [ $port -ne 1 ];then
    /etc/init.d/mysql start
    #TODO send message
    if [ "$(find $mysql_log -mmin -3)" == "" ];then
        content="mysql had been restarted from ${current_ip}."
        phone="15059449082"
        curl "http://gunweb.u591.com:83/interface/duanxin/send_content.php?game_id=$game_id&content=$content&sign=$sign&phone=$phone"
        echo "$(date), ${content}" >> $mysql_log
        exit 0
    fi
fi

