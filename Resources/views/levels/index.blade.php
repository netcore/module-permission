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
    <script src="{{ asset('/assets/permission/js/levels.js') }}?v={{ filemtime(public_path('/assets/permission/js/levels.js')) }}"></script>
@endsection