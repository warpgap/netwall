#!/bin/bash
#script by jiraphat yuenying

wget -O /etc/ssh/sshd_config 'https://raw.githubusercontent.com/warpgap/netwall/master/sshd_config'

service ssh restart
