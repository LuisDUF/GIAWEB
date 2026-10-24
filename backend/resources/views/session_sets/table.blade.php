<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="session-sets-table">
            <thead>
            <tr>
                <th>Session Exercise Id</th>
                <th>Set Number</th>
                <th>Weight Kg</th>
                <th>Reps</th>
                <th>Rpe</th>
                <th>Set Type</th>
                <th>Sub Order</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sessionSets as $sessionSets)
                <tr>
                    <td>{{ $sessionSets->session_exercise_id }}</td>
                    <td>{{ $sessionSets->set_number }}</td>
                    <td>{{ $sessionSets->weight_kg }}</td>
                    <td>{{ $sessionSets->reps }}</td>
                    <td>{{ $sessionSets->rpe }}</td>
                    <td>{{ $sessionSets->set_type }}</td>
                    <td>{{ $sessionSets->sub_order }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['sessionSets.destroy', $sessionSets->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('sessionSets.show', [$sessionSets->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('sessionSets.edit', [$sessionSets->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $sessionSets])
        </div>
    </div>
</div>
