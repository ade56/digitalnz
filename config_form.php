<div class="field">
    <label for="per_page">Number of Results Per Page:</label>
    <div class="inputs">
        <input type="text" class="textinput" name="per_page" size="4" value="<?php echo get_option('digitalnz_per_page'); ?>" id="per_page" />
        <p class="explanation">The number of results displayed per page (max 10).</p> 
    </div>
</div>

<div class="field">
    <label for="api_key">DigitalNZ API Key:</label>
    <div class="inputs">
        <input type="text" class="textinput" name="api_key" size="20" value="<?php echo get_option('digitalnz_api_key'); ?>" id="api_key" />
        <a href="http://www.digitalnz.org/getting-started" target="_blank">Don't have a DigitalNZ API key?</a>
        <p class="explanation">In order to query and discover DigitalNZ content, you must register for a Digital New Zealand API key if you haven't already. The key will look something like this 4a1f7eend7f51869tdn8f7956bqa9d8.</p>
    </div>
</div>

<div class="field">
    <label for="dublin_core">Metadata Standard:</label>
    <div class="inputs">
        <?php echo __v()->formCheckbox('digitalnz_use_dublin_core', true, array('checked'=>(boolean)get_option('digitalnz_use_dublin_core'))); ?>
        Use Dublin Core Metadata Element Set.
        <p class="explanation">Leave this box unticked if you wish to use DigitalNZ metadata standard. Converting to Dublin Core metadata standard may result in lost information.</p>
    </div>
</div>

<div class="field">
    <label for="terms_of_service">DigitalNZ Terms of Service:</label>
    <div class="inputs">
        <div style='float:left'>
            <?php echo __v()->formCheckbox('digitalnz_terms_of_use', true, array('checked'=>(boolean)get_option('digitalnz_terms_of_use'))); ?>
            I accept Digital New Zealand's <a href="http://www.digitalnz.org/terms-of-use" target="_blank">Terms of Use Agreement</a>	
        </div>
        <div style='text-align:right'>
            <a href="/omeka/admin/digital-nz/index/refresh/" class="configure-button button">Update</a>
            <p class="explanation">In order to use DigitalNZ search services, you must first agree to the Terms.</p>
            <p class="explanation" style="color:red">Note: When imported items are older than 30 days click 'Update' to refresh the content.</p> 
        </div>
    </div>
</div>