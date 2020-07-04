<?php

const MYSQL_HOST     = '';
const MYSQL_USER     = '';
const MYSQL_PASSWORD = '';
const MYSQL_DB       = '';

function verify_config(){
    return empty(MYSQL_HOST) || empty(MYSQL_USER) || empty(MYSQL_PASSWORD) || empty(MYSQL_DB);
}
