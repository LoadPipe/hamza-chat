#!/bin/bash

cd hnschat-server
node create-channel.js
systemctl restart hnschat
