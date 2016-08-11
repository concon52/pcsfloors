function removePicture(id, src, type)
{
	$('#removedPictures').append('<input type="hidden" value="' + src + '" name="' + type + '[]">');
	$(id).remove();
}

