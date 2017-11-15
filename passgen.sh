#!/bin/bash
encPass=$(python2 -c "import crypt; print(crypt.crypt(\"$1\", crypt.mksalt(crypt.METHOD_SHA512)))")
echo $encPass
