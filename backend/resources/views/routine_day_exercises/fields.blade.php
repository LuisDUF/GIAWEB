<!-- Routine Day Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('routine_day_id', 'Routine Day Id:') !!}
    {!! Form::number('routine_day_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Exercise Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('exercise_id', 'Exercise Id:') !!}
    {!! Form::number('exercise_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Order Index Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order_index', 'Order Index:') !!}
    {!! Form::number('order_index', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Superset Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('superset_id', 'Superset Id:') !!}
    {!! Form::text('superset_id', null, ['class' => 'form-control']) !!}
</div>