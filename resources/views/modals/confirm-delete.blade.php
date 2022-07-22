<div id="confirm-delete" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
	    <div class="modal-body">
	        Are you sure you want to delete {{ $name }}?
  	    </div>
	    <div class="modal-footer">
	        <button type="button" data-bs-dismiss="modal" class="btn btn-danger" id="delete">Delete</button>
	        <button type="button" data-bs-dismiss="modal" class="btn btn-default">Cancel</button>
	    </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
	$('button[name="delete-resource"]').on('click', function (e) {
		e.preventDefault()
		var $form = $(this).closest('form')
		$('#confirm-delete').modal({ backdrop: 'static', keyboard: false }).one('click', '#delete', function (e) {
			$form.trigger('submit')
		})
	})
    </script>
@endsection
