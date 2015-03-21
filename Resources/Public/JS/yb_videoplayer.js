$(window).load(makeVideolistVisible)
$(window).load(makePlayerVisible)

function makeVideolistVisible()
{
	$('div.yb_videolist .videoListItem').css('visibility', '');
}

function makePlayerVisible()
{
	$('div.yb_videoplayer').css('visibility', '');
}
