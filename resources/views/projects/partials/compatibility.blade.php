{{ Form::label('compatibility', 'Compatibility', ['class' => 'control-label mch-font-color']) }}

<div class="input-group compact @if($errors->has('compatibility')) has-error @endif">
@foreach(\App\Models\Badge::all()->reverse() as $badge)
    @php
        /** @var \App\Models\Project $project */
        /** @var \App\Models\Badge $badge */
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany $state */
        $state = $project->states()->where('badge_id', $badge->id);
    @endphp
	<div class="col-4">
	        {{ Form::checkbox('badge_ids[]', $badge->id, $state->count() > 0, ['id' => 'badge_checkbox_'.$badge->id, 'class' => 'form-check-input']) }}
       		<label class="control-label">{{ $badge->name }}</label>
	</div>
        {{ Form::select("badge_status[$badge->id]", \App\Models\BadgeProject::$states, $state->count() > 0 ? $state->first()->status : 'unknown', ['class' => 'form-select col-7']) }}
@endforeach
</div>
