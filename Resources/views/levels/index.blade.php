@extends('admin::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Levels</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Route List</th>
                            <th>Urls</th>
                            <th width="15%">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($levels as $level)
                            <tr data-id="{{ $level->id }}" class="object{{ $level->id }}">
                                <td class="js-l-name">
                                    {{ $level->name }}
                                </td>
                                <td class="js-l-routes">
                                    <ul>
                                        @foreach($level->routes as $route)
                                            @if($route->route == null)
                                                @continue
                                            @endif
                                            <li>
                                                {{ (substr($route->route, 0, 1) == '.' ? 'any' . $route->route : $route->route) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="js-l-urls">
                                    <ul>
                                        @foreach($level->routes as $route)
                                            @if($route->uri == null)
                                                @continue
                                            @endif
                                            <li>
                                                {{ $route->uri }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <a
                                            href="javascript:;"
                                            class="btn btn-primary js-edit-level"
                                    >
                                        Edit
                                    </a>

                                    <a
                                            href="{{ route('admin::permission.levels.destroy', $level->id) }}"
                                            data-id="{{ $level->id }}"
                                            class="btn btn-danger confirm-delete"
                                    >
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Manage levels</h4>
                </div>
                <div class="panel-body">
                    {{ Form::open(['route' => 'admin::permission.levels.modify', 'method' => 'post', 'class' => 'js-level-from hidden']) }}
                    <div class="alert alert-danger hidden"></div>
                    <div class="alert alert-success hidden"></div>
                    {{ Form::hidden('level_id', null) }}
                    <div class="form-group">
                        {{ Form::label('name', 'Level name', ['class' => 'control-label']) }}
                        {{ Form::text('name', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Routes', null, ['class' => 'control-label']) }}
                        <select class="js-route-input" name="routes[]" data-placeholder="Add route" multiple>
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        {{ Form::label('Urls', null, ['class' => 'control-label']) }}
                        <select class="js-url-input" name="urls[]" data-placeholder="Add url" multiple>
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success pull-left" type="submit">Save</button>
                        <button class="btn pull-right js-cancel-button">Cancel</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>

    <input type="hidden" value="{{ $levels->toJson() }}" name="all-levels">
    <input type="hidden" value="{{ $routes->toJson() }}" name="all-routes">

@endsection

@section('styles')
@endsection

@section('scripts')
    <script>
        $(function () {
            var levelSelect = $('.js-select-level');
            var routeInput = $('.js-route-input');
            var urlInput = $('.js-url-input');
            var levelInput = $('[name="level_id"]');
            var routes = JSON.parse($('input[name="all-routes"]').val());

            levelSelect.select2();

            var form = $('.js-level-from');
            var dangerAlert = form.find('.alert-danger');
            var successAlert = form.find('.alert-success');
            form.hide().removeClass('hidden').slideDown();

            var routeList = [];
//            console.log(routes);
            $.each(routes, function (group, values) {
                var r = [];
                $.each(values, function (index, value) {
                    r.push({
                        id: value,
                        text: value
                    });
                });
                routeList.push({
                    text: group,
                    children: r
                });
            });

            $('.js-cancel-button').on('click', function (e) {
                form.hide();
                $('.col-md-4 .panel-title').text('Create new level');
                e.preventDefault();
                form[0].reset();

                routeInput.select2({
                    data: routeList,
                    tags: true
                });

                urlInput.select2({
                    data: [],
                    tags: true
                });
                form.slideDown();
            });

            routeInput.select2({
                data: routeList,
                tags: true
            });

            urlInput.select2({
                data: [],
                tags: true
            });

            $(document).on('click', '.js-edit-level', function () {
                $('.col-md-4 .panel-title').text('Manage level');
                levelSelect.next('.select2').hide();
                form.hide().removeClass('hidden').slideDown();
                dangerAlert.addClass('hidden');
                successAlert.addClass('hidden');

                var levels = JSON.parse($('input[name="all-levels"]').val());
                var levelId = $(this).parent().parent().data('id');
                var preselectedRoutes = [];
                var preselectedUrls = [];

                $.each(levels, function (index, object) {
                    if (object.id == levelId) {
                        form.find('[name="name"]').val(object.name);
                        $.each(object.routes, function (id, route) {
                            if (route.route != null) {
                                preselectedRoutes.push(route.route);
                            }
                            if (route.uri != null) {
                                preselectedUrls.push(route.uri);
                            }
                        });
                        levelInput.val(levelId);

                    }
                });

                routeInput.val(preselectedRoutes);
                routeInput.trigger('change');

                urlInput.select2({
                    data: preselectedUrls,
                    tags: true
                });

                urlInput.val(preselectedUrls);
                urlInput.trigger('change');
            });

            form.find('[type="submit"]').prop('disabled', false);

            form.on('submit', function (e) {
                e.preventDefault();
                var form = $(this);

                dangerAlert.addClass('hidden').empty();

                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serializeArray(),
                    success: function (response) {
                        successAlert.hide().removeClass('hidden').text(response.message).fadeIn();

                        if (response.redirect != undefined) {
                            setTimeout(function () {
                                window.location = response.redirect;
                            }, 1000);
                        }

                        form.find('input[type="submit"]').prop('disabled', false);

                        var data = response.data;
                        if(response.newLevel) {
                            $('tbody').append('' +
                                '<tr data-id="' + data.id + '">' +
                                '<td class="js-l-name"></td>' +
                                '<td class="js-l-routes"></td>' +
                                '<td class="js-l-urls"></td>' +
                                '<td>' +
                                '<a href="javascript:;" class="btn btn-primary js-edit-level">' +
                                'Edit ' +
                                '</a> ' +
                                '<a href="http://netcore.dev/admin/permission/levels/' + data.id + '" data-id="' + data.id + '" class="btn btn-danger confirm-delete">' +
                                'Delete' +
                                '</a>' +
                                '</td>' +
                                '</tr>');
                        }
                        var row = $('tr[data-id="' + data.id + '"]').hide();

                        var responseRoutes = '<ul>';
                        $.each(response.routes, function (index, route) {
                            responseRoutes += '<li>' + route + '</li>';
                        });
                        responseRoutes += '</ul>';

                        var responseUrls = '<ul>';
                        $.each(response.urls, function (index, route) {
                            responseUrls += '<li>' + route + '</li>';
                        });
                        responseUrls += '</ul>';

                        row.find('.js-l-name').text(data.name);
                        row.find('.js-l-routes').html(responseRoutes);
                        row.find('.js-l-urls').html(responseUrls);
                        levelInput.val(data.id);

                        row.fadeIn();
                        $('input[name="all-levels"]').val(response.allLevels);
                    },
                    error: function (response) {
                        var errors = response.responseJSON.errors;
                        var errorList = '';
                        $.each(errors, function (field, error) {
                            console.log(error);
                            errorList += '<li>' + error[0] + '</li>'
                        });
                        dangerAlert.hide().removeClass('hidden').html(errorList).fadeIn();
                        form.find('input[type="submit"]').prop('disabled', false);
                    },
                    beforeSend: function () {
                        form.find('input[type="submit"]').prop('disabled', true);
                    }
                });
            });
        });
    </script>
@endsection