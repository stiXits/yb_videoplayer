$(window).load(makeVideolistVisible)
$(window).load(makePlayerVisible)

function makeVideolistVisible()
{
	$('div.yb_videolist .videoListItem').css('visibility', '');
	removeLoadingBars();
}

function makePlayerVisible()
{
	$('div.yb_videoplayer').css('visibility', '');
	removeLoadingBars();
}

function removeLoadingBars()
{
	$('div.loadingBar').remove();
}
