<!-- Session Id Field -->
<div class="col-sm-12">
    {!! Form::label('session_id', 'Session Id:') !!}
    <p>{{ $sessionExercise->session_id }}</p>
</div>

<!-- Exercise Id Field -->
<div class="col-sm-12">
    {!! Form::label('exercise_id', 'Exercise Id:') !!}
    <p>{{ $sessionExercise->exercise_id }}</p>
</div>

<!-- Total Sets Field -->
<div class="col-sm-12">
    {!! Form::label('total_sets', 'Total Sets:') !!}
    <p>{{ $sessionExercise->total_sets }}</p>
</div>

<!-- Max Weight Lifted Field -->
<div class="col-sm-12">
    {!! Form::label('max_weight_lifted', 'Max Weight Lifted:') !!}
    <p>{{ $sessionExercise->max_weight_lifted }}</p>
</div>

<!-- Min Weight Lifted Field -->
<div class="col-sm-12">
    {!! Form::label('min_weight_lifted', 'Min Weight Lifted:') !!}
    <p>{{ $sessionExercise->min_weight_lifted }}</p>
</div>

<!-- Is Skipped Field -->
<div class="col-sm-12">
    {!! Form::label('is_skipped', 'Is Skipped:') !!}
    <p>{{ $sessionExercise->is_skipped }}</p>
</div>

<!-- Is Adhoc Field -->
<div class="col-sm-12">
    {!! Form::label('is_adhoc', 'Is Adhoc:') !!}
    <p>{{ $sessionExercise->is_adhoc }}</p>
</div>

<!-- Superset Id Field -->
<div class="col-sm-12">
    {!! Form::label('superset_id', 'Superset Id:') !!}
    <p>{{ $sessionExercise->superset_id }}</p>
</div>

<!-- Notas Field -->
<div class="col-sm-12">
    {!! Form::label('notas', 'Notas:') !!}
    <p>{{ $sessionExercise->notas }}</p>
</div>

