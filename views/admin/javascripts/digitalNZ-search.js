$(document).ready(function() {
	page_number = 0;
	total_results = 0;
        previous_search_text = '';
	
	$('.digitalNZ_nav_button').hide();
        
        /** User Click to Retrieve Digital New Zealand Archives*/
	$("#digitalNZ_search_button").click(function() {
		
            /** Digital New Zealand API Key Required for Querying */
            var api_key = $('#api_key').attr('value');
		
            /** Number of Results Required - Higher = slow response - Lower = fast response - */
            var num_results = $('#num_results').attr('value');
                            
            /** User Specified Search Filters are appended to search text string */
            var search_text = getFilterContent();
			
            /** Check whether to apply a new search or not */
            if(previous_search_text != search_text) {
                page_number = 0;
                previous_search_text = search_text;
            }
			
            /** URL Request to Digital New Zealand */
            var url = 'http://api.digitalnz.org/records/v2.json'
				   + '?api_key=' + api_key  
				   + '&search_text=_' + search_text
				   + '&num_results=' + num_results 
				   + '&start=' + page_number
				   + '&jsonp=jsonpcallback&callback=?';
		
            /** JSON retrieved from Digital New Zealand. Jsonpcallback function called upon 'success'*/
            $.getJSON(url);
        });
	
	/** Next Five Results Retrieved Upon 'Next' click */	
	$('#digitalNZ_next_results').click(function() {
	    var page = page_number + parseInt($('#num_results').attr('value'));
            
            if (page < total_results) page_number = page; 

	    $("#digitalNZ_search_button").trigger('click');
	});
	
	/** Previous Five Results Retrieved Upon 'prev' Click */
	$('#digitalNZ_prev_results').click(function(){
            if (page_number > 0) {
                page_number -= parseInt($('#num_results').attr('value')); 
            }
	    $("#digitalNZ_search_button").trigger('click');
	});

}); 

/** Search Filters Specified By User are Appended to Search Text */
function getFilterContent()
{
    /** User Input Text */
    var search_text = $('#digitalNZ_search_text').attr('value');
		
    /** Content Format Filter */
    var format_filter = $("#digitalNZ_format_filter").attr('value');
		
    if (format_filter != 'All') { search_text += '%20AND%20category:"' + format_filter + '"'; }

    /** Content type Filter */
    var type_filter = $("#digitalNZ_types_filter").attr('value');

    if (type_filter != 'All') search_text += '%20AND%20dnztype:"' + type_filter + '"'; 
		
    /** Content Copy-Right/Usage Filter */
    var rights_filter = $("#digitalNZ_rights_filter").attr('value');
    
    if (rights_filter != 'All') { search_text += '%20AND%20rights:"' + rights_filter + '"'; } 

    /** Content Provider Filter */
    var content_provider_filter = $("#digitalNZ_content_provider_filter").attr('value');

    if (content_provider_filter != 'All') { search_text += '%20AND%20content_partner:"' + content_provider_filter + '"'; }

    /** City/Location Filter */
    var location_filter = $("#digitalNZ_location_filter").attr('value');
            
    if (location_filter != 'All') { search_text += '%20AND%20placename:"' + location_filter + '"'; }

    /** Date Range Filter */
    var from_date = $.trim($("#date_from").attr('value'));
    var to_date = $.trim($("#date_to").attr('value'));

    if (from_date == '' && to_date != '') { 
        search_text += '%20AND%20year:[1500 TO ' + to_date + ']'; 
    }
    if (from_date != '' && to_date == '') { 
        search_text += '%20AND%20year:[' + from_date + ' TO 2020]'; 
    }
    if (from_date != '' && to_date != '') { 
        search_text += '%20AND%20year:[' + from_date + ' TO ' + to_date + ']'; 
    }
                
    return search_text;
}

/**  */
function selectAllItems()
{
    $(".checkbox").each(function() {
            if(!$(this).is(':checked')) {
                $(this).attr('checked', true); 
            }
    });
}
