<?xml version="1.0" encoding="iso-8859-1"?>
<mosinstall version="1.0.0" type="mambot" group="search">
	<name>Auction Factory searchbot</name>
	<author>TheFactory</author>
	<creationDate>Aug 2008</creationDate>
	<copyright>(C) 2007-2008 The Factory.</copyright>
	<license>Commercial</license>
	<authorEmail>contact@thefactory.ro</authorEmail>
	<authorUrl>thefactory.ro</authorUrl>
	<version>1.5.0</version>
	<description>Allows Searching of Auctions</description>
	<files>
		<filename mambot="bids.searchbot">bids.searchbot.php</filename>
	</files>
	<params>
		<param name="search_limit" type="text" size="5" default="50" label="Search Limit" description="Number of Search items to return"/>		
		<param name="search_in_description" type="radio" default="2" label="Search in Long Description" description="If Yes, the Long description is searched too">
			<option value="1">Yes</option>
			<option value="2">No</option>
		</param>
	</params>
</mosinstall>
