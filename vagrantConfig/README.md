# Soliant Starter App Vagrant

## Introduction
* This is a product of Soliant Consulting, Inc. all rights reserved.

## Vagrant

### What it's doing:
* vagrant up
    * Create VM
    * Copy known hosts
    * Move personal keys to VM
    * Sync project folders
    * Setup ip
    * run post.up.configure.zendserver.sh
        * get zend server keys
        * install project extensions to zend server
        * install vhosts
        * restart zend server
    * run post.up.configure.starterapp.sh
        * compose project
        * set default configuration
        * load db
        * deploy phpMyAdmin

### File Order
* Vagrantfile
* post.up.configure.starterapp.sh
* post.up.configure.zendserver.sh
