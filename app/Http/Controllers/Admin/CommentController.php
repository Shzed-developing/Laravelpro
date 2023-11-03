<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use RealRashid\SweetAlert\Facades\Alert;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $comments = Comment::whereApproved(1)->latest()->paginate(20);
        return view('admin.comments.all', compact('comments'));
    }

    public function unapproved()
    {
        $comments = Comment::query();

        if($keyword = request('search')) {
            $comments->where('comment', 'LIKE', "%{$keyword}%")->orWhereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            });
        }

        $comments = $comments->whereApproved(0)->latest()->paginate(20);
        return view('admin.comments.unapproved', compact('comments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update(['approved' => 1]);

        Alert::success('کامنت تایید شد');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        Alert::success('نظر شما با موفقیت حذف شد');

        return back();
    }
}
