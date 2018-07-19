<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Category;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return view('home')->with('data', $categories);
    }

    public function create(Request $request)
    {

        //print_r($request->category);die();
        $blog = new Blog;
        $blog->author_id = Auth::user()->id;
        $blog->name = $request->name;
        $blog->description = $request->description;
        $blog->save();

        
        foreach ($request->category as $cat) {
        $blog_category = new BlogCategory;
        $blog_category->blog_id = $blog->id;
        $blog_category->category_id = $cat;
        $blog_category->save();
        
        }
        return back();

         
    }

    public function getBlogs(Request $request)
    {
        
        $date = isset($request->date) ? $request->date : null;
        $categories = isset($request->categories) ? $request->categories : null;
        $author = isset($request->author) ? $request->author : null;

        $blogs = Blog::all();

        //Filter by date

        $blogs = $blogs->when($date, function ($query, $date) {
                            return $query->where('created_at', $date);
                        }, function ($query) use ($blogs) {
                           return $blogs;
                        });

        //Filter by categories
        if (empty($categories)) {
            $blog_ids = null;
        }else{
            $blog_ids = BlogCategory::whereIn('category_id', $categories)->pluck('blog_id');
        }



        $blogs = $blogs->when($blog_ids, function ($query, $blog_ids) {
                            return $query->whereIn('id', $blog_ids);
                        }, function ($query) use ($blogs) {
                           return $blogs;
                        });

        //Filter by author
        $author_ids =[];

        if (is_null($author)) {
            $author_ids = null;
        }else{
            $author_ids = User::where('name', 'LIKE', '%'.$author.'%')->pluck('id');
        }


        $blogs = $blogs->when($author_ids, function ($query, $author_ids) {
                            return $query->whereIn('author_id', $author_ids);
                        }, function ($query) use ($blogs) {
                           return $blogs;
                        });


        $_blogs = [];
           foreach ($blogs as $blog) {
            $_blogs[] = ['name' => $blog->name, 'description' => $blog->description, 'created_at' => $blog->created_at, 'updated_at' => $blog->updated_at, 'author' => $blog->author->name, 'categories' => $blog->categories->pluck('name')];
           }

      return $_blogs; 

    } 
}
