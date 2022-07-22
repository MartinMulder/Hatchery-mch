@extends('layouts.mch.app')

@section('content')
	<x-page-panel title="Badges">
		<x-slot name="actions">
			<x-panel title="Actions">
			@can('create', \App\Models\Badge::class)
			    <a class="btn btn-success btn-xs" href="{{ route('badges.create')  }}">create</a>
			    @endcan
			</x-panel>
		</x-slot>
	        @include('partials.badges')
	</x-page-panel>
@endsection
