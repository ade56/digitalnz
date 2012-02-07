$(document).ready(function() {
	page_number = 0;
	var old_search_text = '';
	total_results = 0;
	
	/** User Click to Retrieve Digital New Zealand Archives*/
	$("#digitalNZ_search_button").click(function() {
		
		/** Digital New Zealand API Key Required for Querying */
		var api_key = document.getElementById('api_key').value;
		
		/** Number of Results Required - Higher = slow response - Lower = fast response - */
		var num_results = document.getElementById('num_results').value;
				
		/** User Input Text */
		var search_text = $('#digitalNZ_search_text').attr('value');
		
		/** Content Format Filter */
		var format_filter = $("#digitalNZ_format_filter").attr('value');
		
		if(format_filter != 'All') { search_text += '%20AND%20category:"' + format_filter + '"'; }

		/** Content type Filter */
		var type_filter = $("#digitalNZ_types_filter").attr('value');

		if(type_filter != 'All') { search_text += '%20AND%20dnztype:"' + type_filter + '"'; }
		
		/** Content Copy-Right/Usage Filter */
		var rights_filter = $("#digitalNZ_rights_filter").attr('value');
		
		if(rights_filter != 'All') { search_text += '%20AND%20rights:"' + rights_filter + '"'; } 

		/** Content Provider Filter */
		var content_provider_filter = $("#digitalNZ_content_provider_filter").attr('value');

		if(content_provider_filter != 'All') { search_text += '%20AND%20content_partner:"' + content_provider_filter + '"'; }

		/** City/Location Filter */
		var location_filter = $("#digitalNZ_location_filter").attr('value');
			
		if(location_filter != 'All') { search_text += '%20AND%20placename:"' + location_filter + '"'; }

		/** Date Range Filter */
		var from_date = $.trim($("#date_from").attr('value'));
		var to_date = $.trim($("#date_to").attr('value'));

		if(from_date == '' && to_date != '') { search_text += '%20AND%20year:[1500 TO ' + to_date + ']'; }
		if(from_date != '' && to_date == '') { search_text += '%20AND%20year:[' + from_date + ' TO 2020]'; }
		if(from_date != '' && to_date != '') { search_text += '%20AND%20year:[' + from_date + ' TO ' + to_date + ']'; }
		
		if(old_search_text != search_text) { page_number = 0; }
		
		/** Primarily used by 'prev' and 'next' feature to retrieve next set of results */
		var start = page_number;
			
		/** URL Request to Digital New Zealand */
		var url = 'http://api.digitalnz.org/records/v2.json'
				   + '?api_key=' + api_key  
				   + '&search_text=_' + search_text
				   + '&num_results=' + num_results 
				   + '&start=' + page_number
				   + '&jsonp=jsonpcallback&callback=?';
		
		/** JSON retrieved from Digital New Zealand. Jsonpcallback function called upon 'success'*/
		$.getJSON(url);
		
		old_search_text = search_text;
    });
	
	$('.digitalNZ_nav_button').hide();
	$('.digitalNZ_sel_button').hide();
	
	/** Next Five Results Retrieved Upon 'Next' click */	
	$('#digitalNZ_next_results').click(function() {
	    if((page_number + parseInt($('#num_results').attr('value'))) < total_results) page_number = page_number + parseInt($('#num_results').attr('value')); 
	    $("#digitalNZ_search_button").trigger('click');
	});
	
	/** Previous Five Results Retrieved Upon 'prev' Click */
	$('#digitalNZ_prev_results').click(function(){
		if(page_number > 0)  page_number -= parseInt($('#num_results').attr('value')); 
		$("#digitalNZ_search_button").trigger('click');
	});

}); 

function selectAllItems(){
 	$(".checkbox").each(function() {
  		if(!$(this).is(':checked')) $(this).attr('checked', true); 
 	});
}

/** Named callback function from the ajax call when search clicked */
function jsonpcallback(data) { 
	if($(data).attr('result_count') == 0) {
		$("#digitalNZ_search_pane").html('');
		$('.digitalNZ_nav_button').hide();
		var results = "<h2>NO RESULTS</h2><h4>Sorry, your search returned no results. Please try again for different keywords.</h4>"
		$("#digitalNZ_search_pane").append(results);
	} else {
		var results = $(data).attr('results');	
		
		$("#digitalNZ_search_pane").html('');
		
		var select_all_button = "<input type='button' class='digitalNZ_sel_button' id='digitalNZ_select_all' onclick='selectAllItems()' value='Select All' />"
		$("#digitalNZ_search_pane").append(select_all_button);
		
		var start_number = page_number + 1;
		var end_number = page_number + parseInt($('#num_results').attr('value'));
		if(end_number > $(data).attr('result_count')) { end_number = $(data).attr('result_count'); }
		var result_count = "<h2 style='text-align:right'>" + start_number + "-" + end_number + " of " + $(data).attr('result_count') + " results</h2>";
		$("#digitalNZ_search_pane").append(result_count);
		total_results = $(data).attr('result_count');
		
		$.each(results, function(key, value){
			
			/** Copy Right Color Scheme */
			var color_code;
			
			if(value.object_copyright == "No known copyright restrictions" || value.object_copyright == "Unknown"){ color_code = 'tag_green.png'; }
		
			else if(value.object_copyright == "Some rights reserved"){ color_code = 'tag_orange.png'; }
			
			else if(value.object_copyright == "All rights reserved"){ color_code = 'tag_red.png'; }
			
			else { color_code = 'tag_green.png'; }
			
			if(value.identifier == '') { value.identifier = value.display_url }
				
			var html = "<div class='digitalNZ_search_item'>" +
							"<input type='checkbox' class='checkbox' name='results_check_box[]' value='" + value.id + "'/>" +
							"<img src='" + value.thumbnail_url + "' class='digitalNZ_thumbnail'/>" + 
							"<div class='result_title_description'>" +
								"<h2><a href='" + value.identifier + "' target='_blank'>" + value.title + "</a></h2>" +
								"<a>" + value.description + "</a>" +
							"</div>" + 
							"<div class='usage_right_icon'><img src='/./omeka/plugins/DigitalNZ/Images/" + color_code +"' />" + "<span>" + value.object_copyright +"</span></div>" + 
						" </div>"; 
			
			$("#digitalNZ_search_pane").append(html);			
		});	
		
		/** If Item Count Exceeds Five than Next/Prev Buttons are Required for Navigation */
		if(parseInt($('#num_results').attr('value')) < $(data).attr('result_count')) $('.digitalNZ_nav_button').show();
		else $('.digitalNZ_nav_button').hide();
	}
}
