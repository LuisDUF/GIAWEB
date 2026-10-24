<!-- User Id Field -->
<div class="col-sm-12">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $workoutSession->user_id }}</p>
</div>

<!-- Routine Day Id Field -->
<div class="col-sm-12">
    {!! Form::label('routine_day_id', 'Routine Day Id:') !!}
    <p>{{ $workoutSession->routine_day_id }}</p>
</div>

<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('date', 'Date:') !!}
    <p>{{ $workoutSession->date }}</p>
</div>

<!-- Duration Minutes Field -->
<div class="col-sm-12">
    {!! Form::label('duration_minutes', 'Duration Minutes:') !!}
    <p>{{ $workoutSession->duration_minutes }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('notes', 'Notes:') !!}
    <p>{{ $workoutSession->notes }}</p>
</div>

