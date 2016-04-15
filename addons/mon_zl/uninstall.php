<?php

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_zl').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_zl_user').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_zl_friend').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_zl_setting').";");