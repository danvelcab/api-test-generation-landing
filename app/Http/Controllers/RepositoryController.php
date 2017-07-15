<?php

namespace App\Http\Controllers;

use App\Console\Commands\TestExtractParameters;
use App\Http\Requests\Repository\CreateRepositoryFormRequest;
use App\Http\Requests\Repository\UploadFileFormRequest;
use App\Http\Requests\Repository\UploadSpecificationsFileFormRequest;
use App\Repository;
use App\Tools;
use Chumper\Zipper\Zipper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

class RepositoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(){
        return view('repositories.create');
    }
    public function save(CreateRepositoryFormRequest $createRepositoryFormRequest){
        $user = Auth::user();
        $repository = new Repository();
        $repository->url = $createRepositoryFormRequest->get('url');
        $repository->folder = $createRepositoryFormRequest->get('folder');
        $repository->user_id = $user->id;
        $repository->private = 0;
        $repository->save();
        return redirect('/home')->with(['message' => Lang::get('repositories')['repository_saved']]);
    }
    public function edit($repository_id){
        $repository = Repository::find($repository_id);
        return view('repositories.edit',
            [
                'repository' => $repository
            ]);
    }
    public function update(CreateRepositoryFormRequest $createRepositoryFormRequest, $repository_id){
        $repository = Repository::find($repository_id);
        $repository->url = $createRepositoryFormRequest->get('url');
        $repository->folder = $createRepositoryFormRequest->get('folder');
        $repository->save();
        return redirect('/home')->with(['message' => Lang::get('repositories')['repository_updated']]);
    }
    public function delete($repository_id){
        $repository = Repository::find($repository_id);
        $repository->delete();
        return redirect('/home')
            ->with(['message' => Lang::get('repositories')['repository_deleted']]);
    }
    public function generateSpecificationsFile($repository_id){
        $repo = \App\Repository::find($repository_id);
        Repository::removeRepository($repo);
        Repository::downloadProject($repo);
        $exist = is_dir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller');
        if(!$exist){
            mkdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller');
        }
        chdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder);
        exec('composer dump-autoload');
        if($repo->specifications_file_path == null){
            $name = str_random(20) . '-specifications.txt';
            $repo->specifications_file_path = $name;
            $repo->save();
        }else{
            $name = $repo->specifications_file_path;
            copy(
                storage_path() . '/app/files/' . $repo->id . "/" . $name,
                app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller/specifications.txt'
            );
        }
        exec('php artisan test:specifications');
        $exist = is_dir(app_path() . '/../storage/app/files/' . $repo->id);
        if(!$exist){
            mkdir(app_path() . '/../storage/app/files/' . $repo->id);
        }
        copy(
            app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller/specifications.txt',
            storage_path() . '/../storage/app/files/' . $repo->id . "/" . $name
        );
        Session::flash('download.specifications.file', $repo->id);
        Repository::removeRepository($repo);
        return redirect('/home')
            ->with(['message' => Lang::get('repositories')['repository_generate_specifications_file']]);
    }
    public function downloadSpecificationsFile($repository_id) {
        $repository = Repository::find($repository_id);
        return response()
            ->download(storage_path() . '/app/files/' .$repository->id . '/' . $repository->specifications_file_path);
    }
    public function uploadSpecificationsFile(UploadSpecificationsFileFormRequest $request, $repository_id){
        $repo = Repository::find($repository_id);
        $path = $request->specifications_file->store('files/' . $repo->id );
        $exploded_path = explode('/', $path);
        $name = $exploded_path[count($exploded_path) - 1];
        $repo->specifications_file_path = $name;
        $repo->save();
        return redirect('home')->with(['message' => Lang::get('repositories')['repository_upload_specifications_file']]);
    }
    public function generateParamsFile($repository_id){
        $repo = \App\Repository::find($repository_id);
        Repository::removeRepository($repo);
        Repository::downloadProject($repo);
        chdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder);
        exec('composer dump-autoload');
        $exist = is_dir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller');
        if(!$exist){
            mkdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller');
        }
        copy(
            storage_path() . '/app/files/' . $repo->id . '/' . $repo->specifications_file_path,
            app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller/specifications.txt'

        );
        if($repo->params_file_path == null){
            $name = str_random(20) . '-parameters.txt';
            $repo->params_file_path = $name;
            $repo->save();
        }else{
            $name = $repo->params_file_path;
            copy(
                storage_path() . '/app/files/' . $repo->id . "/" . $name,
                app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller/parameters.txt'
            );
        }
        exec('php artisan test:extract');
        copy(
            app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller/parameters.txt',
            storage_path() . '/app/files/' . $repo->id . '/' . $name
        );
        Session::flash('download.params.file', $repo->id);
        Repository::removeRepository($repo);
        return redirect('/home')
            ->with(['message' => Lang::get('repositories')['repository_generate_params_file']]);
    }
    public function downloadParamsFile($repository_id) {
        $repository = Repository::find($repository_id);
        return response()
            ->download(storage_path() . '/app/files/' . $repository->id . '/' . $repository->params_file_path);
    }
    public function uploadParamsFile(UploadFileFormRequest $request, $repository_id){
        $repo = Repository::find($repository_id);
        $path = $request->params_file->store('files/' . $repo->id );
        $exploded_path = explode('/', $path);
        $name = $exploded_path[count($exploded_path) - 1];
        $repo->params_file_path = $name;
        $repo->save();
        return redirect('home')->with(['message' => Lang::get('repositories')['repository_upload_params_file']]);
    }
    public function generateTests($repository_id){
        $repo = \App\Repository::find($repository_id);
        Repository::removeRepository($repo);
        Repository::downloadProject($repo);
        mkdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller');
        copy(
            storage_path() . '/app/files/' . $repo->id . '/' . $repo->params_file_path,
            app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller/parameters.txt'
        );
        chdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder);
        exec('composer dump-autoload');
        exec('php artisan test:generate');

        $zipper = new \Chumper\Zipper\Zipper;
        $files = glob(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller');
        $exist = is_dir(app_path() . '/../storage/app/files/' . $repo->id);
        if(!$exist){
            mkdir(app_path() . '/../storage/app/files/' . $repo->id);
        }else{
            $exist = file_exists(app_path() . '/../storage/app/files/' . $repo->id . '/Controller.zip');
            if($exist){
                unlink(app_path() . '/../storage/app/files/' . $repo->id . '/Controller.zip');
            }
        }
        $zipper->make(app_path() . '/../storage/app/files/' . $repo->id . '/Controller.zip')->add($files)->close();
        Session::flash('download.tests', $repo->id);
        Repository::removeRepository($repo);
        return redirect('/home')
            ->with(['message' => Lang::get('repositories')['repository_generate_test']]);
    }
    public function downloadTests($repository_id) {
        $repository = Repository::find($repository_id);
        return response()
            ->download(app_path() . '/../storage/app/files/' . $repository->id . '/Controller.zip');
    }

}
