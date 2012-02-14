/** Named callback function from the ajax call when search clicked */
function jsonpcallback(data) 
{ 
    $("#digitalNZ_search_pane").html('');
    $('.digitalNZ_nav_button').show();
        
    if($(data).attr('result_count') > 0) {
        var results = $(data).attr('results');
        var result_count = $(data).attr('result_count');
        
        /** Page Navigation Number in Top Right of Search Pane */
        var end_number = page_number + parseInt($('#num_results').attr('value'));
            
        if (end_number > result_count) end_number = result_count; 

        /** */
        $("#digitalNZ_search_pane").append("<input type='button' class='digitalNZ_sel_button' id='digitalNZ_select_all' onclick='selectAllItems()' value='Select All' />" +
                                           "<h2 style='text-align:right;'>" + (page_number + 1) + "-" + end_number + " of " + result_count + " results</h2>");
        /** */
        appendResultItems(results);	
        
        total_results = result_count;
    } else {
        /** No Search Results Were Returned */
        $("#digitalNZ_search_pane").html('<h2>NO RESULTS</h2><h4>Sorry, your search returned no results. Please try again for different keywords.</h4>');
        $('.digitalNZ_nav_button').hide();
    }
}

/** */
function appendResultItems(results)
{
    $.each(results, function(key, value){
        /** Copy Right Color Scheme */
        var color_code = getCopyRightIcon(value.object_copyright);
                    
        if(value.identifier == '') { value.identifier = value.display_url }
                            
        var html = "<div class='digitalNZ_search_item'>" +
                            "<input type='checkbox' class='checkbox' name='results_check_box[]' value='" + value.id + "'/>" +
                            "<img src='" + value.thumbnail_url + "' class='digitalNZ_thumbnail'/>" + 
                            "<div class='result_title_description'>" +
                                "<h2><a href='" + value.identifier + "' target='_blank'>" + value.title + "</a></h2>" +
                                "<a>" + value.description + "</a>" +
                            "</div>" + 
                            "<div class='usage_right_icon'><img src='/./omeka/plugins/DigitalNZ/Images/" + color_code +"' />" + "<span>" + value.object_copyright +"</span></div>" + 
                        "</div>"; 
                    
        $("#digitalNZ_search_pane").append(html);			
    });
}

/** */
function getCopyRightIcon(copyright)
{
    var color_code = '';
    
    if (copyright == "No known copyright restrictions" || copyright == "Unknown") { 
        color_code = 'tag_green.png'; 
    } else if(copyright == "Some rights reserved") { 
        color_code = 'tag_orange.png'; 
    } else if(copyright == "All rights reserved") { 
        color_code = 'tag_red.png'; 
    } else if(copyright.object_copyright == "Crown copyright") { 
        color_code = 'tag_gray.png'; 
    } else { 
        color_code = 'tag_green.png'; 
    }
    
    return color_code;
}