#!/usr/bin/env bash

sudo pkill -u $1
sleep 5
sudo userdel -r $1
