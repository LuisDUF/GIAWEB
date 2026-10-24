<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="user-routine-subscriptions-table">
            <thead>
            <tr>
                <th>Routine Id</th>
                <th>Saved At</th>
                <th>Is Active</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($userRoutineSubscriptions as $userRoutineSubscription)
                <tr>
                    <td>{{ $userRoutineSubscription->routine_id }}</td>
                    <td>{{ $userRoutineSubscription->saved_at }}</td>
                    <td>{{ $userRoutineSubscription->is_active }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['userRoutineSubscriptions.destroy', $userRoutineSubscription->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('userRoutineSubscriptions.show', [$userRoutineSubscription->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('userRoutineSubscriptions.edit', [$userRoutineSubscription->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $userRoutineSubscriptions])
        </div>
    </div>
</div>
