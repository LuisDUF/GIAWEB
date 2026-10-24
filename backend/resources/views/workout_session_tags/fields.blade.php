<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Tag Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tag_id', 'Tag Id:') !!}
    {!! Form::number('tag_id', null, ['class' => 'form-control', 'required']) !!}
</div>