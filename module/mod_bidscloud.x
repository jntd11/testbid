<?xml version="1.0" encoding="utf-8"?>
<install type="module" version="1.5.0">
	<name>Auctions Tag Cloud</name>
	<author>thefactory.ro</author>
	<creationDate>October 2008</creationDate>
	<copyright>(C) 2008 comercial.</copyright>
	<license>comercial</license>
	<authorEmail>thefactory.ro</authorEmail>
	<authorUrl>www.thefactory.ro</authorUrl>
	<version>1.5.0</version>
	<description>Auction Tag Clouds generates a Cloud of the most ocurrences auctions tags.</description>
	<files>
	<filename module="mod_bidscloud">mod_bidscloud.php</filename>
	<filename>index.html</filename>
    <filename>helper.php</filename>
    <filename>tmpl/default.php</filename>
    <filename>tmpl/index.html</filename>
	</files>	
	<params>
		<param name="max_tags" type="text" default="40" label="Maximum number of Tags to display" description="The maximum number of tags to display" />
		<param name="moduleclass_sfx" type="text" default="" label="Module Class Suffix" description="PARAMMODULECLASSSUFFIX" />
	</params>		
	<params group="advanced">
		<param name="cache" type="list" default="1" label="Caching" description="Select whether to cache the content of this module">
			<option value="1">Use global</option>
			<option value="0">No caching</option>
		</param>
		<param name="cache_time" type="text" default="900" label="Cache Time" description="The time before the module is recached" />
	</params>
</install>