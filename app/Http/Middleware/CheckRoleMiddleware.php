<?php

namespace App\Http\Middleware;

use App\Traits\AdminFirstRouteTrait;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use App\Models\Custody;

class CheckRoleMiddleware {
  use ApiResponseTrait, AdminFirstRouteTrait;

  public function handle($request, Closure $next) {

        if(!auth()->user()->is_active)
        {
          auth()->logout();
          return redirect(route('show.login'));
        }
    //    dd(auth()->user()->roles()->first());

         $permissions = auth()->user()->roles()->first()->permissions()->pluck('name');
         $permissions->push('home');
         $permissions->push('logout');
         $permissions = $permissions->toArray();
        // some exception 
        $excpetions = [
            'edit-profile',
            'home',
            'logout', 
            'tasks.rate',
            'tasks.time',
            'projects.agree',
            'projects.accepshow',
            'projects.accept'
          ];

        $currunt_route = Route::currentRouteName();

     

        if(!in_array($currunt_route,$excpetions))
        {
          $currunt_route = str_replace('update-settings','settings',$currunt_route);
          $currunt_route = str_replace('store','create',$currunt_route);
          $currunt_route = str_replace('adminsfile.destroy','admins.destroy',$currunt_route);
          $currunt_route = str_replace('projectsfile.destroy','projects.destroy',$currunt_route);
          $currunt_route = str_replace('import','create',$currunt_route);
        }

        if(!str_contains($currunt_route,'settings') AND !\str_contains($currunt_route,'sms') AND !in_array($currunt_route,$excpetions))
        {
          $currunt_route = str_replace('update','edit',$currunt_route);
        }
    
         if (!in_array($currunt_route, $permissions) AND !in_array($currunt_route,$excpetions)) {

          $msg = trans('auth.not_authorized');
          if ($request->ajax()) {
            return $this->unauthorizedReturn(['type' => 'notAuth']);
          }
    
          if (!count($permissions)) {
            session()->invalidate();
            session()->regenerateToken();
            return redirect(route('show.login'));
          }
    
          session()->flash('danger', $msg);
    
          return redirect()->route($this->getAdminFirstRouteName($permissions));
          
        }
        


        \View::share('roles', $permissions);
    
        return $next($request);
      }
}
