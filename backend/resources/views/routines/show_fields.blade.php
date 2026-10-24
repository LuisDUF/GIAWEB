<!-- User Id Field -->
<div class="col-sm-12">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $routine->user_id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $routine->name }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $routine->description }}</p>
</div>

<!-- Is Public Field -->
<div class="col-sm-12">
    {!! Form::label('is_public', 'Is Public:') !!}
    <p>{{ $routine->is_public }}</p>
</div>

<!-- Is System Routine Field -->
<div class="col-sm-12">
    {!! Form::label('is_system_routine', 'Is System Routine:') !!}
    <p>{{ $routine->is_system_routine }}</p>
</div>

<!-- Esborrada Field -->
<div class="col-sm-12">
    {!! Form::label('esborrada', 'Esborrada:') !!}
    <p>{{ $routine->esborrada }}</p>
</div>

