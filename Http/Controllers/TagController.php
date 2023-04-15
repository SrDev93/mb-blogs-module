<?php

namespace Modules\Blogs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Blogs\Entities\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\request()->session()->has('brand_id')){
            $items = Tag::where('brand_id', \request()->session()->get('brand_id'))->get();
        }elseif (Auth::user()->brand_id) {
            $items = Tag::where('brand_id', Auth::user()->brand_id)->get();
        }else {
            $items = Tag::all();
        }

        return view('blogs::tags.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('blogs::tags.create');
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
            'name' => 'required|unique:tags',
        ]);
        try {
            $tag = Tag::create([
                'lang' => $request->lang,
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'page_title' => $request->page_title,
                'meta_keywords' => $request->meta_keywords,
                'meta_description' => $request->meta_description,
            ]);

            return redirect()->route('tags.index')->with('flash_message', 'با موفقیت ثبت شد');
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
        return view('blogs::tags.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Tag $tag)
    {
        return view('blogs::tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Tag $tag)
    {
        try {
            if ($request->lang) {
                $tag->lang = $request->lang;
            }
            if ($request->brand_id) {
                $tag->brand_id = $request->brand_id;
            }
            $tag->name = $request->name;
            $tag->page_title = $request->page_title;
            $tag->meta_keywords = $request->meta_keywords;
            $tag->meta_description = $request->meta_description;
            $tag->save();

            return redirect()->route('tags.index')->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();

            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }
}
