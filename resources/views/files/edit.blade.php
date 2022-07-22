@extends('layouts.mch.app')

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


                            {!! Form::open(['method' => 'put', 'route' => ['files.update', 'file' => $file->id], 'id' => 'content_form']) !!}

                            <div class="input-group @if($errors->has('file_content')) has-error @endif">
                                {{ Form::label('file_content', 'Content', ['class' => 'control-label']) }}
                                {{ Form::textarea('file_content', $file->content, ['class' => 'form-control', 'id' => 'content']) }}
                                {{ Form::hidden('extension', $file->extension, ['id' => 'extension']) }}
                            </div>

                            <div class="float-end">
                                @if($file->lintable)
                                <button class="lint-button btn btn-default">Lint</button>
                                @endif
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>

                            {!! Form::close() !!}



                    @if($file->name === 'icon.py')
                    <div class="row" id="pixels">
                        <div class="col-md-4">
                            <table>
                                @for($r=0; $r < 8; $r++)
                                <tr id="row{{ $r }}">
                                    @for($p=0; $p < 8; $p++)
                                    <td id="row{{$r}}pixel{{$p}}" class="clickable"></td>
                                    @endfor
                                </tr>
                                @endfor
                            </table>
                        </div>
                        <div class="col-md-8">
                            <span class="colour-container">
                                <a href="#" id="colour"></a>
                            </span>
                            <div id="frames">
                                <a onclick="window.addFrame()" class="frames btn btn-success">+</a>
                            </div>
                        </div>
                    </div>
                    @endif

				</div>
				<div class="col">
                        {!! Form::open(['method' => 'put', 'route' => ['projects.update', 'project' => $project->slug]]) !!}

				<div class="row">
					<div class="@if($errors->has('category_id')) has-error @endif">
					{{ Form::label('category_id', 'Category', ['class' => 'form-label mch-font-color col-4']) }}
					{{ Form::select('category_id', \App\Models\Category::where('hidden', false)->pluck('name', 'id'), $project->category_id, ['class' => 'form-select col-8', 'id' => 'category_id']) }}
					</div>
				</div>
				<div class="row">
		                        <div class="@if($errors->has('project_type')) has-error @endif">
                	                {{ Form::label('project_type', 'Type', ['class' => 'form-label mch-font-color col-4']) }}
                        	        {{ Form::select('project_type', \App\Models\Badge::$types, $project->project_type, ['class' => 'form-select col-8', 'id' => 'badge_ids']) }}
                            		</div>
				</div>
				<div class="row">
		                        <div class="@if($errors->has('license')) has-error @endif">
                	                {{ Form::label('license', 'License', ['class' => 'form-label mch-font-color col-4']) }}
                        	        {{ Form::select('license', \App\Models\License::where('isDeprecatedLicenseId', 0)->where('isOsiApproved', 1)->pluck('name', 'licenseId'), $project->license, ['class' => 'form-select col-8', 'id' => 'license']) }}
                            		</div>
				</div>
				<div class="row">
		                        <div class="input-group @if($errors->has('min_firmware') || $errors->has('max_firmware')) has-error @endif">
					<div class="col">
	                	                {{ Form::label('min_firmware', 'Minimal firmware version', ['class' => 'form-label mch-font-color']) }}
        	                	        {{ Form::text('min_firmware', $project->min_firmware, ['class' => 'form-control', 'id' => 'min_firmware']) }}
					</div>
					<div class="col">
	                                	{{ Form::label('max_firmware', 'Maximum firmware version', ['class' => 'form-label mch-font-color']) }}
        	                        	{{ Form::text('max_firmware', $project->max_firmware, ['class' => 'form-control', 'id' => 'max_firmware']) }}
					</div>
				</div>
                            </div>
			    <div class="row">	
                            @include('projects.partials.compatibility')
			    </div>
		            <div class="row">
                            @include('projects.partials.dependencies')
			    </div>
			    <div class="row">
                            @include('projects.partials.collaborators')
			    </div>
                            <div class="input-group @if($errors->has('allow_team_fixes')) has-error @endif">
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


@section('script')
<script>
    window.keymap = "{{ Auth::user()->editor }}";
</script>
@endsection
