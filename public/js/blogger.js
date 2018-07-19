jQuery(function($){

	var date;
	var categories = new Array();
	var author;



$(document).ready(function(){
	

	$.fn.datepicker.defaults.format = "yyyy-mm-dd";
	$('.datepicker').datepicker({
    
	});

	var datepicker = $.fn.datepicker.noConflict(); 
	$.fn.bootstrapDP = datepicker;   

	getBlogs(date, categories, author);

});

//Apply filter
$('.apply-btn').click(function(){
//refresh div
$("#blogsTable").load(location.href + " #blogsTable");
//get date
date = $('.datepicker').datepicker().val();
//get categories
$.each($("input[name='category[]']:checked"), function() {
  categories.push($(this).val());
});
//get author
author = $('.search-box').val();

getBlogs(date, categories, author);
 categories = [];

});

function getBlogs(date, categories, author){
	$.get( '/blogs',
     	{
     	 'date' : date,
     	  'categories' : categories,
     	  'author' : author
     	 },
     	  function (response, textStatus, jqXHR){

			// render HTML
			if(response.length != 0){

			response.forEach(function (blog) {
			   
			    $('#blogsTable').append( 
			    	'<div class="jumbotron"><h1 class="display-4">'+
			    	blog.name +'</h1><p class="lead">'+
			    	blog.description + '</p><hr class="my-4"><p>Posted by - '+
			    	blog.author + ', Created on - ' + blog.created_at + ', Categories - ' + '<span><strong>' + 
			    	blog.categories +
			    	'</strong></span></p><p class="lead"><a class="btn btn-primary btn-lg" href="#" role="button">Visit blog</a></p></div>'

					 );
			});
				


			}
     	  	

     	  }
     	);
}




});

function validate_form()
{
valid = true;

if($('input[type=checkbox]:checked').length == 0)
{
    alert ( "ERROR! Please select at least one checkbox" );
    valid = false;
}

return valid;
}