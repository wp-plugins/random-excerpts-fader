var $j = jQuery.noConflict();

function RandomPostFader_js() {

	var $active = $j('#RandomExcerpts p.active');
	var $duration = $j('#RandomExcerpts #duration').text();
	if (parseInt($duration) < 1000){ $fade = parseInt($duration); 
	} else { $fade = 1000; }
	console.log($fade);
    if ( $active.length == 0 ) $active = $j('#RandomExcerpts p:last');

    var $next =  $active.next('p').length ? $active.next('p')
        : $j('#RandomExcerpts p:first');

    $active.addClass('last-active');

    $next.css({opacity: 0.0})
		.removeClass('hide')
        .addClass('active')
        .animate({opacity: 1.0}, $fade, function() {});
	$active.animate({opacity: 0.0}, $fade, function() {})
            		.removeClass('active last-active');
        
}

$j(function() {
  var $height = $j('#RandomExcerpts p:first').height();
  var $duration = $j('#RandomExcerpts #duration').text();

  $j('#RandomExcerpts p').each(function(index) {
    if ($j(this).height() > $height ) {
    	$height = $j(this).height();
    }
  });
  $j('#RandomExcerpts p:first')
  			.css('opacity','1.0')
  			.removeClass('hide')
  			.addClass('active');
  
  $j('#RandomExcerpts').css('height',$height);
    setInterval( "RandomPostFader_js()", parseInt($duration) );
});