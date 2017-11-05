;<?php
;exit();
;/* Configuration Ini File

[application]
version = 1.0
appname = EnVuetifyApp
logfile = /tmp/error.log
loglevel = trace

[preload]
libs = Curl,jwt-auth,Memcache

[run]
defaultController = Main
defaultAction = start
autoLoadModels = true
enableErrorLog = false
enableCache = false
enableRest = true


[database]
engine = mysql
server = localhost
username = root
password = 
database = test


[memcached]
server = localhost
port = 11211
expiration = 30

