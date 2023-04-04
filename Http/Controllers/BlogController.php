<?php

namespace Modules\Blogs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Base\Entities\Photo;
use Modules\Blogs\Entities\Blog;
use Modules\Blogs\Entities\BlogCategory;
use Modules\Blogs\Entities\BlogTag;
use Modules\Blogs\Entities\Tag;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->brand_id) {
            $items = Blog::where('brand_id', Auth::user()->brand_id)->get();
        }else {
            $items = Blog::all();
        }

        return view('blogs::index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->brand_id) {
            $categories = BlogCategory::where('brand_id', Auth::user()->brand_id)->get();
            $tags = Tag::where('brand_id', Auth::user()->brand_id)->get();
        }else {
            $categories = BlogCategory::all();
            $tags = Tag::all();
        }

        return view('blogs::create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lang' => 'required',
            'brand_id' => 'required',
            'title' => 'required',
        ]);
        try {
            $blog = Blog::create([
                'lang' => $request->lang,
                'brand_id' => $request->brand_id,
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => $request->slug,
                'short_text' => $request->short_text,
                'body' => $request->body,
                'image_alt' => $request->image_alt,
                'image' => (isset($request->image)?file_store($request->image, 'assets/uploads/photos/blogs_images/','photo_'):null),
                'banner' => (isset($request->banner)?file_store($request->banner, 'assets/uploads/photos/blogs_banners/','photo_'):null)
            ]);

            if (isset($request->tags)){
                foreach ($request->tags as $tag){
                    $bt = BlogTag::create([
                        'blog_id' => $blog->id,
                        'tag_id' => $tag
                    ]);
                }
            }

            return redirect()->route('blogs.index')->with('flash_message', 'با موفقیت ثبت شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('blogs::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Blog $blog)
    {
        if (Auth::user()->brand_id) {
            $categories = BlogCategory::where('brand_id', Auth::user()->brand_id)->get();
            $tags = Tag::where('brand_id', Auth::user()->brand_id)->get();
        }else {
            $categories = BlogCategory::all();
            $tags = Tag::all();
        }

        $blog_tags = $blog->tags->pluck('tag_id')->toArray();

        return view('blogs::edit', compact('blog', 'categories', 'tags', 'blog_tags'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Blog $blog)
    {
        try {
            if ($request->lang) {
                $blog->lang = $request->lang;
            }
            if ($request->brand_id) {
                $blog->brand_id = $request->brand_id;
            }
            $blog->category_id = $request->category_id;
            $blog->title = $request->title;
            $blog->slug = $request->slug;
            $blog->short_text = $request->short_text;
            $blog->body = $request->body;
            $blog->image_alt = $request->image_alt;
            if (isset($request->image)) {
                if ($blog->image){
                    File::delete($blog->image);
                }
                $blog->image = file_store($request->image, 'assets/uploads/photos/blogs_images/','photo_');
            }
            if (isset($request->banner)) {
                if ($blog->banner){
                    File::delete($blog->banner);
                }
                $blog->banner = file_store($request->banner, 'assets/uploads/photos/blogs_banners/','photo_');
            }

            $blog->save();

            $deleted = BlogTag::where('blog_id', $blog->id)->whereNotIn('tag_id', $request->tags)->delete();

            if (isset($request->tags)){
                foreach ($request->tags as $tag){
                    $old = BlogTag::where('blog_id', $blog->id)->where('tag_id', $tag)->first();
                    if (!$old) {
                        $bt = BlogTag::create([
                            'blog_id' => $blog->id,
                            'tag_id' => $tag
                        ]);
                    }
                }
            }

            return redirect()->route('blogs.index')->with('flash_message', 'با موفقیت بروزرسانی شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Blog $blog)
    {
        try {
            $blog->delete();

            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }
}
