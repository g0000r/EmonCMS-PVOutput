# EmonCMS-PVOutput
For anyone who has a monitoring unit from https://shop.openenergymonitor.com/, this script will allow you to take values from the 'Feeds' (within EmonCMS) and push them to PVOutput.org

The script can sit on the unit under /home/pi

The script recognises feeds by their name.
In my case, I was importing data from my inverter via MQTT to 'Solar' feed.
I used the 2nd CT clamp and put it on the power line to my house's AC
Voltage was taken from the VRMS data feed.

PVOutput allows for custom data feeds for v7 - v12.
As you can see from the script, I am using 4 of them.

These custom feeds can be added/deleted from the script (lines 67-78) and it will still function.

Configuration is as simple as 

1. Get your API key from PVoutput and insert it into line 9
2. Get your System ID from PVOutput and insert it into line 12
3. Line 15 is the local IP of your EmonCMS device
4. Line 18 is the API read key for yoru EmonCMS device.  This can be found on the 'My Account' page of your EmonCMS unit 
5. Edit the IP address on your EmonCMS device on line 25

It's now ready for testing.

Copy the script to the device using FTP 

to run, 
php -f /path/to/file.php
You should get a 'String(20) "OK 200: Added Status" response.
Check PVOutput.org to see if your entry is there (takes about 10 seconds to show up)

When you're happy with the result, add a cron job.
1. Crontab -e
2. */5 * * * * /usr/bin/php -q /home/pi/SCRIPT_NAME.php > /dev/null

This will run the script every 5 minutes.
