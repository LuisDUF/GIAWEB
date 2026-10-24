<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="exercises-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Muscle Group Id</th>
                <th>Description</th>
                <th>Image Url</th>
                <th>Animacion</th>
                <th>Equipment Type</th>
                <th>Maquina</th>
                <th>Con Asiento</th>
                <th>Con Respaldo</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($exercises as $exercise)
                <tr>
                    <td>{{ $exercise->name }}</td>
                    <td>{{ $exercise->muscle_group_id }}</td>
                    <td>{{ $exercise->description }}</td>
                    <td>{{ $exercise->image_url }}</td>
                    <td>{{ $exercise->animacion }}</td>
                    <td>{{ $exercise->equipment_type }}</td>
                    <td>{{ $exercise->maquina }}</td>
                    <td>{{ $exercise->con_asiento }}</td>
                    <td>{{ $exercise->con_respaldo }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['exercises.destroy', $exercise->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('exercises.show', [$exercise->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('exercises.edit', [$exercise->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $exercises])
        </div>
    </div>
</div>
