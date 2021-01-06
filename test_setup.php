<?php
/**
 * This is only used for our CI system.
 * Obviously, don't use this for anything else
 * or run it in any environment that isn't an
 * ephemeral test system.
 */

$pg_host = getenv('POSTGRES_HOST');
$pg_port = getenv('POSTGRES_PORT');

$connection_string = "host=$pg_host port=$pg_port dbname=fsatest user=fsatest";
$handle = pg_connect($connection_string);

pg_query($handle, 'CREATE DATABASE fsatest;');
pg_query($handle, 'CREATE USER fsatest WITH SUPERUSER PASSWORD "test123";');
pg_query($handle, 'GRANT ALL PRIVILEGES ON DATABASE "fsatest" TO fsatest;');

pg_close($handle);