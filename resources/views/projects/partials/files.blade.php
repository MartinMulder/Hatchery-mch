<div class="container-fluid g-0">
	<div class="row g-0 mch-font-color border-bottom">
		<div class="col-4">
			File
		</div>
		<div class="col-3 d-none d-xxl-block">
			Last edited	
		</div>
		<div class="col-1">
			Size
		</div>
		<div class="col-2 d-none d-xxl-block">
			Process
		</div>
        	@if(Auth::user()->can('update', $project->versions->last()->files()->first()))
		<div class="col">
		    {!! Form::open(['method' => 'get', 'route' => 'files.create']) !!}
		    {{ Form::hidden('version', $project->versions->last()->id) }}
		    <button class="btn btn-success" type="submit" value="add"><span class="bi-file-earmark-plus"></span></button>
		    {!! Form::close() !!}
		</div>
		@endif
		@php
			$hasIcon = false;
		@endphp
	</div>
	@forelse($project->versions->last()->files()->paginate() as $file)
	<div class="row g-0">
		<div class="col-4">
			@if($file->editable && Auth::user()->can('update', $file))
			<a href="{{ route('files.edit', ['file' => $file->id]) }}">{{ $file->name }}</a>
			@else
			<a href="{{ route('files.show', ['file' => $file->id]) }}">{{ $file->name }}</a>
			@endif
			@if ($file->name === 'icon.py')
			    @php
				$hasIcon = true;
			    @endphp
			@endif
		</div>
		<div class="col-3 d-none d-xxl-block">
		    {{ $file->updated_at }}
		</div>
		<div class="col-1">
			{{ $file->size_formatted }}
		</div>
		<div class="col-2 d-none d-xxl-block">
			@can('process', $file)
				{!! Form::open(['method' => 'post', 'route' => ['files.process', 'file' => $file->id]]) !!}
				<button class="btn btn-success btn-xs" name="process-resource" type="submit" value="proccess"  style="width: 48px;">synth</button>
				{!! Form::close() !!}
			@endcan
		</div>
		@can('delete', $file)
		<div class="col">
			{!! Form::open(['method' => 'delete', 'route' => ['files.destroy', 'file' => $file->id]]) !!}
			<button class="btn btn-danger" name="delete-resource" type="submit" value="delete" data-bs-toggle="modal" data-bs-target="#confirm-delete"><span class="bi-trash3"></span></button>
			{!! Form::close() !!}
		</div>
		@endcan
	</div>
	@empty
	<div class="row g-0">
	    <div class="col">No files yet</div>
	</div>
	@endforelse
</div>
<table class="table table-striped table-sm">
    <thead>
    <tr>
        <th>File</th>
        <th>Last edited</th>
        <th>Size</th>
        <th>Process</th>
        <th>
        @if(Auth::user()->can('update', $project->versions->last()->files()->first()))
            {!! Form::open(['method' => 'get', 'route' => 'files.create']) !!}
            {{ Form::hidden('version', $project->versions->last()->id) }}
            <button class="btn btn-success" type="submit" value="add"><span class="bi-file-earmark-plus"></span></button>
            {!! Form::close() !!}
        @endif
        </th>
    </tr>
    </thead>
    <tbody>
    @php
        $hasIcon = false;
    @endphp
    @forelse($project->versions->last()->files()->paginate() as $file)
        <tr>
            <td>
                @if($file->editable && Auth::user()->can('update', $file))
                <a href="{{ route('files.edit', ['file' => $file->id]) }}">{{ $file->name }}</a>
                @else
                <a href="{{ route('files.show', ['file' => $file->id]) }}">{{ $file->name }}</a>
                @endif
                @if ($file->name === 'icon.py')
                    @php
                        $hasIcon = true;
                    @endphp
                @endif
            </td>
            <td>{{ $file->updated_at }}</td>
            <td>{{ $file->size_formatted }}</td>
            <td>
        @can('process', $file)
                {!! Form::open(['method' => 'post', 'route' => ['files.process', 'file' => $file->id]]) !!}
                <button class="btn btn-success btn-xs" name="process-resource" type="submit" value="proccess"  style="width: 48px;">synth</button>
                {!! Form::close() !!}
        @endcan
            </td>
            <td>
		@can('delete', $file)
                {!! Form::open(['method' => 'delete', 'route' => ['files.destroy', 'file' => $file->id]]) !!}
                <button class="btn btn-danger" name="delete-resource" type="submit" value="delete" data-bs-toggle="modal" data-bs-target="#confirm-delete"><span class="bi-trash3"></span></button>
		        {!! Form::close() !!}
		@endcan
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6">No files yet</td>
        </tr>
    @endforelse
    </tbody>
</table>
{{ $project->versions->last()->files()->paginate()->render() }}
@if(Auth::user()->can('update', $project->versions->last()->files()->first()))
@if (!$hasIcon)
    {!! Form::open(['method' => 'get', 'route' => 'files.create-icon']) !!}
    {{ Form::hidden('version', $project->versions->last()->id) }}
    <button class="btn btn-success btn-xs" type="submit" value="add">Add icon</button>
    {!! Form::close() !!}
@endif
<div>Upload Python, Text or PNG image files.</div>
{!! Form::open([ 'route' => [ 'files.upload', 'version' => $project->versions->last()->id ], 'files' => true, 'enctype' => 'multipart/form-data', 'id' => 'uploader' ]) !!}
    <div class="fallback">
        <input name="file" type="file" />
        <input type="submit" />
    </div>
{!! Form::close() !!}
@endif
<div id="confirm-delete" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Are you sure you want to delete this?
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
    window.onload = function() {
        const uploader = new window.Dropzone("#uploader",{
            maxFilesize: 32,
        });
        const d = document.getElementById("uploader");
        d.className += " dropzone";
    }

    // Delete resource
    $('button[name="delete-resource"]').on('click', function (e) {
        e.preventDefault()
        const $form = $(this).closest('form')
        $('#confirm-delete').modal({ backdrop: 'static', keyboard: false }).one('click', '#delete', function (e) {
            $form.trigger('submit')
        })
    })

    // Process resource
    $('button[name="process-resource"]').on('click', function (e) {
        e.preventDefault()
        const form = $(this).closest('form')
        $.post(form.attr('action'), {
            _token: window.Laravel.csrfToken
        });
    });
</script>
@endsection
