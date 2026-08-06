<?php
namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppSpecsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:SUPERADMIN');
    }
    public function index()
    {
        $connection = config('database.default');
        $databaseInfo = [
            'connection' => $connection,
            'database' => config("database.connections.$connection.database"),
            'host' => config("database.connections.$connection.host"),
            'port' => config("database.connections.$connection.port"),
        ];
        $composerPackages = $this->getComposerPackages();
        [$databaseVersion, $databaseTables] = $this->getDatabaseStructure();
        [$roles, $permissionGroups] = $this->getAccessControl();

        return view('fastcrud::app-specs', [
            'appName' => config('app.name'),
            'appUrl' => config('app.url'),
            'appEnv' => config('app.env'),
            'appDebug' => config('app.debug'),
            'appTimezone' => config('app.timezone'),
            'appLocale' => app()->getLocale(),
            'fastcrudVersion' => $composerPackages['satriotol/fastcrud'] ?? 'N/A',
            'generatedAt' => now(),
            'generatedBy' => auth()->user()?->name ?? '-',

            'phpVersion' => phpversion(),
            'phpExtensions' => get_loaded_extensions(),
            'phpIniSettings' => [
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
            ],
            'serverSoftware' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'osInfo' => php_uname(),

            'laravelVersion' => App::version(),
            'sessionDriver' => config('session.driver'),
            'sessionLifetime' => config('session.lifetime'),
            'cacheDriver' => config('cache.default'),
            'queueDriver' => config('queue.default'),
            'databaseDriver' => $connection,
            'databaseInfo' => $databaseInfo,
            'databaseVersion' => $databaseVersion,
            'databaseTables' => $databaseTables,

            'modules' => $this->getModules(),
            'roles' => $roles,
            'permissionGroups' => $permissionGroups,
            'composerPackages' => $composerPackages,
        ]);
    }

    /** @return array<string,string> package name => version */
    private function getComposerPackages()
    {
        $lock = base_path('composer.lock');
        if (!is_file($lock)) {
            return [];
        }
        $composerData = json_decode(file_get_contents($lock), true);
        $packageList = [];

        foreach ($composerData['packages'] ?? [] as $package) {
            $packageList[$package['name']] = $package['version'];
        }
        ksort($packageList);

        return $packageList;
    }

    /** @return array{0:string,1:array} server version and table list with row counts */
    private function getDatabaseStructure()
    {
        try {
            $version = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
            $tables = collect(Schema::getTables())
                ->map(function ($table) {
                    try {
                        // schema_qualified_name keeps the count correct on pgsql non-public schemas
                        $table['rows'] = DB::table($table['schema_qualified_name'] ?? $table['name'])->count();
                    } catch (\Throwable) {
                        $table['rows'] = null;
                    }
                    return $table;
                })
                ->sortBy('name')
                ->values()
                ->all();

            return [$version, $tables];
        } catch (\Throwable) {
            return ['N/A', []];
        }
    }

    /**
     * Routes grouped by the first segment of their name, so the module list
     * grows by itself whenever the CRUD generator registers new routes.
     */
    private function getModules()
    {
        $modules = [];

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            if (!$name) {
                continue;
            }
            $methods = array_values(array_diff($route->methods(), ['HEAD']));
            $modules[strtok($name, '.')][] = [
                'methods' => implode('|', $methods),
                'uri' => $route->uri(),
                'name' => $name,
                'middleware' => implode(', ', $route->gatherMiddleware()),
            ];
        }
        ksort($modules);

        return $modules;
    }

    /** @return array{0:\Illuminate\Support\Collection,1:\Illuminate\Support\Collection} */
    private function getAccessControl()
    {
        try {
            $roles = Role::withCount(['permissions', 'users'])->orderBy('name')->get();
            $permissionGroups = Permission::with('roles:id,name')
                ->orderBy('name')
                ->get()
                // "api_key-index" => group "api_key"; names without a dash keep their own name
                ->groupBy(fn($permission) => str_contains($permission->name, '-')
                    ? substr($permission->name, 0, strrpos($permission->name, '-'))
                    : $permission->name);

            return [$roles, $permissionGroups];
        } catch (\Throwable) {
            return [collect(), collect()];
        }
    }
}
