<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="profile-rest-days-table">
            <thead>
            <tr>
                <th>Week Day Id</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($profileRestDays as $profileRestDays)
                <tr>
                    <td>{{ $profileRestDays->week_day_id }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['profileRestDays.destroy', $profileRestDays->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('profileRestDays.show', [$profileRestDays->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('profileRestDays.edit', [$profileRestDays->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $profileRestDays])
        </div>
    </div>
</div>
