#!/bin/bash

cd chat-server/scripts
node create-channel.js
systemctl restart hnschat
