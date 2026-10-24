<!-- Routine Id Field -->
<div class="col-sm-12">
    {!! Form::label('routine_id', 'Routine Id:') !!}
    <p>{{ $userRoutineSubscription->routine_id }}</p>
</div>

<!-- Saved At Field -->
<div class="col-sm-12">
    {!! Form::label('saved_at', 'Saved At:') !!}
    <p>{{ $userRoutineSubscription->saved_at }}</p>
</div>

<!-- Is Active Field -->
<div class="col-sm-12">
    {!! Form::label('is_active', 'Is Active:') !!}
    <p>{{ $userRoutineSubscription->is_active }}</p>
</div>

