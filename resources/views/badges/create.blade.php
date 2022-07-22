@extends('layouts.app')

@section('content')
	<x-page-panel title="Add a badge">
		<div class="col-md-12 clearfix">
		    {!! Form::open(['method' => 'post', 'route' => 'badges.store', 'id' => 'content_form']) !!}

		    <div class="form-group @if($errors->has('name')) has-error @endif">
			{{ Form::label('name', 'Badge name', ['class' => 'control-label']) }}
			{{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
		    </div>

		    <h3>Optional:</h3>

		    <div class="form-group @if($errors->has('constraints')) has-error @endif">
			{{ Form::label('constraints', 'Constraints', ['class' => 'control-label']) }}
			{{ Form::textarea('constraints', null, ['class' => 'form-control', 'id' => 'constraints']) }}
		    </div>

		    <div class="form-group @if($errors->has('commands')) has-error @endif">
			{{ Form::label('commands', 'Commands', ['class' => 'control-label']) }}
			{{ Form::textarea('commands', null, ['class' => 'form-control', 'id' => 'commands']) }}
			{{ Form::hidden('extension', 'sh', ['id' => 'extension']) }}
		    </div>

		    <div class="float-end">
			<button type="submit" class="btn btn-default bg-palette3">Save</button>
		    </div>

		    {!! Form::close() !!}
		</div>
	</x-page-panel>
@endsection

@section('script')
    <script>
        window.keymap = "{{ Auth::user()->editor }}";
    </script>
@endsection
