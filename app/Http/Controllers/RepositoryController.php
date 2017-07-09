<?php

namespace App\Http\Controllers;

use App\Console\Commands\TestExtractParameters;
use App\Http\Requests\Repository\CreateRepositoryFormRequest;
use App\Http\Requests\Repository\UploadFileFormRequest;
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
    public function generateParamsFile($repository_id){
        $repo = \App\Repository::find($repository_id);
        Repository::downloadProject($repo);
        chdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder);
        exec('composer dump-autoload');
        exec('php artisan test:extract');
        $name = str_random(20) . '-parameters.txt';
        copy(
            app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller/parameters.txt',
            storage_path() . '/app/files/' . $name
        );
        $repo->params_file_path = 'files/' . $name;
        $repo->save();
        Session::flash('download.params.file', $repo->id);
        exec("cd ..");
        exec("cd ..");
        exec("RD /S /Q /" . $repo->id);
        return redirect('/home')
            ->with(['message' => Lang::get('repositories')['repository_generate_params_file']]);
    }
    public function downloadParamsFile($repository_id) {
        $repository = Repository::find($repository_id);
        return response()
            ->download(storage_path() . '/app/' . $repository->params_file_path);
    }
    public function uploadParamsFile(UploadFileFormRequest $request, $repository_id){
        $path = $request->params_file->store('files');
        $repo = Repository::find($repository_id);
        $repo->params_file_path = $path;
        $repo->save();
        return redirect('home')->with(['message' => Lang::get('repositories')['repository_upload_params_file']]);
    }
    public function generateTests($repository_id){
        $repo = \App\Repository::find($repository_id);
        Repository::downloadProject($repo);
        copy(
            storage_path() . '/app/' . $repo->params_file_path,
            app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller/parameters.txt'
        );
        chdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder);
        exec('composer dump-autoload');
        exec('php artisan test:generate');

        $zipper = new \Chumper\Zipper\Zipper;
        $files = glob(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller/*');
        $zipper->make(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/tests/Controller.zip')->add($files)->close();
        Session::flash('download.tests', $repo->id);
        return redirect('/home')
            ->with(['message' => Lang::get('repositories')['repository_generate_test']]);
    }
    public function downloadTests($repository_id) {
        $repository = Repository::find($repository_id);
        return response()
            ->download(app_path() . '/../storage/repositories/' . $repository->id . '/' . $repository->folder . '/tests/Controller.zip');
    }

}
