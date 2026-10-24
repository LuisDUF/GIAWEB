<!-- Session Exercise Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('session_exercise_id', 'Session Exercise Id:') !!}
    {!! Form::number('session_exercise_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Set Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('set_number', 'Set Number:') !!}
    {!! Form::number('set_number', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Weight Kg Field -->
<div class="form-group col-sm-6">
    {!! Form::label('weight_kg', 'Weight Kg:') !!}
    {!! Form::number('weight_kg', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Reps Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reps', 'Reps:') !!}
    {!! Form::number('reps', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Rpe Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rpe', 'Rpe:') !!}
    {!! Form::number('rpe', null, ['class' => 'form-control']) !!}
</div>

<!-- Set Type Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('set_type', 'Set Type:') !!}
    {!! Form::textarea('set_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Sub Order Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sub_order', 'Sub Order:') !!}
    {!! Form::number('sub_order', null, ['class' => 'form-control']) !!}
</div>