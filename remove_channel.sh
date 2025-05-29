#!/bin/bash

cd chat-server/scripts
node remove-channel.js
systemctl restart hnschat
