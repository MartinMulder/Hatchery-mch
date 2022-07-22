@extends('layouts.app')

@section('content')
                   <x-page-panel title="Badge: {{ $badge->name }}">
			<x-slot name="actions">
                            @can('update', $badge)
                                <a class="btn btn-primary btn-xs" href="{{ route('badges.edit', ['badge' => $badge])  }}">edit</a>
                            @endcan
                            @can('delete', $badge)
                                {!! Form::open(['method' => 'delete', 'route' => ['badges.destroy', 'badge' => $badge], 'class' => 'deleteform']) !!}
                                <button class="btn btn-danger btn-xs" name="delete-resource" type="submit" value="delete" data-bs-toggle="modal" data-bs-target="#confirm-delete">delete</button>
                                {!! Form::close() !!}
                            @endcan
                        </x-slot>
                            <div class="col-md-12 clearfix">
                                <div class="form-group">
                                    {{ Form::label('added', 'Badge added', ['class' => 'control-label']) }}:
                                    {{ $badge->created_at }}
                                </div>
                                @if($badge->constraints)
                                <div class="form-group">
                                    {{ Form::label('constraints-readonly', 'Constraints', ['class' => 'control-label']) }}:
                                    {{ Form::textarea('constraints', $badge->constraints, ['class' => 'form-control', 'id' => 'constraints-readonly']) }}
                                </div>
                                @endif
                                @if($badge->commands)
                                    <div class="form-group">
                                        {{ Form::label('commands-readonly', 'Commands', ['class' => 'control-label']) }}:
                                        {{ Form::textarea('commands', $badge->commands, ['class' => 'form-control', 'id' => 'commands-readonly']) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{ Form::hidden('extension', 'sh', ['id' => 'extension']) }}
                        @if($projects->total() > 0)
                            <h3>Projects:</h3>
                            @include('partials.projects')
                        @endif
		</x-page-panel>
@include('modals.confirm-delete', ['name' => $badge->name])

@endsection
@section('script')
    <script type="text/javascript">
        @auth
            window.keymap = "{{ Auth::user()->editor }}";
        @endauth
				// Delete resource
    </script>
@endsection
