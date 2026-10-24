<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Weight Kg Field -->
<div class="form-group col-sm-6">
    {!! Form::label('weight_kg', 'Weight Kg:') !!}
    {!! Form::number('weight_kg', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Log Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('log_date', 'Log Date:') !!}
    {!! Form::text('log_date', null, ['class' => 'form-control','id'=>'log_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#log_date').datepicker()
    </script>
@endpush

<!-- Masa Muscular Field -->
<div class="form-group col-sm-6">
    {!! Form::label('masa_muscular', 'Masa Muscular:') !!}
    {!! Form::number('masa_muscular', null, ['class' => 'form-control']) !!}
</div>

<!-- Body Fat Percentage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('body_fat_percentage', 'Body Fat Percentage:') !!}
    {!! Form::number('body_fat_percentage', null, ['class' => 'form-control']) !!}
</div>

<!-- Abdominal Circumference Cm Field -->
<div class="form-group col-sm-6">
    {!! Form::label('abdominal_circumference_cm', 'Abdominal Circumference Cm:') !!}
    {!! Form::number('abdominal_circumference_cm', null, ['class' => 'form-control']) !!}
</div>

<!-- Hip Circumference Cm Field -->
<div class="form-group col-sm-6">
    {!! Form::label('hip_circumference_cm', 'Hip Circumference Cm:') !!}
    {!! Form::number('hip_circumference_cm', null, ['class' => 'form-control']) !!}
</div>