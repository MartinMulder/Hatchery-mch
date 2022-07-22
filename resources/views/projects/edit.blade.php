@extends('layouts.app')

@section('content')
	<x-page-panel title="{{ $project->name }}">
		<x-slot name="title">
                    @if($project->git)
                        <img src="{{ asset('img/git.svg') }}" alt="Git revision: {{ $project->git_commit_id}}" class="collab-icon" />
                    @endif
                    @if(!$project->collaborators->isEmpty())
                        <img src="{{ asset('img/collab.svg') }}" alt="{{ $project->collaborators()->count() . ' ' . \Illuminate\Support\Str::plural('collaborator', $project->collaborators()->count()) }}" class="collab-icon" />
                    @endif
		    {{ $project->name }}
		</x-slot>
		<x-slot name="actions">
			<x-panel title="Actions">
                        	<a href="{{ route('projects.show', ['project' => $project]) }}" class="btn btn-default btn-xs">show</a>
                        	@can('rename', $project)
                        	<a href="{{ route('projects.rename', ['project' => $project]) }}" class="btn btn-info btn-xs">rename</a>
                        	@endcan
                        	@can('pull', $project)
                        	<a href="{{ route('projects.pull', ['project' => $project]) }}" class="btn btn-success btn-xs">update</a>
                        	@endcan
                        	@can('delete', $project)
                        	{!! Form::open(['method' => 'delete', 'route' => ['projects.destroy', 'project' => $project->slug], 'class' => 'deleteform']) !!}
                        	<button class="btn btn-danger btn-xs" name="delete-resource" type="submit" data-bs-toggle="modal" data-bs-target="#confirm-delete" value="delete">delete</button>
                        	{!! Form::close() !!}
                        	@endcan
			</x-panel>
			<x-panel title="Files">
                            @include('projects.partials.files')
			</x-panel>
			<x-panel title="Revisions">
                            @include('projects.partials.revisions')
			</x-panel>
                </x-slot>
	
			<div class="row content-row mx-3 p-lg-2 h-100">
				<div class="col-9">
				    <div class="form-group">
					{!! $project->descriptionHtml !!}
				    </div>
				    @if($project->versions->last()->files()->where('name', 'README.md')->exists())
				    <a class="btn btn-success btn-xs" href="{{ route('files.edit', $project->versions->last()->files()->where('name', 'README.md')->first()) }}">Edit README.md</a>
				    @else
				    <a class="btn btn-success btn-xs" href="{{ route('files.create', ['version' => $project->versions->last()->id, 'name' => 'README.md']) }}">Create README.md</a>
				    @endif
				</div>
				<div class="col">
                        {!! Form::open(['method' => 'put', 'route' => ['projects.update', 'project' => $project->slug]]) !!}

                            <div class="form-group @if($errors->has('category_id')) has-error @endif">
                                {{ Form::label('category_id', 'Category', ['class' => 'form-label']) }}
                                {{ Form::select('category_id', \App\Models\Category::where('hidden', false)->pluck('name', 'id'), $project->category_id, ['class' => 'form-control', 'id' => 'category_id']) }}
                            </div>
                            <div class="form-group @if($errors->has('project_type')) has-error @endif">
                                {{ Form::label('project_type', 'Type', ['class' => 'form-label']) }}
                                {{ Form::select('project_type', \App\Models\Badge::$types, $project->project_type, ['class' => 'form-control', 'id' => 'badge_ids']) }}
                            </div>
                            <div class="form-group @if($errors->has('license')) has-error @endif">
                                {{ Form::label('license', 'License', ['class' => 'form-label']) }}
                                {{ Form::select('license', \App\Models\License::where('isDeprecatedLicenseId', 0)->where('isOsiApproved', 1)->pluck('name', 'licenseId'), $project->license, ['class' => 'form-control', 'id' => 'license']) }}
                            </div>
                            <div class="form-group @if($errors->has('min_firmware') || $errors->has('max_firmware')) has-error @endif">
                                {{ Form::label('min_firmware', 'Minimal firmware version', ['class' => 'form-label']) }}
                                {{ Form::text('min_firmware', $project->min_firmware, ['class' => 'form-control', 'id' => 'min_firmware']) }}
                                {{ Form::label('max_firmware', 'Maximum firmware version', ['class' => 'form-label']) }}
                                {{ Form::text('max_firmware', $project->max_firmware, ['class' => 'form-control', 'id' => 'max_firmware']) }}
                            </div>
                            @include('projects.partials.compatibility')
                            @include('projects.partials.dependencies')
                            @include('projects.partials.collaborators')
                            <div class="form-group @if($errors->has('allow_team_fixes')) has-error @endif">
                                {{ Form::label('allow_team_fixes', 'Allow badge.team to apply fixes to code', ['class' => 'form-label']) }}
                                {{ Form::checkbox('allow_team_fixes', $project->allow_team_fixes, ['class' => 'form-control', 'id' => 'allow_team_fixes']) }}
                            </div>
				</div>
                                <div class="text-end">
                                    {{ Form::label('publish', 'Publish', ['class' => 'form-label']) }}
                                    {{ Form::checkbox('publish', 1, null, ['id' => 'publish']) }}
                                    <button type="submit" class="btn btn-default">Save</button>
                                </div>
			</div>


                        {!! Form::close() !!}

                </div>
	</x-page-panel>
@endsection
