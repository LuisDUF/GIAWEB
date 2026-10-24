<!-- Routine Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('routine_id', 'Routine Id:') !!}
    {!! Form::number('routine_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Saved At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('saved_at', 'Saved At:') !!}
    {!! Form::text('saved_at', null, ['class' => 'form-control','id'=>'saved_at']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#saved_at').datepicker()
    </script>
@endpush

<!-- Is Active Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('is_active', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('is_active', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('is_active', 'Is Active', ['class' => 'form-check-label']) !!}
    </div>
</div>