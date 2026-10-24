<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="routine-days-table">
            <thead>
            <tr>
                <th>Routine Id</th>
                <th>Name</th>
                <th>Day Order</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($routineDays as $routineDay)
                <tr>
                    <td>{{ $routineDay->routine_id }}</td>
                    <td>{{ $routineDay->name }}</td>
                    <td>{{ $routineDay->day_order }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['routineDays.destroy', $routineDay->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('routineDays.show', [$routineDay->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('routineDays.edit', [$routineDay->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $routineDays])
        </div>
    </div>
</div>
