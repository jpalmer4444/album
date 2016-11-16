#!/bin/sh
# 
# File:   script.sh
# Author: jpalmer
#
# Deploys pricing app on staging server from ~/ directory.
#
# Created on Feb 27, 2016, 11:52:05 AM
cd ~/;sudo unzip pricing.zip;rm pricing.zip;cd /var/www/html/;sudo rm -rf /vol01/pricing_archive
sudo mv pricing/ /vol01/pricing_archive;sudo mv ~/pricing/ /var/www/html/pricing/;sudo chown apache:apache -R pricing/*;sudo chmod a+r,a+w,a+x -R pricing/*;sudo service httpd restart