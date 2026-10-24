<!-- Username Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('username', 'Username:') !!}
    {!! Form::textarea('username', null, ['class' => 'form-control']) !!}
</div>

<!-- Gender Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('gender', 'Gender:') !!}
    {!! Form::textarea('gender', null, ['class' => 'form-control']) !!}
</div>

<!-- Height Cm Field -->
<div class="form-group col-sm-6">
    {!! Form::label('height_cm', 'Height Cm:') !!}
    {!! Form::number('height_cm', null, ['class' => 'form-control']) !!}
</div>

<!-- Weight Kg Field -->
<div class="form-group col-sm-6">
    {!! Form::label('weight_kg', 'Weight Kg:') !!}
    {!! Form::number('weight_kg', null, ['class' => 'form-control']) !!}
</div>

<!-- Birth Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('birth_date', 'Birth Date:') !!}
    {!! Form::text('birth_date', null, ['class' => 'form-control','id'=>'birth_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#birth_date').datepicker()
    </script>
@endpush

<!-- Consecutive Log Days Field -->
<div class="form-group col-sm-6">
    {!! Form::label('consecutive_log_days', 'Consecutive Log Days:') !!}
    {!! Form::number('consecutive_log_days', null, ['class' => 'form-control']) !!}
</div>

<!-- Premium Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('premium', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('premium', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('premium', 'Premium', ['class' => 'form-check-label']) !!}
    </div>
</div>