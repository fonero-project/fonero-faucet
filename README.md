# Fonero Faucet

The secure, private, untraceable cryptocurrency.

Copyright (c) 2017-2018, The Fonero Project.

## Install
import fonero-faucet.sql  
edit framework/framework.php framework/assets/jackpot.php framework/assets/payments.php  
crontab -e  
*/28 * * * * /usr/bin/php -f /var/www/framework/assets/payments.php >/dev/null 2>&1  
@daily /usr/bin/php -f /var/www/framework/assets/jackpot.php >/dev/null 2>&1  
