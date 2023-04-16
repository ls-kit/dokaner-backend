<?php

namespace App\Http\Controllers\Backend\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Post;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.content.faq.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('backend.content.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->fagValidator();

        unset($data['schedule_time']);

        if (request('post_status') === 'schedule') {
            $data['schedule_time'] = Carbon::createFromFormat('d/m/Y', request('schedule_time'))->format('Y-m-d');
        }
        $data['post_type'] = 'faq';
        $data['revision_by'] = Auth::id();
        $data['update_by'] = Auth::id();
        $data['user_id'] = Auth::id();

        Post::create($data);

        return redirect()->route('admin.faq.index')->withFlashSuccess('FAQs Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $faq)
    {
        return view('backend.content.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->fagValidator($id);

        unset($data['schedule_time']);

        if (request('post_status') === 'schedule') {
            $data['schedule_time'] = Carbon::createFromFormat('d/m/Y', request('schedule_time'))->format('Y-m-d');
        }
        $data['post_type'] = 'faq';
        $data['revision_by'] = Auth::id();
        $data['update_by'] = Auth::id();

        Post::find($id)->update($data);

        return redirect()->route('admin.faq.index')->withFlashSuccess('FAQs Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::withTrashed()->find($id);
        if ($post->trashed()) {
            $post->forceDelete();
            return \response([
                'status' => true,
                'icon' => 'success',
                'msg' => 'FAQ permanently deleted',
            ]);
        } else if ($post->delete()) {
            return \response([
                'status' => true,
                'icon' => 'success',
                'msg' => 'FAQ moved to trashed successfully',
            ]);
        }

        return \response([
            'status' => false,
            'icon' => 'error',
            'msg' => 'Delete failed',
        ]);
    }

    public function fagValidator(int $update_id = null)
    {
        return request()->validate([
            'post_title' => 'required|string|max:800',
            'post_slug' => 'required|string|max:800|' . $update_id ? 'unique:posts,post_slug,' . $update_id : 'unique:posts,post_slug', // unique page slug
            'post_content' => 'required|string',
            'post_excerpt' => 'nullable|string|max:800',
            'post_status' => 'required|string|max:191',
            'post_format' => 'required|string|max:191',
            'schedule_time' => 'nullable|date_format:d/m/Y',
        ]);
    }

    public function trashed()
    {
        $faqs = Post::onlyTrashed()->where('post_type', 'faq')->orderByDesc('created_at')->paginate(10);
        return view('backend.content.faq.trash', compact('faqs'));
    }

    public function restore($id)
    {
        Post::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.faq.index')->withFlashSuccess('FAQs Recovered Successfully');
    }
}
