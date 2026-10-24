<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="profiles-table">
            <thead>
            <tr>
                <th>Username</th>
                <th>Gender</th>
                <th>Height Cm</th>
                <th>Weight Kg</th>
                <th>Birth Date</th>
                <th>Consecutive Log Days</th>
                <th>Premium</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($profiles as $profile)
                <tr>
                    <td>{{ $profile->username }}</td>
                    <td>{{ $profile->gender }}</td>
                    <td>{{ $profile->height_cm }}</td>
                    <td>{{ $profile->weight_kg }}</td>
                    <td>{{ $profile->birth_date }}</td>
                    <td>{{ $profile->consecutive_log_days }}</td>
                    <td>{{ $profile->premium }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['profiles.destroy', $profile->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('profiles.show', [$profile->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('profiles.edit', [$profile->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $profiles])
        </div>
    </div>
</div>
