<!-- Name Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::textarea('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Muscle Group Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('muscle_group_id', 'Muscle Group Id:') !!}
    {!! Form::number('muscle_group_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Url Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('image_url', 'Image Url:') !!}
    {!! Form::textarea('image_url', null, ['class' => 'form-control']) !!}
</div>

<!-- Animacion Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('animacion', 'Animacion:') !!}
    {!! Form::textarea('animacion', null, ['class' => 'form-control']) !!}
</div>

<!-- Equipment Type Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('equipment_type', 'Equipment Type:') !!}
    {!! Form::textarea('equipment_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Maquina Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('maquina', 'Maquina:') !!}
    {!! Form::textarea('maquina', null, ['class' => 'form-control']) !!}
</div>

<!-- Con Asiento Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('con_asiento', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('con_asiento', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('con_asiento', 'Con Asiento', ['class' => 'form-check-label']) !!}
    </div>
</div>

<!-- Con Respaldo Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('con_respaldo', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('con_respaldo', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('con_respaldo', 'Con Respaldo', ['class' => 'form-check-label']) !!}
    </div>
</div>