-- Create database if not exists
CREATE DATABASE IF NOT EXISTS hnschat;
USE hnschat;



-- Create user and grant privileges
CREATE USER IF NOT EXISTS 'hnschat'@'%' IDENTIFIED BY 'hnschat-password';
ALTER USER 'hnschat'@'%' IDENTIFIED WITH mysql_native_password BY 'hnschat-password';
GRANT ALL PRIVILEGES ON hnschat.* TO 'hnschat'@'%';
FLUSH PRIVILEGES;

-- create a sample domain for use
INSERT INTO domains (
    id,
    domain,
    type,
    created
) VALUES (
    'qj8c539a2jr',
    'hamzamarket',
    'handshake',
    UNIX_TIMESTAMP()
);

INSERT INTO domains (
    id,
    domain,
    type,
    created
) VALUES (
    'abcd12345',
    'yogibear',
    'handshake',
    UNIX_TIMESTAMP()
);

-- Import the database schema
-- Note: You'll need to download and include the actual schema from the SQL file
-- This is a placeholder for the actual schema import
-- SOURCE /docker-entrypoint-initdb.d/hnschat.sql; 