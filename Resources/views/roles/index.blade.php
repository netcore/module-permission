@extends('admin::layouts.master')

@section('content')
        <div class="col-md-8">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Roles</h4>
                </div>
                <div class="panel-body">
                    <div class="col-md-12 table-wrapper">
                        {{ Form::open(['route' => 'admin::permission.roles.update', 'method' => 'put', 'class' => 'js-form-submit', 'data-redirect' => 'false']) }}

                        <div class="alert alert-success hidden"></div>
                        <div class="alert alert-danger hidden"></div>

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
                                <tr class="object{{ $role->id }}">
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
                                        <td class="text-center">
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
                                        </td>
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
                                        <a href="{{ route('admin::permission.roles.destroy', $role->id) }}" data-id="{{ $role->id }}" class="btn btn-danger confirm-delete">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ Form::submit('Save', ['class' => 'btn btn-success pull-right']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Add new role</h4>
                </div>
                <div class="panel-body">
                    {{ Form::open(['route' => 'admin::permission.roles.store', 'method' => 'post', 'class' => 'js-form-submit']) }}
                    <div class="alert alert-success hidden"></div>
                    <div class="alert alert-danger hidden"></div>
                    <div class="form-group">
                        {{ Form::label('name', 'Role name', ['class' => 'control-label']) }}
                        {{ Form::text('name', null, ['class' => 'form-control']) }}
                    </div>


                        <hr>
                        @foreach($levels as $level)
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-2">
                                    {{ Form::label('level-'. $level->id, $level->name, ['class' => 'control-label']) }}
                                </div>
                                <div class="col-md-6">
                                    {{ Form::checkbox('levels[]', $level->id, null, ['class' => 'single-entries-changeable-state', 'id' => 'level-'. $level->id]) }}
                                </div>
                            </div>
                        @endforeach
                    <hr>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-2">
                            {{ Form::label('is_active', 'Is active?', ['class' => 'control-label']) }}
                        </div>
                        <div class="col-md-6">
                            {{ Form::checkbox('is_active', 1, null, ['class' => 'single-entries-changeable-state', 'checked']) }}
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 10px;">
                        {{ Form::submit('Add role', ['class' => 'btn btn-success', 'disabled']) }}
                    </div>
                    {{ Form::close() }}
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
            // custom x-editable buttons
            $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary btn-xs editable-submit">Save</button>' +
                '<button type="button" class="btn btn-xs editable-cancel">Cancel</button>';


            // adding new role
            var roleForm = $('.js-form-submit');
            roleForm.find('input[type="submit"]').prop('disabled', false);

            roleForm.on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var dangerAlert = form.find('.alert-danger');
                var successAlert = form.find('.alert-success');

                dangerAlert.addClass('hidden').empty();

                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serializeArray(),
                    success: function(response) {
                        successAlert.hide().removeClass('hidden').text(response.message).fadeIn();

                        if(response.redirect != undefined) {
                            setTimeout(function () {
                                window.location = response.redirect;
                            }, 1000);
                        }

                        roleForm.find('input[type="submit"]').prop('disabled', false);
                    },
                    error: function (response) {
                        var errors = response.responseJSON.errors;
                        var errorList = '';
                        $.each(errors, function (field, error) {
                            console.log(error);
                            errorList += '<li>' + error[0] + '</li>'
                        });
                        dangerAlert.hide().removeClass('hidden').html(errorList).fadeIn();
                        roleForm.find('input[type="submit"]').prop('disabled', false);
                    },
                    beforeSend: function () {
                        roleForm.find('input[type="submit"]').prop('disabled', true);
                    }
                });
            });
        });
    </script>
@endsection