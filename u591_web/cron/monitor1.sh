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

for d in `df -P | grep /dev | awk '{print $5}' | sed 's/%//g'`
 do
    if [ $d -gt 90 && "$(find $disk_log -mmin -60)" == ""]; then
           df -h >>$message_log
           #TODO send message
           content='disk more than 90% from ${current_ip}.'
           curl "http://gunweb.u591.com:83/interface/duanxin/send_monitor_linux.php?game_id=$game_id&content=$content&sign=$sign"
           exit 0
    fi
done

#this script is mointoring the mysql port 3306
#
port=`netstat -nlt|grep 3306|wc -l`
if [ $port -ne 1 ];then
    /etc/init.d/mysqld start
    #TODO send message
    if [ "$(find $mysql_log -mmin -3)" == "" ];then
        content="mysql had been restarted from ${current_ip}."
        curl "http://gunweb.u591.com:83/interface/duanxin/send_monitor_linux.php?game_id=$game_id&content=$content&sign=$sign"
        echo "$(date), ${content}" >> $mysql_log
        exit 0
    fi
fi

# this script is mointoring linux CPU
#
CPU=`top -b -n 1|grep Cpu|awk '{print $2}'|cut -f 1 -d "."`
if [ $CPU -gt 90 ]
then
    #TODO send message
    if [ "$(find $cup_log -mmin -10)" == "" ];then
        content='cpu use more than 80% from ${current_ip}.'
        curl "http://gunweb.u591.com:83/interface/duanxin/send_monitor_linux.php?game_id=$game_id&content=$content&sign=$sign"
        echo "$(date), ${content}" >> $mysql_log
        exit 0
    fi
fi

# this script is mointoring httpsqs port 1218
#
qs_port=`netstat -nlt|grep 1218|wc -l`
if [ $qs_port -ne 1 ];then
    httpsqs -d -x /data/httpsqs/data -a u591
    #TODO send message
    if [ "$(find $httpsqs_log -mmin -3)" == "" ];then
        content='httpsqs had been restarted from ${current_ip}.'
        curl "http://gunweb.u591.com:83/interface/duanxin/send_monitor_linux.php?game_id=$game_id&content=$content&sign=$sign"
        echo "$(date), ${content}" >> $httpsqs_log
        exit 0
    fi
fi

# this script is mointoring nginx
#
#web_nginx=`ps -ef |grep nginx|grep -v grep|wc -l`
web_nginx=`netstat -nlt|grep 8080|wc -l`
if [ $web_nginx -ne 1 ];then
    /etc/init.d/nginx start
    #TODO send message
    if [ "$(find $nginx_log -mmin -3)" == "" ];then
        content='nginx had been restarted from ${current_ip}.'
        curl "http://gunweb.u591.com:83/interface/duanxin/send_monitor_linux.php?game_id=$game_id&content=$content&sign=$sign"
        echo "$(date), ${content}" >> $nginx_log
        exit 0
    fi
fi

# this script is mointoring linux php-fmp
#
#变量初始化
process="php-fpm" #进程名
down=0
while true
do
    #取得http状态码
    code=$(curl -I -m 10 -o /dev/null -s -w %{http_code}"\n"  127.0.0.1:8080)
    #当状态码返回000或者大于等于500时,计数故障到down变量
    if [ $code -eq 000 -o $code -ge 500 ];then
        ((down++))
    else
        break
    fi
    #稍等5s
    sleep 5
    #判断是否连续检测三次都为故障.
    if [ $down -ge 3 ];then
        if [ "$(find $php_fmp_log -mmin -3)" == "" ];then
            #取得进程名对应的所有pid
            pids=$(ps aux | grep ${process} | grep -v "grep" | awk '{print $2}')
            #依次对所有pid执行kill命令
            for i in $pids;do
                kill -9 $i
                kill -9 $i
            done
            #kill完pid后,启动服务
            /etc/init.d/php-fpm start
            #TODO send message
            echo "$(date) return code $code,${process} had been restarted" >> $php_fmp_log
            content='${process} had been restarted from ${current_ip}.'
            curl "http://gunweb.u591.com:83/interface/duanxin/send_monitor_linux.php?game_id=$game_id&content=$content&sign=$sign"
        else
            echo "$(date) ${process} not yet recovery.As it had been restarted in 2 minutes.so this time ignore." >> $php_fmp_log2
        fi
        break
    fi
done