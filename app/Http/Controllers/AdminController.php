<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\Blogtag;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\User;
use App\Models\Role;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;



class AdminController extends Controller
{
    //index

    public function index(Request $request)
    {
        //check if not user and if path is not login then redirects to login
        //prevents access to other pages
        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }

        //checks if not  user and if path is login returns welcome page
        if (!Auth::check() && $request->path() == 'login') {
            return view('welcome');
        }



        //logged in check if admin
        $user = Auth::user();

        if ($user['usertype'] == 'User') {
            return redirect('/login');
        }

        if ($request->path() == 'login') {
            return redirect('/');
        }

        return $this->checkforpermission($user, $request);
    }

    //check for permmision
    public function checkforPermission($user, $request)
    {
        $permission = json_decode($user->Role->permission);

        $hasPermission = false;

        if (!$permission) {
            return view('Welcome');
        }


        foreach ($permission as $p) {
            if ($p->name == $request->path()) {

                if ($p->read) {
                    $hasPermission = true;
                }
            }
        }
        if (Str::contains($request->path(), 'editblog')) {
            $hasPermission = true;
        }



        if ($hasPermission) {
            return view('welcome');
        } else {
            //return view('welcome');
            return view('Notfound');
        }
    }
    //logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    //add tag
    public function addTag(Request $request)
    {


        //validate request
        $this->validate($request, [

            'tagName' => 'required'

        ]);

        return Tag::create([
            'tagName' => $request->tagName,

        ]);
    }

    //edit tag 
    public function editTag(Request $request)
    {
        //validate request
        $this->validate($request, [

            'tagName' => 'required',
            'id' => 'required'
        ]);

        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName,
        ]);
    }

    //delete tag

    public function deleteTag(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',

        ]);

        return Tag::where('id', $request->id)->delete();
    }


    //get tag

    public function getTags()
    {
        return Tag::OrderBy('id', 'desc')->get();
    }

    //Upload image

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,png,jpg|',

        ]);
        $picName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $picName);
        return $picName;
    }

    //upload vue-editor image
    public function uploadeditorimage(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,png,jpg|',

        ]);
        $picName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $picName);
        return response()->json([
            'success' => 1,
            'file' => [

                'url' => "http://fullstack.test/uploads/$picName",
            ]
        ]);

        //return $picName;


    }




    //delete image from modal

    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName, false);
        return 'done';
    }

    //delete from DB

    public function deleteFileFromServer($fileName, $hasFullPath = false)
    {
        if (!$hasFullPath) {
            $filePath = public_path() . '/uploads/' . $fileName;
        }
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return;
    }


    //Add category
    public function addcategory(Request $request)
    {
        //validate request
        $this->validate($request, [

            'categoryName' => 'required',
            'IconImage' => 'required',
        ]);

        return Category::create([
            'categoryName' => $request->categoryName,
            'IconImage' => $request->IconImage,


        ]);
    }

    //get
    public function getcategory()
    {
        return Category::OrderBy('id', 'desc')->get();
    }


    //edit
    public function editcategory(Request $request)
    {



        //validate request
        $this->validate($request, [
            'id' => 'required',
            'categoryName' => 'required',
            'IconImage' => 'required'


        ]);

        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'IconImage' => $request->IconImage,

        ]);
    }


    //delete category
    public function deletecategory(Request $request)
    {
        //first delete original file from server 
        $this->deleteFileFromServer($request->IconImage);
        //validate request
        $this->validate($request, [
            'id' => 'required',

        ]);

        return Category::where('id', $request->id)->delete();
    }


    //create admin user

    public function createadminuser(Request $request)
    {
        //validate request
        $this->validate($request, [

            'fullname' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required',


        ]);

        $password = bcrypt($request->password);

        $user =  User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);
        return $user;
    }

    public function getusers()
    {
        return User::OrderBy('id', 'desc')->get();
    }

    //edit

    public function editusers(Request $request)
    {
        //validate request
        $this->validate($request, [
            'id' => 'required',
            'fullname' => 'required',
            'email' => "bail|required|email|unique:users,id,$request->id",
            'password' => 'min:6',
        ]);

        $data = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'role_id' => $request->role_id
        ];

        if ($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }

        $user = User::where('id', $request->id)->update($data);
        return $user;
    }

    //Admin Login 

    public function adminlogin(Request $request)
    {
        //validate request
        $this->validate($request, [
            'email' => "bail|required|email",
            'password' => 'bail|required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->role->isAdmin == 0) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect Login Details',
                ], 401);
            }
            return response()->json([
                'msg' => 'You are logged in',
                'user' => $user,

            ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect login details',
            ], 401);
        }
    }



    //Roles 
    //Add category
    public function createadminroles(Request $request)
    {
        //validate request
        $this->validate($request, [

            'roleName' => 'required',
        ]);

        return Role::create([
            'roleName' => $request->roleName,


        ]);
    }

    //get
    public function getroles()
    {
        return Role::all();
    }
    //edit
    public function editroles(Request $request)
    {
        //validate request
        $this->validate($request, [

            'roleName' => 'required',
            'id' => 'required'
        ]);

        return Role::where('id', $request->id)->update([
            'roleName' => $request->roleName,
        ]);
    }

    public function assignrole(Request $request)
    {
        //validate request
        $this->validate($request, [

            'permission' => 'required',
            'id' => 'required'

        ]);
        return Role::where('id', $request->id)->update([
            'permission' => $request->permission
        ]);
    }

    public function slug()
    {
        $title = 'This is a nice title, king kevo';
        Blog::create([
            'title' => $title,
            'post' =>  'post',
            'post_excerpt' => 'post_excerpt',
            'user_id' => 1,
            'metaDescription' => 'metaDescription',
        ]);


        return $title;
    }


    public function createBlog(Request $request)
    {


        $this->validate($request, [
            'title' => 'required',
            'post' =>  'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
        ]);
        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogtags = [];
        DB::beginTransaction();

        //Log::info($blogCategories);
        try {
            $blog = Blog::create([
                'title' => $request->title,
                'slug' => $request->title,
                'post' =>  $request->post,
                'post_excerpt' => $request->post_excerpt,
                'user_id' => Auth::User()->id,
                'metaDescription' => $request->metaDescription,
                'jsonData' => $request->jsonData,
            ]);


            foreach ($categories as $c) {

                array_push($blogCategories, ['category_id' => $c, 'blog_id' => $blog->id]);
            }
            Blogcategory::insert($blogCategories);

            foreach ($tags as $t) {

                array_push($blogtags, ['tag_id' => $t, 'blog_id' => $blog->id]);
            }
            Blogtag::insert($blogtags);
            DB::commit();
            return 'done';
        } catch (\Throwable $th) {
            DB::rollback();
            return 'Not done';
        }
    }

    public function blogdata(Request $request)
    {
        return Blog::with(['tag', 'cat'])->orderBy('id', 'desc')->paginate($request->total);
    }

    public function deleteBlog(Request $request)
    {
        return Blog::where('id', $request->id)->delete();
    }

    public function singleBlogItem(Request $request, $id)
    {
        return Blog::with(['tag', 'cat'])->where('id', $id)->first();
    }

    public function updateBlog(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);
        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];

        DB::beginTransaction();
        try {
            $blog = Blog::where('id', $id)->update([
                'title' => $request->title,
                'slug' => $request->title,
                'post' => $request->post,
                'post_excerpt' => $request->post_excerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription,
                'jsonData' => $request->jsonData,
            ]);
 
            

            // insert blog categories
            foreach ($categories as $c) {
                array_push($blogCategories, ['category_id' => $c, 'blog_id' => $id]);
            }

            // delete all previous categories
            Blogcategory::where('blog_id', $id)->delete();
            Blogcategory::insert($blogCategories);
            // insert blog tags
            foreach ($tags as $t) {
                array_push($blogTags, ['tag_id' => $t, 'blog_id' => $id]);
            }
            Blogtag::where('blog_id', $id)->delete();
            Blogtag::insert($blogTags);
            DB::commit();
            return 'done';
        } catch (\Throwable $e) {

            DB::rollback();
            return 'not done';
        }

    }

    

    
}
