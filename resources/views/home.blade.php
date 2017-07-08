@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Your projects</div>

                <div class="panel-body">
                    @if(count($repositories) == 0)
                        <div class="text-center">
                            Configure your first project and start working with API Test Generator
                        </div>
                        <div class="text-center" style="margin-top: 15px">
                            <a href="{{URL::asset('repositories/create')}}">
                                <button class="btn btn-primary">Configure</button>
                            </a>
                        </div>
                    @endif
                    @foreach($repositories as $repository)
                        <div class="col-md-12">
                            <div class="col-md-8">
                                <div>
                                    <label>URL:</label> {{$repository->url}}
                                </div>
                                <div>
                                    <label>Folder:</label> {{$repository->folder}}
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        <button class="btn btn-primary">Actions</button>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="{{URL::asset('repositories/generateParamsFile') . '/' . $repository->id}}">Generate params file</a>
                                        </li>
                                        <li>
                                            @if($repository->params_file_path != null)
                                                <a onclick="openUploadModal({{$repository->id}})">Upload params file</a>
                                            @endif
                                        </li>
                                        <li>
                                            @if($repository->params_file_path != null)
                                                <a href="{{URL::asset('repositories/generateTests') . '/' . $repository->id}}">Generate tests</a>
                                            @else
                                                <a class="a-disabled">Generate tests</a>
                                            @endif
                                        </li>
                                        <li>
                                            <a href="{{URL::asset('repositories/edit') . '/' . $repository->id}}">Edit project</a>
                                        </li>
                                        <li>
                                            <a href="{{URL::asset('repositories/delete') . '/' . $repository->id}}">Delete project</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr noshade="noshade" />
                        </div>
                    @endforeach
                    @if(count($repositories) == 1)
                        <div class="alert-warning col-md-12">
                            API Test Generator is still in beta. At the moment, you can only create one project per account
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="upload-file-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Upload Modal File</h4>
            </div>
            <form id="upload-form" class="form-horizontal" method="POST" enctype="multipart/form-data"
                  {{--action="{{ URL::asset('repositories/upload-params-file') . '/' . $repository->id }}"--}}
            >
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="form-group">
                    <label for="params_file" class="col-md-4 control-label">File</label>

                    <div class="col-md-6">
                        <input id="params_file" type="file" class="form-control" name="params_file" required>
                        @if ($errors->has('params_file'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('params_file') }}</strong>
                                    </span>
                        @endif
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    function openUploadModal(id){
        var form = $('#upload-form');
        var newAction = "http://localhost/api-test-generation-landing/public/repositories/upload-params-file/" + id;
        form.attr('action', newAction);
        $('#upload-file-modal').modal('show')
    }
</script>
@endsection
