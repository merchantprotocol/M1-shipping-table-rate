#!/bin/bash

CWD="$(pwd)/../../.."

CONFIG_FILE="$CWD/app/etc/local.xml"
INDEXER_FILE="$CWD/shell/indexer.php"

PHP_BIN=`which php`

echo "Do you want to completely uninstall the extension?(y/n)"
read UNINSTALL


if [ "$UNINSTALL" == "y" ]; then

	rm -fr $CWD/app/code/local/Innoexts/ShippingTablerate
	rm -f $CWD/app/design/adminhtml/default/default/layout/shippingtablerate.xml
	rm -fr $CWD/app/design/adminhtml/default/default/template/shippingtablerate/
	rm -f $CWD/app/etc/modules/Innoexts_ShippingTablerate.xml
	rm -f $CWD/app/locale/en_US/Innoexts_ShippingTablerate.csv
	rm -fr $CWD/skin/adminhtml/default/default/images/shippingtablerate/
	rm -fr $CWD/skin/adminhtml/default/default/shippingtablerate/
	rm -f $CWD/package.xml	
	
	rm -fr $CWD/var/cache
	
	$PHP_BIN $INDEXER_FILE --reindexall	
fi
