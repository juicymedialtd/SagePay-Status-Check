SagePay Status Check
=============
This is a simple script to check remote access to the SagePay server.
We have in the past experienced connectivity issues that have been difficult to isolate so this was created to make sure that we know where to look for fault.

Usage
-----
Add a CRON at any frequency pointing to the script:
/usr/bin/wget -q http://www.testdomain.com/test-sagepay/checksagepay.php

Update your own e-mail address too.

Copyright Juicy Media Ltd, Peter Davies 01/01/2011