# <?php return; ?>

defaults
tls on
tls_trust_file /etc/pki/tls/certs/ca-bundle.crt
logfile ~/msmtp.log

account default
host smtp.sendgrid.net
from newsroom@i-newswire.com
auth on
user inewswire
password icontacts2013777
protocol smtp
timeout 15



