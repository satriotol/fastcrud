<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

class AppSpecsController extends Controller
{
    public function index()
    {
        $lastCommit = $this->getLastGitCommit();
        $lastCommitDate = $this->getLastGitCommitDate();
        $phpVersion = phpversion();
        $phpExtensions = get_loaded_extensions();
        $phpIniSettings = [
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
        ];
        $databaseInfo = [
            'database' => config('database.connections.' . config('database.default') . '.database'),
            'host' => config('database.connections.' . config('database.default') . '.host'),
        ];
        $composerPackages = $this->getComposerPackages();
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
        $osInfo = php_uname();

        return view('backend.app-specs', [
            'phpVersion' => $phpVersion,
            'phpExtensions' => $phpExtensions,
            'phpIniSettings' => $phpIniSettings,
            'laravelVersion' => App::version(),
            'sessionDriver' => config('session.driver'),
            'sessionLifetime' => config('session.lifetime'),
            'cacheDriver' => config('cache.default'),
            'queueDriver' => config('queue.default'),
            'databaseDriver' => config('database.default'),
            'databaseInfo' => $databaseInfo,
            'appEnv' => config('app.env'),
            'appDebug' => config('app.debug'),
            'lastCommit' => $lastCommit,
            'lastCommitDate' => $lastCommitDate,
            'composerPackages' => $composerPackages,
            'serverSoftware' => $serverSoftware,
            'osInfo' => $osInfo,
        ]);
    }

    private function getLastGitCommit()
    {
        return trim(shell_exec('git log -1 --format=%H'));
    }

    private function getLastGitCommitDate()
    {
        return trim(shell_exec('git log -1 --format=%cd'));
    }

    private function getComposerPackages()
    {
        $composerJson = file_get_contents(base_path('composer.lock'));
        $composerData = json_decode($composerJson, true);
        $packages = $composerData['packages'] ?? [];
        $packageList = [];

        foreach ($packages as $package) {
            $packageList[] = $package['name'] . ' ' . $package['version'];
        }

        return $packageList;
    }
}
