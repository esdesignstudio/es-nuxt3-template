#!/bin/sh
ssh-keygen -t rsa -b 4096 -C "hi@e-s.tw" -N "" -f ~/.ssh/id_rsa
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/id_rsa