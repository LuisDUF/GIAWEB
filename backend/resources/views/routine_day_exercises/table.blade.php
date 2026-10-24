<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="routine-day-exercises-table">
            <thead>
            <tr>
                <th>Routine Day Id</th>
                <th>Exercise Id</th>
                <th>Order Index</th>
                <th>Superset Id</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($routineDayExercises as $routineDayExercise)
                <tr>
                    <td>{{ $routineDayExercise->routine_day_id }}</td>
                    <td>{{ $routineDayExercise->exercise_id }}</td>
                    <td>{{ $routineDayExercise->order_index }}</td>
                    <td>{{ $routineDayExercise->superset_id }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['routineDayExercises.destroy', $routineDayExercise->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('routineDayExercises.show', [$routineDayExercise->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('routineDayExercises.edit', [$routineDayExercise->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $routineDayExercises])
        </div>
    </div>
</div>
