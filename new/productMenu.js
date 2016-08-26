

function populateitems(obj)
{
	$('.productpage').delegate('input.fileupload1', 'change', function(){
		  $('form input.fileupload1').last().after($('<input type="file" name="picture[]" class="form-control-file fileupload1" />'));
}
