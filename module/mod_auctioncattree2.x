<?xml version="1.0" encoding="iso-8859-1"?>
<mosinstall type="module" version="4.5.2">
	<name>AF Category Module-2</name>
   <author>The Factory Team</author>
   <creationDate>September 2007</creationDate>
   <copyright>(C) 2007 The Factory . All rights reserved.</copyright>
   <license>Commercial</license>
   <authorEmail>contact@thefactory.ro</authorEmail>
   <authorUrl>http://www.thefactory.ro</authorUrl>
   <version>1.5.0</version>
	<description>Module for auctionFactory</description>
<files>
	<filename module="mod_auctioncattree2">mod_auctioncattree2.php</filename>
</files>

<params>
	<param name="moduleclass_sfx" type="text" default="" label="Module Class Suffix" description="A suffix to be applied to the css class of the module (table.moduletable), this allows individual module styling" />
	<param name="category_counter" type="radio" default="0" label="Enable Category Counter">
		<option value="0">No</option>
		<option value="1">Yes</option>
	</param>
</params>
</mosinstall>