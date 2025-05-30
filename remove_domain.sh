#!/bin/bash

cd chat-server/scripts
node remove-domain.js
systemctl restart hnschat
