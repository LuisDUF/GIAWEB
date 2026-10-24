<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $exercise->name }}</p>
</div>

<!-- Muscle Group Id Field -->
<div class="col-sm-12">
    {!! Form::label('muscle_group_id', 'Muscle Group Id:') !!}
    <p>{{ $exercise->muscle_group_id }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $exercise->description }}</p>
</div>

<!-- Image Url Field -->
<div class="col-sm-12">
    {!! Form::label('image_url', 'Image Url:') !!}
    <p>{{ $exercise->image_url }}</p>
</div>

<!-- Animacion Field -->
<div class="col-sm-12">
    {!! Form::label('animacion', 'Animacion:') !!}
    <p>{{ $exercise->animacion }}</p>
</div>

<!-- Equipment Type Field -->
<div class="col-sm-12">
    {!! Form::label('equipment_type', 'Equipment Type:') !!}
    <p>{{ $exercise->equipment_type }}</p>
</div>

<!-- Maquina Field -->
<div class="col-sm-12">
    {!! Form::label('maquina', 'Maquina:') !!}
    <p>{{ $exercise->maquina }}</p>
</div>

<!-- Con Asiento Field -->
<div class="col-sm-12">
    {!! Form::label('con_asiento', 'Con Asiento:') !!}
    <p>{{ $exercise->con_asiento }}</p>
</div>

<!-- Con Respaldo Field -->
<div class="col-sm-12">
    {!! Form::label('con_respaldo', 'Con Respaldo:') !!}
    <p>{{ $exercise->con_respaldo }}</p>
</div>

