#!/usr/bin/env bash
ADMIN_USER=`whoami`
if ! id "$1" >/dev/null 2>&1; then
	sudo useradd $1 --password niHYAA5jMRuFg --create-home --shell /bin/bash  # Password turing
	sudo usermod -a -G jhub $1
	sudo chown -R $1:$1 /home/$1
fi

if id "$1" >/dev/null 2>&1; then
  exit 0
else
	echo 1
fi
