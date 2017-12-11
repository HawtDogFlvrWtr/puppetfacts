#!/bin/bash
apacheUser=$(ps -ef | egrep '(httpd|apache2|apache)' | grep -v `whoami` | grep -v root | head -n1 | awk '{print $1}')
echo "Current user is $apacheUser. Fixing permissions and creating folders"

# making systems folder
if [ ! -d "credentials" ]; then
  mkdir credentials
else
  echo "credentials folder already exists. skipping"
fi
# making credentials folder
if [ ! -d "systems" ]; then
  mkdir systems
else
  echo "systems folder already exists. skipping"
fi

# changing permissions
chown -R $apacheUser:$apacheUser *
