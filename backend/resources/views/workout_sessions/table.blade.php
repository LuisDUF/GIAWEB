<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="workout-sessions-table">
            <thead>
            <tr>
                <th>User Id</th>
                <th>Routine Day Id</th>
                <th>Date</th>
                <th>Duration Minutes</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($workoutSessions as $workoutSession)
                <tr>
                    <td>{{ $workoutSession->user_id }}</td>
                    <td>{{ $workoutSession->routine_day_id }}</td>
                    <td>{{ $workoutSession->date }}</td>
                    <td>{{ $workoutSession->duration_minutes }}</td>
                    <td>{{ $workoutSession->notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['workoutSessions.destroy', $workoutSession->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('workoutSessions.show', [$workoutSession->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('workoutSessions.edit', [$workoutSession->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $workoutSessions])
        </div>
    </div>
</div>
