<!-- Username Field -->
<div class="col-sm-12">
    {!! Form::label('username', 'Username:') !!}
    <p>{{ $profile->username }}</p>
</div>

<!-- Gender Field -->
<div class="col-sm-12">
    {!! Form::label('gender', 'Gender:') !!}
    <p>{{ $profile->gender }}</p>
</div>

<!-- Height Cm Field -->
<div class="col-sm-12">
    {!! Form::label('height_cm', 'Height Cm:') !!}
    <p>{{ $profile->height_cm }}</p>
</div>

<!-- Weight Kg Field -->
<div class="col-sm-12">
    {!! Form::label('weight_kg', 'Weight Kg:') !!}
    <p>{{ $profile->weight_kg }}</p>
</div>

<!-- Birth Date Field -->
<div class="col-sm-12">
    {!! Form::label('birth_date', 'Birth Date:') !!}
    <p>{{ $profile->birth_date }}</p>
</div>

<!-- Consecutive Log Days Field -->
<div class="col-sm-12">
    {!! Form::label('consecutive_log_days', 'Consecutive Log Days:') !!}
    <p>{{ $profile->consecutive_log_days }}</p>
</div>

<!-- Premium Field -->
<div class="col-sm-12">
    {!! Form::label('premium', 'Premium:') !!}
    <p>{{ $profile->premium }}</p>
</div>

