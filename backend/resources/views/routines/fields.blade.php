<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::textarea('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Is Public Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('is_public', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('is_public', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('is_public', 'Is Public', ['class' => 'form-check-label']) !!}
    </div>
</div>

<!-- Is System Routine Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('is_system_routine', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('is_system_routine', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('is_system_routine', 'Is System Routine', ['class' => 'form-check-label']) !!}
    </div>
</div>

<!-- Esborrada Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('esborrada', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('esborrada', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('esborrada', 'Esborrada', ['class' => 'form-check-label']) !!}
    </div>
</div>