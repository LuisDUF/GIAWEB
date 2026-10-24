<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="user-body-logs-table">
            <thead>
            <tr>
                <th>User Id</th>
                <th>Weight Kg</th>
                <th>Log Date</th>
                <th>Masa Muscular</th>
                <th>Body Fat Percentage</th>
                <th>Abdominal Circumference Cm</th>
                <th>Hip Circumference Cm</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($userBodyLogs as $userBodyLogs)
                <tr>
                    <td>{{ $userBodyLogs->user_id }}</td>
                    <td>{{ $userBodyLogs->weight_kg }}</td>
                    <td>{{ $userBodyLogs->log_date }}</td>
                    <td>{{ $userBodyLogs->masa_muscular }}</td>
                    <td>{{ $userBodyLogs->body_fat_percentage }}</td>
                    <td>{{ $userBodyLogs->abdominal_circumference_cm }}</td>
                    <td>{{ $userBodyLogs->hip_circumference_cm }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['userBodyLogs.destroy', $userBodyLogs->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('userBodyLogs.show', [$userBodyLogs->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('userBodyLogs.edit', [$userBodyLogs->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $userBodyLogs])
        </div>
    </div>
</div>
