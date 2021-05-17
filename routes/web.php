<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminCheck;

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
//

Route::prefix('app')->middleware(Admincheck::class)->group(function(){
   
 Route::post('/create_tag',[AdminController::class,'addTag']);
Route::get('/get_tags',[AdminController::class,'getTags']);
Route::post('/edit_tag',[AdminController::class,'editTag']);
Route::post('/delete_tag',[AdminController::class,'deleteTag']);
Route::post('/upload',[AdminController::class,'upload']);
Route::post('/delete_image',[AdminController::class,'deleteImage']);
Route::post('/create_category',[AdminController::class,'addcategory']);
Route::get('/get_category',[AdminController::class,'getcategory']);
Route::post('/edit_category',[AdminController::class,'editcategory']);
Route::post('/delete_category',[AdminController::class,'deletecategory']);
Route::post('/create_user',[AdminController::class,'createadminuser']);
Route::get('/get_users',[AdminController::class,'getusers']);
Route::post('/edit_users',[AdminController::class,'editusers']);
Route::post('/admin_login',[AdminController::class,'adminlogin']);
//roles routes
Route::get('/get_roles',[AdminController::class,'getroles']);
Route::post('/create_roles',[AdminController::class,'createadminroles']);
Route::post('/edit_roles',[AdminController::class,'editroles']);
Route::post('/edit_roles',[AdminController::class,'editroles']);
Route::post('/assign_roles',[AdminController::class,'assignrole']);
//comment routes



//blog routes
Route::post('/create-blog',[AdminController::class,'createBlog']);
Route::get('/blogsdata',[AdminController::class,'blogdata']);
Route::get('/blog_single/{id}',[AdminController::class,'singleBlogItem']);
Route::post('/update_blog/{id}',[AdminController::class,'updateBlog']);

Route::post('/delete_blog',[AdminController::class,'deleteblog']);









});
//cannot use middleware because of csrf token exception in verifycsrftoken page
Route::post('/createBlog',[AdminController::class,'uploadeditorimage']);
//do not have app extension
Route::get('slug',[AdminController::class,'slug']);
Route::get('blogsdata',[AdminController::class,'blogdata']);




Route::get('/',[AdminController::class,'index']);
Route::get('logout',[AdminController::class,'logout']);
Route::any('{slug}',[AdminController::class,'index'])->where('slug', '([A-z\d -\/_.]+)?');
















