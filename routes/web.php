<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */






// Auth::routes();
Route::group(['middleware' => ['web']] , function () {
    Route::get('/', [LoginController::class, 'showloginform'])->name('show.login');
    Route::post('admin-login', [LoginController::class, 'login'])->name('admin-login');
});

Route::group(['middleware' => ['auth',  'admin-lang' , 'web' , 'check-role'] , 'namespace' => 'Dashboard'], function () {

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('edit-profile', [AdminController::class, 'editProfile'])->name('edit-profile');
    Route::put('update-profile', [AdminController::class, 'updateProfile'])->name('update-profile');
    Route::get('home', [
        'uses'      => 'HomeController@index',
        'as'        => 'home',
        'title'     => 'dashboard.home',
        'type'      => 'parent'
    ]);
/*------------ start Of roles ----------*/
    Route::get('roles', [
        'uses'      => 'RoleController@index',
        'as'        => 'roles.index',
        'title'     => 'dashboard.roles',
        'type'      => 'parent',
        'child'     => [ 'roles.create','roles.edit', 'roles.destroy'  ,'roles.deleteAll']
    ]);

    # roles store
    Route::get('roles/create', [
        'uses'  => 'RoleController@create',
        'as'    => 'roles.create',
        'type'      => 'child',
        'title' => ['actions.add', 'dashboard.role']
    ]);

    # roles store
    Route::post('roles/store', [
        'uses'  => 'RoleController@store',
        'as'    => 'roles.store',
        'type'      => 'child',
        'title' => ['actions.add', 'dashboard.role']
    ]);

    # roles update
    Route::get('roles/{id}/edit', [
        'uses'  => 'RoleController@edit',
        'as'    => 'roles.edit',
        'type'      => 'child',
        'title' => ['actions.edit', 'dashboard.role']
    ]);

    # roles update
    Route::put('roles/{id}', [
        'uses'  => 'RoleController@update',
        'as'    => 'roles.update',
        'type'      => 'child',
        'title' => ['actions.edit', 'dashboard.role']
    ]);

    # roles delete
    Route::delete('roles/{id}', [
        'uses'  => 'RoleController@destroy',
        'as'    => 'roles.destroy',
        'type'      => 'child',
        'title' => ['actions.delete', 'dashboard.role']
    ]);
    #delete all roles
    Route::post('delete-all-roles', [
        'uses'  => 'RoleController@deleteAll',
        'as'    => 'roles.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.roles']
    ]);
/*------------ end Of roles ----------*/
/*------------ start Of admins ----------*/
    Route::get('admins', [
        'uses'      => 'AdminController@index',
        'as'        => 'admins.index',
        'title'     => 'dashboard.admins',
        'type'      => 'parent',
        'child'     => [ 'admins.create','admins.edit','admins.destroy'  ,'admins.deleteAll']
    ]);

    # admins store
    Route::get('admins/create', [
        'uses'  => 'AdminController@create',
        'as'    => 'admins.create',
        'title' => ['actions.add', 'dashboard.admin']
    ]);

    # admins store
    Route::post('admins/store', [
        'uses'  => 'AdminController@store',
        'as'    => 'admins.store',
        'title' => ['actions.add', 'dashboard.admin']
    ]);

    # admins update
    Route::get('admins/{id}/edit', [
        'uses'  => 'AdminController@edit',
        'as'    => 'admins.edit',
        'title' => ['actions.edit', 'dashboard.admin']
    ]);

    # admins update
    Route::put('admins/{id}', [
        'uses'  => 'AdminController@update',
        'as'    => 'admins.update',
        'title' => ['actions.edit','dashboard.admin']
    ]);

    # admins delete
    Route::delete('admins/{id}', [
        'uses'  => 'AdminController@destroy',
        'as'    => 'admins.destroy',
        'title' => ['actions.delete', 'dashboard.admin']
    ]);
    #delete all admins
    Route::post('delete-all-admins', [
        'uses'  => 'AdminController@deleteAll',
        'as'    => 'admins.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.admins']
    ]);

/*------------ end Of admins ----------*/
/*------------ start Of customers ----------*/
    Route::get('customers', [
        'uses'      => 'CustomersController@index',
        'as'        => 'customers.index',
        'title'     => 'dashboard.customers',
        'type'      => 'parent',
        'child'     => [ 'customers.create','customers.edit','customers.destroy' ]
    ]);

    # customers store
    Route::get('customers/create', [
        'uses'  => 'CustomersController@create',
        'as'    => 'customers.create',
        'title' => ['actions.add', 'dashboard.customers']
    ]);

    # customers store
    Route::post('customers/store', [
        'uses'  => 'CustomersController@store',
        'as'    => 'customers.store',
        'title' => ['actions.add', 'dashboard.customers']
    ]);

    # customers update
    Route::get('customers/{id}/edit', [
        'uses'  => 'CustomersController@edit',
        'as'    => 'customers.edit',
        'title' => ['actions.edit', 'dashboard.customers']
    ]);

    # customers update
    Route::put('customers/{id}', [
        'uses'  => 'CustomersController@update',
        'as'    => 'customers.update',
        'title' => ['actions.edit','dashboard.customers']
    ]);

    # customers delete
    Route::delete('customers/{id}', [
        'uses'  => 'CustomersController@destroy',
        'as'    => 'customers.destroy',
        'title' => ['actions.delete', 'dashboard.customers']
    ]);

/*------------ end Of customers ----------*/
/*------------ start Of projects ----------*/
    Route::get('projects', [
        'uses'      => 'ProjectsController@index',
        'as'        => 'projects.index',
        'title'     => 'dashboard.projects',
        'type'      => 'parent',
        'child'     => [ 'projects.create','projects.show','projects.rate','projects.report','projects.edit','projects.destroy' ]
    ]);

    # projects show
    Route::get('projects/{id}/report', [
        'uses'  => 'ProjectsController@report',
        'as'    => 'projects.report',
        'title' => ['actions.show', 'dashboard.report']
    ]);
    # projects show
    Route::get('projects/{id}/show', [
        'uses'  => 'ProjectsController@show',
        'as'    => 'projects.show',
        'title' => ['actions.show', 'dashboard.projects']
    ]);
    # projects store
    Route::get('projects/create', [
        'uses'  => 'ProjectsController@create',
        'as'    => 'projects.create',
        'title' => ['actions.add', 'dashboard.projects']
    ]);

    # projects rate
    Route::post('projects/rate/{project}', [
        'uses'  => 'ProjectsController@rate',
        'as'    => 'projects.rate',
        'title' => ['actions.add', 'dashboard.rate']
    ]);
    # projects store
    Route::post('projects/store', [
        'uses'  => 'ProjectsController@store',
        'as'    => 'projects.store',
        'title' => ['actions.add', 'dashboard.projects']
    ]);

    # projects update
    Route::get('projects/{id}/edit', [
        'uses'  => 'ProjectsController@edit',
        'as'    => 'projects.edit',
        'title' => ['actions.edit', 'dashboard.projects']
    ]);

    # projects update
    Route::put('projects/{id}', [
        'uses'  => 'ProjectsController@update',
        'as'    => 'projects.update',
        'title' => ['actions.edit','dashboard.projects']
    ]);

    # projects delete
    Route::delete('projects/{id}', [
        'uses'  => 'ProjectsController@destroy',
        'as'    => 'projects.destroy',
        'title' => ['actions.delete', 'dashboard.projects']
    ]);
    # projects delete
    Route::delete('projects/{id}/file', [
        'uses'  => 'ProjectsController@deleteFile',
        'as'    => 'projectsfile.destroy',
        'title' => ['actions.delete', 'dashboard.projectsfile']
    ]);

/*------------ end Of projects ----------*/
/*------------ start Of tasks ----------*/
    Route::get('tasks', [
        'uses'      => 'TasksController@index',
        'as'        => 'tasks.index',
        'title'     => 'dashboard.tasks',
        'type'      => 'parent',
        'child'     => [ 'tasks.create','tasks.show','belongs-tasks','tasks.edit','tasks.destroy' ]
    ]);

     # tasks show
     Route::get('tasks/{id}/show', [
        'uses'  => 'TasksController@show',
        'as'    => 'tasks.show',
        'title' => ['actions.show', 'dashboard.tasks']
    ]);
     # tasks belongs-tasks
     Route::get('belongs-tasks', [
        'uses'  => 'TasksController@belongs-tasks',
        'as'    => 'belongs-tasks',
        'title' => ['actions.show', 'dashboard.belongs-tasks']
    ]);

    # tasks store
    Route::get('tasks/create', [
        'uses'  => 'TasksController@create',
        'as'    => 'tasks.create',
        'title' => ['actions.add', 'dashboard.tasks']
    ]);

    # tasks store
    Route::post('tasks/store', [
        'uses'  => 'TasksController@store',
        'as'    => 'tasks.store',
        'title' => ['actions.add', 'dashboard.tasks']
    ]);

    # tasks update
    Route::get('tasks/{id}/edit', [
        'uses'  => 'TasksController@edit',
        'as'    => 'tasks.edit',
        'title' => ['actions.edit', 'dashboard.tasks']
    ]);

    # tasks update
    Route::put('tasks/{id}', [
        'uses'  => 'TasksController@update',
        'as'    => 'tasks.update',
        'title' => ['actions.edit','dashboard.tasks']
    ]);
   
    Route::put('tasks/{id}/rate', [
        'uses'  => 'TasksController@rateTask',
        'as'    => 'tasks.rate',
        'title' => ['actions.edit','dashboard.tasks']
    ]);

    Route::put('tasks/{userTask}/time', [
        'uses'  => 'TasksController@timeTask',
        'as'    => 'tasks.time',
        'title' => ['actions.edit','dashboard.tasks']
    ]);

    # tasks delete
    Route::delete('tasks/{id}', [
        'uses'  => 'TasksController@destroy',
        'as'    => 'tasks.destroy',
        'title' => ['actions.delete', 'dashboard.tasks']
    ]);
    # tasks delete
    Route::delete('tasks/{id}/file', [
        'uses'  => 'TasksController@deleteFile',
        'as'    => 'tasksfile.destroy',
        'title' => ['actions.delete', 'dashboard.tasksfile']
    ]);

/*------------ end Of tasks ----------*/
/*------------ start Of objective ----------*/
    Route::get('objective', [
        'uses'      => 'ObjectiveController@index',
        'as'        => 'objective.index',
        'title'     => 'dashboard.objective',
        'type'      => 'parent',
        'child'     => [ 'objective.create','objective.show','belongs-objective','objective.edit','objective.destroy' ]
    ]);

     # objective show
     Route::get('objective/{id}/show', [
        'uses'  => 'ObjectiveController@show',
        'as'    => 'objective.show',
        'title' => ['actions.show', 'dashboard.objective']
    ]);
     # objective belongs-objective
     Route::get('belongs-objective', [
        'uses'  => 'ObjectiveController@belongs-objective',
        'as'    => 'belongs-objective',
        'title' => ['actions.show', 'dashboard.belongs-objective']
    ]);

    # objective store
    Route::get('objective/create', [
        'uses'  => 'ObjectiveController@create',
        'as'    => 'objective.create',
        'title' => ['actions.add', 'dashboard.objective']
    ]);

    # objective store
    Route::post('objective/store', [
        'uses'  => 'ObjectiveController@store',
        'as'    => 'objective.store',
        'title' => ['actions.add', 'dashboard.objective']
    ]);

    # objective update
    Route::get('objective/{id}/edit', [
        'uses'  => 'ObjectiveController@edit',
        'as'    => 'objective.edit',
        'title' => ['actions.edit', 'dashboard.objective']
    ]);

    # objective update
    Route::put('objective/{id}', [
        'uses'  => 'ObjectiveController@update',
        'as'    => 'objective.update',
        'title' => ['actions.edit','dashboard.objective']
    ]);
   
    Route::put('objective/{id}/rate', [
        'uses'  => 'ObjectiveController@rateTask',
        'as'    => 'objective.rate',
        'title' => ['actions.edit','dashboard.objective']
    ]);

    Route::put('objective/{userTask}/time', [
        'uses'  => 'ObjectiveController@timeTask',
        'as'    => 'objective.time',
        'title' => ['actions.edit','dashboard.objective']
    ]);

    # objective delete
    Route::delete('objective/{id}', [
        'uses'  => 'ObjectiveController@destroy',
        'as'    => 'objective.destroy',
        'title' => ['actions.delete', 'dashboard.objective']
    ]);
    # objective delete
    Route::delete('objective/{id}/file', [
        'uses'  => 'ObjectiveController@deleteFile',
        'as'    => 'objectivefile.destroy',
        'title' => ['actions.delete', 'dashboard.objectivefile']
    ]);

/*------------ end Of objective ----------*/

});




/*** update route if i added new routes  */
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Route::get('update-routes', function (){
    $routes_data    = [];

    foreach (Route::getRoutes() as $route) {
        if ($route->getName()){
            
            $check_permission = Permission::where('name',$route->getName())->count();

            if(!$check_permission)
            {
                $routes_data []   = [ 'name' => $route->getName() , 
                    'nickname_en' =>  $route->getName() ,
                    'nickname_ar' =>  $route->getName() ,
                    'guard_name' => 'web'
                ];
            }
            
        }
    }
    Permission::insert( $routes_data );

    $admin = App\Models\User::find(1);
    $role = Role::find(1);

    $role->givePermissionTo(Permission::all());
    $admin->assignRole('super-admin');

});


Route::get('files/storage/{filePath}', [AdminController::class,'fileStorageServe'])->where(['filePath' => '.*'])->name('serve.file');





 /*** USE AUTH AREA  */
 Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
 Route::post('login', [LoginController::class, 'login']);
 // REHIESTER
 Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
 Route::post('register', [RegisterController::class, 'register']);
 // routes/web.php
 Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
 Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
 Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
 Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
/*** USE AUTH AREA  */