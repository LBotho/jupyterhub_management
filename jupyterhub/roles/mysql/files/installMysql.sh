#!/bin/bash
debconf-set-selections <<< "mysql-server mysql-server/root_password password isen29"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password isen29"
apt-get update && apt-get install mysql-server -y
