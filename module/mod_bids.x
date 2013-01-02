<?xml version="1.0" encoding="iso-8859-1"?>
<mosinstall type="module" version="4.5.2">
	<name>auctionFactory Module</name>
   <author>The Factory Team</author>
   <creationDate>August 2007</creationDate>
   <copyright>(C) 2007 The Factory . All rights reserved.</copyright>
   <license>Commercial</license>
   <authorEmail>contact@thefactory.ro</authorEmail>
   <authorUrl>http://www.thefactory.ro</authorUrl>
   <version>1.5.0</version>
	<description>Module for auctionFactory</description>
<files>
	<filename module="mod_bids">mod_bids.php</filename>
</files>

<params>
	<param name="lang" type="list" label="Language">
		<option value="default">Default</option>
		<option value="de">German</option>
		<option value="fr">French</option>
		<option value="en">English</option>
		<option value="ro">Romanian</option>
	</param>
	<param name="type_display" type="list" label="The type of module">
		<option value="0">Latest Auctions</option>
		<option value="1">Popular Auctions</option>
		<option value="2">Most Valuable Auctions</option>
		<option value="3">Random Auctions</option>
		<option value="4">Featured Auctions</option>
	</param>
	<param name="display_image" type="radio" default="0" label="Display auction image in Module">
			<option value="0">No</option>
			<option value="1">Yes</option>
	</param>
	<param name="image_width" type="text" default="30" label="Image Width" description="" />
	<param name="image_height" type="text" default="30" label="Image Height" description="" />

	<param name="moduleclass_sfx" type="text" default="" label="Module Class Suffix" description="A suffix to be applied to the css class of the module (table.moduletable), this allows individual module styling" />
	<param name="nr_auctions_displayed" type="text" default="5" label="Number of acutions to display" description="Enter a number of auctions to display in the list" />
</params>
</mosinstall>