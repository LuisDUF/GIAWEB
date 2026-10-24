<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="routines-table">
            <thead>
            <tr>
                <th>User Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Is Public</th>
                <th>Is System Routine</th>
                <th>Esborrada</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($routines as $routine)
                <tr>
                    <td>{{ $routine->user_id }}</td>
                    <td>{{ $routine->name }}</td>
                    <td>{{ $routine->description }}</td>
                    <td>{{ $routine->is_public }}</td>
                    <td>{{ $routine->is_system_routine }}</td>
                    <td>{{ $routine->esborrada }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['routines.destroy', $routine->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('routines.show', [$routine->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('routines.edit', [$routine->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $routines])
        </div>
    </div>
</div>
