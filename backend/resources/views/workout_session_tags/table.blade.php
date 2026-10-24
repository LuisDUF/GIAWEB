<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="workout-session-tags-table">
            <thead>
            <tr>
                <th>User Id</th>
                <th>Tag Id</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($workoutSessionTags as $workoutSessionTag)
                <tr>
                    <td>{{ $workoutSessionTag->user_id }}</td>
                    <td>{{ $workoutSessionTag->tag_id }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['workoutSessionTags.destroy', $workoutSessionTag->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('workoutSessionTags.show', [$workoutSessionTag->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('workoutSessionTags.edit', [$workoutSessionTag->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $workoutSessionTags])
        </div>
    </div>
</div>
