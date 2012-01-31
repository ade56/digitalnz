<div class="field">
	<label for="per_page">Number of Results Per Page:</label>
	<div class="inputs">
		<input type="text" class="textinput" name="per_page" size="4" value="<?php echo get_option('digitalnz_per_page'); ?>" id="per_page" />
		<p class="explanation">The number of results displayed per page (max 10).</p>
		<p class="explanation" style="color:red">Warning: A higher number of results per page may result in lower response rate.</p> 
	</div>
</div>

<div class="field">
	<label for="default_save_location">Default "Export to KML" Location:</label>
	<div class="inputs">
		<input type="text" class="textinput" name="default_save_location" size="20" value="<?php echo get_option('kml_save_location'); ?>" id="default_save_location" />
		<p class="explanation">The default location where KML files are saved.</p>
	</div>
</div>

<div class="field">
	<label for="api_key">DigitalNZ API Key:</label>
	<div class="inputs">
		<input type="text" class="textinput" name="api_key" size="20" value="<?php echo get_option('digitalnz_api_key'); ?>" id="api_key" />
		<a href="http://www.digitalnz.org/members/sign_up" target="_blank">Don't have a DigitalNZ API key?</a>
		<p class="explanation">In order to query and discover Digital New Zealand content, you must register for a Digital New Zealand API key if you haven't already. The key will look something like this 4a1f7eend7f51869tdn8f7956bqa9d8.</p>
	</div>
</div>

<div class="field">
	<label for="dublin_core">Metadata Standard:</label>
	<div class="inputs">
	<?php
		if(get_option("use_dublin_core")==1)
		{
	?>
			<input type="checkbox" name="dublin_core" id="dublin_core" value="1" checked="true" />
	<?php
		}
		else
		{
	?>
			<input type="checkbox" name="dublin_core" id="dublin_core" value="1" />
	<?php
		}
	?>
		Use Dublin Core Metadata Element Set.
		<p class="explanation" style="color:red">Warning: Converting to Dublin Core metadata standard may result in lost information.</p>
	</div>
</div>

<div class="field">
	<label for="geolocation_plugin">Geolocation Plugin:</label>
	<div class="inputs">
	<?php
		if(plugin_is_active("Geolocation"))
		{
	?>
			<p><b><a href="http://omeka.org/codex/Plugins/Geolocation">Geolocation</a></b>
			<a href="/omeka/admin/plugins/config?name=Geolocation" class="configure-button button">Configure</a></p>
			<p class="explanation">Version 1.2 | By Center for History &amp; New Media</p>
	<?php
		}
		else
		{
	?>
			<p class="explanation" style="color:red">Geolocation Plugin NOT detected. This plugin uses the Geolocation Plugin to geolocate items, which has not been installed properly. Click <a href="http://omeka.org/add-ons/plugins/">here</a> to download and install the plugin.</p>
	<?php
		}
	?>
	</div>
</div>

<div class="field">
	<label for="">DigitalNZ Terms of Service: </label>
	<div class="inputs">
		<p class="explanation"><a href="/omeka/admin/plugins/config?name=Geolocation" class="configure-button button">Update</a></p>
	</div>
</div>
