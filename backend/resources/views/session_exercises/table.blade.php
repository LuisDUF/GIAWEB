<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="session-exercises-table">
            <thead>
            <tr>
                <th>Session Id</th>
                <th>Exercise Id</th>
                <th>Total Sets</th>
                <th>Max Weight Lifted</th>
                <th>Min Weight Lifted</th>
                <th>Is Skipped</th>
                <th>Is Adhoc</th>
                <th>Superset Id</th>
                <th>Notas</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sessionExercises as $sessionExercise)
                <tr>
                    <td>{{ $sessionExercise->session_id }}</td>
                    <td>{{ $sessionExercise->exercise_id }}</td>
                    <td>{{ $sessionExercise->total_sets }}</td>
                    <td>{{ $sessionExercise->max_weight_lifted }}</td>
                    <td>{{ $sessionExercise->min_weight_lifted }}</td>
                    <td>{{ $sessionExercise->is_skipped }}</td>
                    <td>{{ $sessionExercise->is_adhoc }}</td>
                    <td>{{ $sessionExercise->superset_id }}</td>
                    <td>{{ $sessionExercise->notas }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['sessionExercises.destroy', $sessionExercise->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('sessionExercises.show', [$sessionExercise->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('sessionExercises.edit', [$sessionExercise->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $sessionExercises])
        </div>
    </div>
</div>
