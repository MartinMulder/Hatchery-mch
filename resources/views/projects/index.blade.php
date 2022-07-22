@extends('layouts.mch.app')

@section('content')
	<x-page-panel title="Eggs">
		<x-slot name="actions">
			<x-panel title="Actions">
                        	<a href="{{ route('projects.import') }}" class="btn btn-default btn-xs">import</a>
                        	<a href="{{ route('projects.create') }}" class="btn btn-success btn-xs">add</a>
			</x-panel>
			<x-panel title="Filters">
			
				<div class="row g-2 align-items-center title-font">
					<div class="">	
						<label for="#badge" class="col-form-label">Select Bagde</label> 
			    			{{ Form::select('badge_id', \App\Models\Badge::pluck('name', 'slug')->reverse()->prepend('Choose a badge model', ''), $badge, ['id' => 'badge', 'class' => 'form-control']) }}
					</div>
				</div>
				<div class="row g-2 align-items-center title-font">
					<div class="">	
						<label for="#category" class="col-form-label">Select category</label> 
			    			{{ Form::select('category_id', \App\Models\Category::where('hidden', false)->pluck('name', 'slug')->reverse()->prepend('Choose a category', ''), $category, ['id' => 'category', 'class' => 'form-control']) }}
					</div>
				</div>
				<div class="row g-2 align-items-center title-font">
					<div class="row g-2 align-items-center">	
						<label for="#search" class="col-form-label">Search</label> 
					    {{ Form::open(['method' => 'post', 'route' => ['projects.search', 'badge' => $badge, 'category' => $category], 'class' => 'searchform'])  }}
						{{ Form::text('search', $search, ['placeholder' => 'Search']) }}
					    {{ Form::close() }}
					</div>
				</div>
			</ul>
			</x-panel>
		
		</x-slot>


                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Rev</th>
                                <th>Egg</th>
                                <th class="hidden-xs">Content</th>
                                <th class="hidden-xs">Category</th>
                                <th>Collab</th>
                                <th class="hidden-xs"><img src="{{ asset('img/rulez.gif') }}" alt="up" /></th>
                                <th class="hidden-xs"><img src="{{ asset('img/isok.gif') }}" alt="pig" /></th>
                                <th class="hidden-xs"><img src="{{ asset('img/sucks.gif') }}" alt="down" /></th>
                                <th class="hidden-xs"><img src="{{ asset('img/alert.gif') }}" alt="alert" /></th>
                                <th>Last release</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                                <tr>
                                    <td>
                                    @can('update', $project)
                                        <a href="{{ route('projects.edit', ['project' => $project->slug]) }}">{{ $project->name }}</a>
                                    @else
                                        <a href="{{ route('projects.show', ['project' => $project->slug]) }}">{{ $project->name }}</a>
                                    @endcan
                                    </td>
                                    <td>{{ $project->versions()->published()->count() > 0 ? $project->versions()->published()->get()->last()->revision : 'unreleased' }}</td>
                                    <td>{{ $project->size_of_zip_formatted }}</td>
                                    <td class="hidden-xs">{{ $project->size_of_content_formatted }}</td>
                                    <td class="hidden-xs">{{ $project->category }}</td>
                                    <td>
                                    @if($project->git)
                                        <img src="{{ asset('img/git.svg') }}" alt="Git revision: {{ $project->git_commit_id}}" class="collab-icon" />
                                    @endif
                                    @if(!$project->collaborators->isEmpty())
                                        <img src="{{ asset('img/collab.svg') }}" alt="{{ $project->collaborators()->count() . ' ' . \Illuminate\Support\Str::plural('collaborator', $project->collaborators()->count()) }}" class="collab-icon" />
                                    @endif
                                    </td>
                                    <td class="hidden-xs">{{ $project->votes->where('type', 'up')->count() }}</td>
                                    <td class="hidden-xs">{{ $project->votes->where('type', 'pig')->count() }}</td>
                                    <td class="hidden-xs">{{ $project->votes->where('type', 'down')->count() }}</td>
                                    <td class="hidden-xs">{{ $project->warnings->count() }}</td>
                                    <td>{{ $project->versions()->published()->count() > 0 ? $project->versions()->published()->get()->last()->updated_at : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No Eggs published yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($badge && $category && $search)
                        {{ $projects->appends(['badge' => $badge, 'category' => $category, 'search' => $search])->links() }}
                    @elseif ($badge && $category)
                        {{ $projects->appends(['badge' => $badge, 'category' => $category])->links() }}
                    @elseif ($search && $category)
                        {{ $projects->appends(['search' => $search, 'category' => $category])->links() }}
                    @elseif ($badge && $search)
                        {{ $projects->appends(['badge' => $badge, 'search' => $search])->links() }}
                    @elseif ($badge)
                        {{ $projects->appends(['badge' => $badge])->links() }}
                    @elseif ($category)
                        {{ $projects->appends(['category' => $category])->links() }}
                    @else
                        {{ $projects->links() }}
                    @endif
	</x-page-panel>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
          $('#badge').change(function () {
            if ($(this).val()) {
              window.location.href = '{{ $search ? route('projects.search') : route('projects.index') }}?{!! ($category ? "category=$category&" : "") !!}{!! ($search ? "search=$search&" : "") !!}badge=' + $(this).val();
            } else {
              window.location.href = '{{ $search ? route('projects.search') : route('projects.index')  . ($category ? "?category=$category" : "") . ($search ? ($category ? '&' : '?') . "search=$search" : "") }}';
            }
          })
          $('#category').change(function () {
            if ($(this).val()) {
              window.location.href = '{{ $search ? route('projects.search') : route('projects.index')  }}?{!! ($badge ? "badge=$badge&" : "") !!}{!! ($search ? "search=$search&" : "") !!}category=' + $(this).val();
            } else {
              window.location.href = '{{ $search ? route('projects.search') : route('projects.index') . ($badge ? "?badge=$badge" : "") . ($search ? ($badge ? '&' : '?') . "search=$search" : "")  }}';
            }
          })
        })
    </script>
@endsection
