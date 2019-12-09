#!/usr/bin/env bash
apt-get update
apt-get install -y --force-yes openssh-server

# SSH
mkdir /var/run/sshd && \
echo 'root:root' | chpasswd && \
sed -i 's/PermitRootLogin without-password/PermitRootLogin yes/' /etc/ssh/sshd_config && \
sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd && \
echo "export VISIBLE=now" >> /etc/profile && \
# SSH

echo 'export TERM=xterm' >> /root/.bashrc
printf "alias cls='clear'\n" >> /root/.bashrc
