<?xml version="1.0" encoding="UTF-8"?>
<cbinstall version="1.0.0" type="plugin" group="user">
	<name>Auction Factory Plugin - My Auctions</name>
	<author>The Factory</author>
	<creationDate></creationDate>
	<copyright>(C) 2006 thefactory.ro</copyright>
	<license>Commercial</license>
	<authorEmail>contact@thefactory.ro</authorEmail>
	<authorUrl>www.thefactory.ro</authorUrl>
	<version>1.5.0</version>
	<description>
		Tab Plugin for displaying User Auctions from Auction Factory
	</description>
	<files>
		<filename plugin="auction_my_auctions.plugin">auction_my_auctions.plugin.php</filename>
	</files>
	<params>		
	</params>
	<tabs>
		<tab name="My Auctions" description="This tab is just a basic tab." class="getmyauctionsTab" fields="0" position="cb_tabmain" displaytype="tab">
			<params>
			</params>
			<fields>
			</fields>
		</tab>
	</tabs>
	<install>
		<queries>
		</queries>
	</install>
	<uninstall>
	</uninstall>
</cbinstall>