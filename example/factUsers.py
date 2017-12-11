#!/usr/bin/env python
#
#
# Example puppet fact for factor that will pull all users passwords and pki information if it's set
# Created by Christopher Phipps 12/11/2017
#
#
import urllib2, ssl
import json
hostName = "https://www.wreckyour.net/puppetfacts/"

# Disable certificate checking
ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

def pullInfo(url):
  # Check if https
  if "https" in hostName or "HTTPS" in hostName:
    jsonReturn = urllib2.urlopen(url+"getInfo.php?allusers", context=ctx).read()
  else:
    jsonReturn = urllib2.urlopen(url+"getInfo.php?allusers").read()

  return jsonReturn

usersJson = pullInfo(hostName)
for users in json.loads(usersJson):
  userName = users['username']
  password = users['password']
  print userName+"_password="+password
  pki = users['pki']
  if pki != '':
    print userName+"_pki="+pki
