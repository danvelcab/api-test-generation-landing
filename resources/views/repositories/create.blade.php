@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="">
                <div class="panel-heading text-center text-capitalize"><h2>Project configuration</h2></div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{URL::asset('repositories/save')}}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="url" class="col-md-4 control-label">GitHub url project</label>

                            <div class="col-md-6">
                                <input id="url" type="url" class="form-control" name="url" value="{{ old('url') }}" required autofocus>

                                @if ($errors->has('url'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('folder') ? ' has-error' : '' }}">
                            <label for="folder" class="col-md-4 control-label">
                                Folder name
                                <a href="#" data-toggle="tooltip" title="The name of the project root folder">
                                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true">
                                    </span>
                                </a>
                            </label>

                            <div class="col-md-6">
                                <input id="folder" type="text" class="form-control" name="folder" required>

                                @if ($errors->has('folder'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('folder') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Configure!
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection

