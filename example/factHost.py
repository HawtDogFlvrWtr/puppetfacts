#!/usr/bin/env python
#
#
# Example puppet fact for factor that will pull all host information if it's set
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

def pullInfo(url, mac):
  # Check if https
  if "https" in hostName or "HTTPS" in hostName:
    jsonReturn = urllib2.urlopen(url+"getInfo.php?macAddress="+mac, context=ctx).read()
  else:
    jsonReturn = urllib2.urlopen(url+"getInfo.php?macAddress="+mac).read()

  return jsonReturn

web_return = pullInfo(hostName, "TEST")
json_object = json.loads(web_return)
for host in json_object:
    print host+"="+json_object[host]
