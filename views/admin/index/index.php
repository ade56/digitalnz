<?php 
    head(array('title' => 'DigitalNZ', 'bodyclass' => 'primary')); 	
?>

<h1>Digital New Zealand Import</h1>

<div id='primary'>
    <h2>Search DigitalNZ Content</h2>
    <p>Search DigitalNZ content by specifying a search query below. A list of DigitalNZ items will appear once the
        'search' button is clicked. Selecting the items and check-box and clicking 'import' will save the item to Omeka.</p>
		
    <?php
        // Display success message or error message
        $message = $this->escape($this->message);
        if($message == 'success') echo '<div class="success">Item(s) successfully added!</div>';
        if($message == 'error') echo '<div class="error">No item was selected to import!</div>';	
        if($message == 'error_key') echo '<div class="error">Invalid Configuration. 
                                          <a href="'.WEB_ROOT.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'config?name=DigitalNZ'.'">Click here</a>
                                          to configure the "Digital New Zealand" plugin.</div>';
    ?>

    <form action="<?php echo html_escape(uri(array('action'=>'add'))); ?>" method="post" accept-charset="utf-8">
        <div class="field">
            <label for="digitalNZ_search_text">Text Input</label>   
            <div class="inputs">         
                <?php 
                    echo $this->formText('digitalNZ_search_text', null, array('class' => 'textinput'));
                    if ($this->escape($this->message) == 'error_key') {
                        echo "<input type='button' value='Search' id='digitalNZ_search_button' disabled='disabled' class='configure-button button'/>";
                    } else {
                        echo "<input type='button' value='Search' id='digitalNZ_search_button' class='configure-button button'/>";
                    } 
                ?>
                <a href="http://www.digitalnz.org" target="_blank"><img src="<?php echo WEB_PLUGIN.DIRECTORY_SEPARATOR.'DigitalNZ'.DIRECTORY_SEPARATOR.'Images'.DIRECTORY_SEPARATOR.'DNZ_Logo.jpg'; ?>" alt="DNZ logo" title="DigitalNZ" style="float:right" /></a>
                <p class="explanation">Input text above and select 'search'.</p>  		
            </div>
        </div>

        <div class="field">
            <label for="digitalNZ_format_filter">Formats</label>
            <div class="inputs">
                <select class='digitalNZ_search_filter' id='digitalNZ_format_filter'>
                    <option value="All">All</option>
                    <option value="Images">Images</option>
                    <option value="Audio">Audio</option>
                    <option value="Videos">Videos</option>
                    <option value="Data">Data</option>
                    <option value="Interactives">Interactive</option>
                </select>
                <p class="explanation">Filter by archive format.</p> 
            </div>
        </div>

        <div class="field">
            <label for="digitalNZ_types_filter">Types</label>
            <div class="inputs">
                <select class='digitalNZ_search_filter' id='digitalNZ_types_filter'>
                    <option value="All">All</option>
                    <option value="Artwork">Artwork</option>
                    <option value="Memorabilia">Memorabilia</option>
                    <option value="Magazine">Magazine</option>
                    <option value="People">People</option>
                    <option value="News">News</option>
                    <option value="Specimen">Specimen</option>
                    <option value="Book">Book</option>
                    <option value="Reference">Reference</option>
                </select>
                <p class="explanation">Filter by type information.</p>
            </div>
        </div>

        <div class="field">
            <label for="digitalNZ_rights_filter">Usage Rights</label>
            <div class="inputs">
                <select class='digitalNZ_search_filter' id='digitalNZ_rights_filter'>
                    <option value="All">Not filtered by license</option>			
                    <option value="All rights reserved">All rights reserved</option>
                    <option value="Share">Free to use or share</option>	
                    <option value="Modify">Free to use, share or modify</option>
                    <option value="Use commercially">Use commercially</option>
                    <option value="Unknown">No known copyright restrictions</option>	
                </select>
                <p class="explanation">Filter by copyright information.</p> 
            </div>
        </div>

        <div class="field">
            <label for="digitalNZ_content_provider_filter">Content Provider</label>
            <div class="inputs">
                <select class='digitalNZ_search_filter' id='digitalNZ_content_provider_filter'>
                    <option value="All">All matching content providers</option>
                    <option value="95bFM">95bFM</option>
                    <option value="Accident Compensation Corporation">Accident Compensation Corporation</option>
                    <option value="Alexander Turnbull Library">Alexander Turnbull Library</option>
                    <option value="Archives New Zealand Te Rua Mahara o te Kāwanatanga">Archives New Zealand Te Rua Mahara o te Kāwanatanga</option>
                    <option value="Auckland Art Gallery Toi o Tāmaki">Auckland Art Gallery Toi o Tāmaki</option>
                    <option value="Auckland City Archives">Auckland City Archives</option>
                    <option value="Auckland Libraries">Auckland Libraries</option>
                    <option value="Auckland University of Technology">Auckland University of Technology</option>
                    <option value="Auckland War Memorial Museum Tamaki Paenga Hira">Auckland War Memorial Museum Tamaki Paenga Hira</option>
                    <option value="Audio Foundation">Audio Foundation</option>
                    <option value="Australian Research Online">Australian Research Online</option>
                    <option value="BRANZ">BRANZ</option>
                    <option value="Beacon Pathway Ltd">Beacon Pathway Ltd</option>
                    <option value="British Council NZ">British Council NZ</option>
                    <option value="Buller, Grey and Westland District Libraries">Buller, Grey and Westland District Libraries</option>
                    <option value="Carterton District Library">Carterton District Library</option>
                    <option value="Centre for Housing Research Aotearoa New Zealand">Centre for Housing Research Aotearoa New Zealand</option>
                    <option value="Christchurch Art Gallery Te Puna o Waiwhetu">Christchurch Art Gallery Te Puna o Waiwhetu</option>
                    <option value="Christchurch City Libraries">Christchurch City Libraries</option>
                    <option value="Christchurch Polytechnic Institute of Technology, Te Wānanga Ōtautahi">Christchurch Polytechnic Institute of Technology, Te Wānanga Ōtautahi</option>
                    <option value="Coromandel Community Digital Storytelling Project">Coromandel Community Digital Storytelling Project</option>
                    <option value="Department of Conservation">Department of Conservation</option>
                    <option value="Department of Conservation Te Papa Atawhai">Department of Conservation Te Papa Atawhai</option>
                    <option value="Department of Corrections">Department of Corrections</option>
                    <option value="Department of Labour">Department of Labour</option>
                    <option value="DigitalNZ">DigitalNZ</option>
                    <option value="Down to the Wire">Down to the Wire</option>
                    <option value="Energy Efficiency and Conservation Authority">Energy Efficiency and Conservation Authority</option>
                    <option value="Hamilton City Libraries">Hamilton City Libraries</option>
                    <option value="Housing New Zealand Corporation">Housing New Zealand Corporation</option>
                    <option value="Howick Historical Village">Howick Historical Village</option>
                    <option value="Hurunui District Libraries">Hurunui District Libraries</option>
                    <option value="Hutt City Libraries">Hutt City Libraries</option>
                    <option value="Institute of Environmental Science and Research Ltd (ESR)">Institute of Environmental Science and Research Ltd (ESR)</option>
                    <option value="International Children's Digital Library">International Children's Digital Library</option>
                    <option value="Kawerau District Library">Kawerau District Library</option>
                    <option value="Kete Central Hawke's Bay">Kete Central Hawke's Bay</option>
                    <option value="Kete Hauraki Coromandel">Kete Hauraki Coromandel</option>
                    <option value="Kete Horowhenua">Kete Horowhenua</option>
                    <option value="Kete King Country">Kete King Country</option>
                    <option value="Kete Marlborough">Kete Marlborough</option>
                    <option value="Kete Masterton">Kete Masterton</option>
                    <option value="Kete Selwyn">Kete Selwyn</option>
                    <option value="Kete Tararua">Kete Tararua</option>
                    <option value="Kete Tasman">Kete Tasman</option>
                    <option value="Kowai Archives">Kowai Archives</option>
                    <option value="Land Information New Zealand">Land Information New Zealand</option>
                    <option value="Landcare Research">Landcare Research</option>
                    <option value="Lincoln University">Lincoln University</option>
                    <option value="Manawatu District Council">Manawatu District Council</option>
                    <option value="Massey University">Massey University</option>
                    <option value="Matapihi">Matapihi</option>
                    <option value="Ministry for Culture and Heritage">Ministry for Culture and Heritage</option>
                    <option value="Ministry for the Environment">Ministry for the Environment</option>
                    <option value="Ministry for the Environment (MfE)">Ministry for the Environment (MfE)</option>
                    <option value="Ministry of Agriculture and Forestry">Ministry of Agriculture and Forestry</option>
                    <option value="Ministry of Economic Development">Ministry of Economic Development</option>
                    <option value="Ministry of Education">Ministry of Education</option>
                    <option value="Ministry of Fisheries Te Tautiaki i nga tini a Tangaroa">Ministry of Fisheries Te Tautiaki i nga tini a Tangaroa</option>
                    <option value="Ministry of Health">Ministry of Health</option>
                    <option value="Ministry of Tourism Te Manatū Tāpoi">Ministry of Tourism Te Manatū Tāpoi</option>
                    <option value="Mix &amp; Mash 2011">Mix &amp; Mash 2011</option>
                    <option value="Motu Economic and Public Policy Research">Motu Economic and Public Policy Research</option>
                    <option value="Museum Victoria">Museum Victoria</option>
                    <option value="Museum of New Zealand Te Papa Tongarewa">Museum of New Zealand Te Papa Tongarewa</option>
                    <option value="NZ On Screen">NZ On Screen</option>
                    <option value="National Library of Australia">National Library of Australia</option>
                    <option value="National Library of New Zealand">National Library of New Zealand</option>
                    <option value="Nelson Photo News">Nelson Photo News</option>
                    <option value="New Zealand Book Council">New Zealand Book Council</option>
                    <option value="New Zealand Electronic Text Centre">New Zealand Electronic Text Centre</option>
                    <option value="New Zealand Free Photos">New Zealand Free Photos</option>
                    <option value="New Zealand Parliament - Pāremata Aotearoa">New Zealand Parliament - Pāremata Aotearoa</option>
                    <option value="New Zealand Police">New Zealand Police</option>
                    <option value="New Zealand Taxation">New Zealand Taxation</option>
                    <option value="North Otago Museum">North Otago Museum</option>
                    <option value="Northland Regional Council">Northland Regional Council</option>
                    <option value="Not specified">Not specified</option>
                    <option value="Office of Film and Literature Classification">Office of Film and Literature Classification</option>
                    <option value="Open Polytechnic">Open Polytechnic</option>
                    <option value="Otago Museum">Otago Museum</option>
                    <option value="OurSpace">OurSpace</option>
                    <option value="Palmerston North City Library">Palmerston North City Library</option>
                    <option value="PhotoSales">PhotoSales</option>
                    <option value="Picture Australia">Picture Australia</option>
                    <option value="Porirua City Council">Porirua City Council</option>
                    <option value="Powerhouse Museum">Powerhouse Museum</option>
                    <option value="Public Address">Public Address</option>
                    <option value="Public Address Radio">Public Address Radio</option>
                    <option value="Puke Ariki">Puke Ariki</option>
                    <option value="Raglan and District Museum Inc.">Raglan and District Museum Inc.</option>
                    <option value="Reserve Bank of New Zealand">Reserve Bank of New Zealand</option>
                    <option value="Ross Becker &amp; Moira Fraser">Ross Becker &amp; Moira Fraser</option>
                    <option value="Rotorua Museum of Art &amp; History, Te Whare Taonga O Te Arawa">Rotorua Museum of Art &amp; History, Te Whare Taonga O Te Arawa</option>
                    <option value="Shantytown">Shantytown</option>
                    <option value="Silky Oak">Silky Oak</option>
                    <option value="Statistics New Zealand">Statistics New Zealand</option>
                    <option value="TV3">TV3</option>
                    <option value="Tauranga City Libraries">Tauranga City Libraries</option>
                    <option value="Te Aroha &amp; Districts Museum">Te Aroha &amp; Districts Museum</option>
                    <option value="Te Manawa">Te Manawa</option>
                    <option value="Television New Zealand">Television New Zealand</option>
                    <option value="The Nelson Provincial Museum">The Nelson Provincial Museum</option>
                    <option value="The New Zealand Film Archive Ngā Kaitiaki o Ngā Taonga Whitiāhua">The New Zealand Film Archive Ngā Kaitiaki o Ngā Taonga Whitiāhua</option>
                    <option value="The Phoenix Project">The Phoenix Project</option>
                    <option value="The Prow: ngā kōrero o te tau ihu">The Prow: ngā kōrero o te tau ihu</option>
                    <option value="The Treasury">The Treasury</option>
                    <option value="The University of Auckland Library">The University of Auckland Library</option>
                    <option value="Tourism New Zealand">Tourism New Zealand</option>
                    <option value="University of Canterbury">University of Canterbury</option>
                    <option value="University of Canterbury Library">University of Canterbury Library</option>
                    <option value="University of Otago">University of Otago</option>
                    <option value="University of Otago - National School of Surveying">University of Otago - National School of Surveying</option>
                    <option value="University of Otago Library">University of Otago Library</option>
                    <option value="University of Waikato">University of Waikato</option>
                    <option value="V.C. Browne &amp; Son">V.C. Browne &amp; Son</option>
                    <option value="Victoria University of Wellington">Victoria University of Wellington</option>
                    <option value="Victoria and Albert Museum">Victoria and Albert Museum</option>
                    <option value="Waimakariri District Libraries">Waimakariri District Libraries</option>
                    <option value="Waimate Museum">Waimate Museum</option>
                    <option value="Wairarapa Archive">Wairarapa Archive</option>
                    <option value="Waitangi Tribunal">Waitangi Tribunal</option>
                    <option value="Wanganui Collegiate School Museum">Wanganui Collegiate School Museum</option>
                    <option value="Wellington City Council">Wellington City Council</option>
                    <option value="Wellington City Libraries">Wellington City Libraries</option>
                    <option value="Whanganui Regional Museum">Whanganui Regional Museum</option>
                    <option value="Whitireia Community Polytechnic">Whitireia Community Polytechnic</option>
                    <option value="Wikimedia Commons">Wikimedia Commons</option>
                    <option value="mychillybin">mychillybin</option>
                    <option value="www.whenmyhomeshook.co.nz">www.whenmyhomeshook.co.nz</option>
                </select>
                <p class="explanation">Filter by content provider information.</p>
            </div>
        </div>

        <div class="field">
            <label for="digitalNZ_location_filter">Location</label>
            <div class="inputs">
                <select class='digitalNZ_search_filter' id='digitalNZ_location_filter'>
                    <option value="All">All matching locations</option>
                    <option value="Auckland">Auckland</option>			
                    <option value="Wellington">Wellington</option>
                    <option value="Christchurch">Christchurch</option>
                    <option value="Hamilton">Hamilton</option>
                    <option value="Napier">Napier-Hastings</option>	
                    <option value="Tauranga">Tauranga</option>
                    <option value="Dunedin">Dunedin</option>
                    <option value="Palmerston North">Palmerston North</option>
                    <option value="Nelson">Nelson</option>
                    <option value="Rotorua">Rotorua</option>
                    <option value="New Plymouth">New Plymouth</option>
                    <option value="Whangarei">Whangarei</option>
                    <option value="Invercargill">Invercargill</option>
                    <option value="Wanganui">Whanganui</option>
                    <option value="Gisborne">Gisborne</option>
                </select>
                <p class="explanation">Filter by location information.</p> 
            </div>
        </div>

        <div class="field">
            <label for="digitalNZ_date_filter">Date Range</label>
            <div class="inputs">
                <input type="text" class="textinput" id="date_from" size="5" /> --- 
                <input type="text" class="textinput" id="date_to" size="5" />
                <p class="explanation">Enter a 'year' to filter by date (e.g., 1900).<br />
                                        Leaving one field blank will retrieve all results before or after the year provided.</p> 
            </div>
        </div>

        <div class="field">
            <label for="digitalNZ_collection_list">Collection Option</label>
            <div class="inputs">
                <input type="radio" name="digitalNZ_collection" value="no_collection" checked onclick="new_collection.style.display='none';digitalNZ_collection_select.style.display='none'" />No Collection&nbsp&nbsp&nbsp
                <input type="radio" name="digitalNZ_collection" value="new_collection" onclick="new_collection.style.display='block';digitalNZ_collection_select.style.display='none'" />Create a new collection&nbsp&nbsp&nbsp
                <input type="radio" name="digitalNZ_collection" value="exist_collection" onclick="digitalNZ_collection_select.style.display='block';new_collection.style.display='none'" />Add to an existing collection
                <input type="text" class="textinput" name ="new_collection" id="new_collection" style="display:none;color:#808080" value="Please input a name" onfocus="if(this.value=='Please input a name')this.value=''" onblur="if(this.value=='')this.value='Please input a name'" />
                <select class='digitalNZ_search_filter' name='digitalNZ_collection_select' id='digitalNZ_collection_select' style="display:none">
                    <option value="">Please select a collection</option>
                    <?php
                        foreach($this->collection_array as $key => $value)
                        {
                            echo '<option value="' . $key . '">' . $value .'</option>';
                        }				
                    ?>
                </select>
                <p class="explanation">Select an existing collection or create a new one by specifying the title in the text-box.</p> 
            </div>
        </div>

        <div class="field" id="digitalNZ_search_pane"></div>	

        <div id="backtop"><a href="#">TOP</a></div>

        <div class="input">
            <input type="button" class='digitalNZ_nav_button' id="digitalNZ_prev_results" value="Previous" />
            <input type="button" class='digitalNZ_nav_button' id="digitalNZ_next_results" value="Next" />
            <input type="submit" class="submit" name="submit" id="digitalnz-import" value="Import Item(s)" />
        </div>

        <input type='hidden' name='num_results' id='num_results' value="<?php echo get_option('digitalnz_per_page'); ?>" />
        <input type='hidden' name='api_key' id='api_key' value="<?php echo get_option('digitalnz_api_key'); ?>" />
        <input type='hidden' name='image_src' id='image_src' value="<?php echo WEB_PLUGIN; ?>" />
    </form>
</div>

<?php foot();