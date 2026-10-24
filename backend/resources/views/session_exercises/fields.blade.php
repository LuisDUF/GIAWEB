<!-- Session Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('session_id', 'Session Id:') !!}
    {!! Form::number('session_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Exercise Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('exercise_id', 'Exercise Id:') !!}
    {!! Form::number('exercise_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Total Sets Field -->
<div class="form-group col-sm-6">
    {!! Form::label('total_sets', 'Total Sets:') !!}
    {!! Form::number('total_sets', null, ['class' => 'form-control']) !!}
</div>

<!-- Max Weight Lifted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('max_weight_lifted', 'Max Weight Lifted:') !!}
    {!! Form::number('max_weight_lifted', null, ['class' => 'form-control']) !!}
</div>

<!-- Min Weight Lifted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('min_weight_lifted', 'Min Weight Lifted:') !!}
    {!! Form::number('min_weight_lifted', null, ['class' => 'form-control']) !!}
</div>

<!-- Is Skipped Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('is_skipped', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('is_skipped', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('is_skipped', 'Is Skipped', ['class' => 'form-check-label']) !!}
    </div>
</div>

<!-- Is Adhoc Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('is_adhoc', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('is_adhoc', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('is_adhoc', 'Is Adhoc', ['class' => 'form-check-label']) !!}
    </div>
</div>

<!-- Superset Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('superset_id', 'Superset Id:') !!}
    {!! Form::text('superset_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Notas Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('notas', 'Notas:') !!}
    {!! Form::textarea('notas', null, ['class' => 'form-control']) !!}
</div>