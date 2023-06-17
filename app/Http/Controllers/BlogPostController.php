<?php

namespace App\Http\Controllers;


use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Mockery\Exception;

class BlogPostController extends HomeController
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function add(Request $request)
    {
        try {

            $post = [
                'title' => $request->name,
                'description' => $request->description,
            ];

            Blog::create($post);
            $response['message'] = "success";
            $response['status'] = 1;
        } catch (\Exception $ex) {

            $response['message'] = $ex->getMessage();
            $response['status'] = 0;
        }

        return $response;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function editBlog(Request $request)
    {
        try {

            $id = $request->route('id');
            $data = Blog::where('id', $id)
                ->first();
            $response['result'] = $data;
            $response['message'] = "success";
            $response['status'] = 1;
        } catch (Exception $ex) {

            $response['message'] = $ex->getMessage();
            $response['status'] = 0;
        }

        return $response;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function updateBlog(Request $request)
    {
        try {
            Blog::where('id', $request->id)
                ->update([
                    'title' => $request->name,
                    'description' => $request->description
                ]);

            $response['message'] = "success";
            $response['status'] = 1;
        } catch (Exception $ex) {

            $response['message'] = $ex->getMessage();
            $response['status'] = 0;
        }

        return $response;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function updateStatus(Request $request)
    {
        try {
            Blog::where('id', $request->id)
                ->update([
                    'status' => 0
                ]);

            $response['message'] = "Updated Sucessfully";
            $response['status'] = 1;
        } catch (Exception $ex) {

            $response['message'] = $ex->getMessage();
            $response['status'] = 0;
        }

        return $response;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function deleteBlog(Request $request)
    {
        try {
            Blog::where('id', $request->id)
                ->delete();

            $response['message'] = "Deleted Successfully";
            $response['status'] = 1;
        } catch (Exception $ex) {

            $response['message'] = $ex->getMessage();
            $response['status'] = 0;
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view(Request $request)
    {
        $getBlog = Blog::find($request->id);

        $view_data = [
            'comment' => Comment::where([Comment::USER_ID => \Auth::user()->id, Comment::BLOG_ID => $request->id])->get(),
            'data' => $getBlog
        ];

        return view('blogView', $view_data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function addComment(Request $request)
    {
        try {
            $addComment = [
                Comment::BLOG_ID => $request->blog_id,
                Comment::COMMENT => $request->comment,
                Comment::USER_ID => \Auth::user()->id
            ];
            Comment::addUpdate($addComment);

            $response['message'] = "Added Successfully";
            $response['status'] = 1;
        } catch (Exception $ex) {

            $response['message'] = $ex->getMessage();
            $response['status'] = 0;
        }

        return $response;

    }
}
