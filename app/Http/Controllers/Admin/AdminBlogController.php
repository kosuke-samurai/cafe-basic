<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Cat;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    //ブログ一覧画面
    public function index()
    {
        //$blogs = Blog::all();

        //昇順変更
        //$blogs = Blog::latest('updated_at')->limit(10)->get();

        //ページネーション
        $blogs = Blog::latest('updated_at')->paginate(10);
        //$blogs = Blog::latest('updated_at')->simplepaginate(10);

        return view('admin.blogs.index', ['blogs' => $blogs]);
    }

    //ブログ投稿画面
    public function create()
    {
        return view('admin.blogs.create');
    }

    //ブログ投稿処理
    public function store(StoreBlogRequest $request)
    {
        $savedImagePath = $request->file('image')->store('blogs', 'public');
        $blog = new Blog($request->validated());
        $blog->image = $savedImagePath;
        $blog->save();

        return to_route('admin.blogs.index')->with('success', 'ブログを投稿できました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    //指定したIDのブログの編集画面
    public function edit(Blog $blog)
    {
        //以下は不要になった
        //$blog = Blog::findOrFail($id);

        $categories = Category::all();
        $cats = Cat::all();
        return view('admin.blogs.edit', ['blog' => $blog, 'categories' => $categories, 'cats' => $cats]);
    }

    //指定したIDのブログの更新処理
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $updateData = $request->validated();

        //画像を変更する場合
        if ($request->has('image')) {

            //変更前の画像削除
            Storage::disk('public')->delete($blog->image);
            //変更後の画像をアップロード、保存パスを更新対象データにセット
            $updateData['image'] = $request->file('image')->store('blogs', 'public');
        }

        //一対多
        $blog->category()->associate($updateData['category_id']);
        //多対多
        //$blog->cats()->attach($updateData['cats']);
        $blog->cats()->sync($updateData['cats']);

        $blog->update($updateData);

        return to_route('admin.blogs.index')->with('success', 'ブログを更新できました');
    }

    //指定したIDのブログの削除処理
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        Storage::disk('public')->delete($blog->image);

        return to_route('admin.blogs.index')->with('success', 'ブログを削除しました');
    }
}
