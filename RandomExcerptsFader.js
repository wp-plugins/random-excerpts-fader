var $j = jQuery.noConflict();

function RandomPostFader_js() {

	var active = $j('#RandomExcerpts p.active');
	var duration = $j('#RandomExcerpts #duration').text();
	if (parseInt(duration) < 1000){ 
		fade = parseInt(duration); 
	} else { 
		fade = 1000; 
	}
    if ( active.length == 0 ){
	    active = $j('#RandomExcerpts p:last');
	}
    var next =  ($j(active).next('p').length != 0) ? $j(active).next('p') : $j('#RandomExcerpts p:first');

    $j(active).fadeOut(fade, function(){
		$j(next).addClass('active')
		$j(next).fadeIn(fade);
		$j(active).removeClass('active');    
    });

        
}

$j(function() {
  var pHeight = $j('#RandomExcerpts p:first').innerHeight();
  var duration = $j('#RandomExcerpts #duration').text();
  $j('#RandomExcerpts p:first').addClass('active');

   setInterval( "RandomPostFader_js()", parseInt(duration) );
});