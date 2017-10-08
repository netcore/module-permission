@extends('admin::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Roles</h4>
                </div>
                <div class="panel-body">
                    @include('admin::_partials._messages')
                    {{ Form::open(['route' => 'admin::permission.roles.update', 'method' => 'put']) }}
                        <table class="table table-bordered">
                            <tr>
                                <td></td>
                                <th colspan="{{ $levels->count() }}">Permissions</th>
                                <td></td>
                                <td></td>
                            </tr>
                                <tr>
                                    <th>Role</th>

                                    @foreach($levels as $level)
                                        <th class="text-center" width="8%">{{ $level->name }}</th>
                                    @endforeach

                                    <th width="10%">Is active?</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    {{ Form::hidden('role', $role->id) }}
                                    <tr>
                                        <td>
                                            <a href="javascript:;"
                                               class="js-role-name"
                                               data-pk="{{ $role->id }}"
                                               data-url="{{ route('admin::permission.roles.field.update') }}"
                                               data-title="Role name"
                                               data-name="name"
                                            >{{ $role->name }}</a>
                                            <div class="editable-buttons">
                                            </div>
                                        </td>
                                        @foreach($levels as $level)
                                            <th class="text-center">
                                                <input
                                                        class="single-entries-changeable-state"
                                                        type="checkbox"
                                                        data-render="switchery"
                                                        data-theme="default"
                                                        data-switchery="true"
                                                        name="levels[{{ $role->id }}][]"
                                                        value="{{ $level->id }}"
                                                        {{ in_array($level->id, $role->levels->pluck('id')->toArray()) ? 'checked' : '' }}
                                                />
                                            </th>
                                        @endforeach
                                        <td>
                                            <input
                                                    class="single-entries-changeable-state"
                                                    type="checkbox"
                                                    data-render="switchery"
                                                    data-theme="default"
                                                    value="1"
                                                    name="active[{{ $role->id }}]"
                                                    {{ $role->is_active ? 'checked' : '' }}
                                            />
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    {{ Form::submit('Save', ['class' => 'btn btn-primary pull-right']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="/assets/admin/plugins/x-editable/css/bootstrap-editable.css" rel="stylesheet">
@endsection

@section('scripts')
    <script src="/assets/admin/plugins/x-editable/js/bootstrap-editable.min.js"></script>
    <script>
        $(function () {
            //init switcher
            $('.single-entries-changeable-state').each(function(i, switcher) {
                new Switchery(switcher);
            });

            //init editable

            $('.js-role-name').editable({
                type: 'text'
            });
            $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary btn-xs editable-submit">Save</button>' +
                '<button type="button" class="btn btn-xs editable-cancel">Cancel</button>';
        });
    </script>
@endsection