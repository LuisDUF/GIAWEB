<!-- Routine Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('routine_id', 'Routine Id:') !!}
    {!! Form::number('routine_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::textarea('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Day Order Field -->
<div class="form-group col-sm-6">
    {!! Form::label('day_order', 'Day Order:') !!}
    {!! Form::number('day_order', null, ['class' => 'form-control', 'required']) !!}
</div>