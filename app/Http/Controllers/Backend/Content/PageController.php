<?php

namespace App\Http\Controllers\Backend\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Post;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $pages = Post::where('post_type', 'page')->latest()->paginate(10);
        return view('backend.content.page.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('backend.content.page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $data = $this->validatePage();

        unset($data['image']);
        unset($data['schedule_time']);

        if ($request->input('post_status') === 'schedule') {
            $data['schedule_time'] = Carbon::createFromFormat('d/m/Y', $request->input('schedule_time'))->format('Y-m-d');
        }
        $data['post_type'] = 'page';
        $data['revision_by'] = Auth::id();
        $data['update_by'] = Auth::id();
        $data['user_id'] = Auth::id();

        $upload = $request->input('thumb_status') == 1 && $request->hasFile('image') ? true : false;

        $location = str_replace(".", "", Str::limit($data['post_slug'], 30));
        $file = $request->file('image');

        DB::transaction(function () use ($data, $file, $upload, $location) {
            $data = Post::create($data);
            $folder = $data->id . '-' . $location;
            if ($upload) {
                $post_thumb = store_picture($file, 'pages/' . $folder, $location . '-' . time());
                $data->update(['post_thumb' => $post_thumb]);
            }
        });

        return redirect()->route('admin.page.index')->withFlashSuccess('Page Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Post $page)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $page
     * @return Factory|View
     */
    public function edit(Post $page)
    {
        return view('backend.content.page.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $data = $this->validate($request, [
            'post_title' => 'required|string|max:800',
            'post_slug' => 'required|string|max:800|unique:posts,post_slug,' . $id, // unique page slug
            'post_content' => 'required|string',
            'post_excerpt' => 'nullable|string|max:800',
            'post_status' => 'required|string|max:191',
            'schedule_time' => 'nullable|date_format:d/m/Y',
            'thumb_status' => 'nullable|string|max:155',
            'image' => 'nullable|max:800|mimes:jpeg,jpg,png,gif,webp',
        ]);

        unset($data['image']);
        unset($data['schedule_time']);

        if ($request->input('post_status') === 'schedule') {
            $data['schedule_time'] = Carbon::createFromFormat('d/m/Y', $request->input('schedule_time'))->format('Y-m-d');
        }
        $data['post_type'] = 'page';
        $data['revision_by'] = Auth::id();
        $data['update_by'] = Auth::id();

        $upload = $request->input('thumb_status') == 1 && $request->hasFile('image') ? true : false;

        $location = str_replace(".", "", Str::limit($data['post_slug'], 30));
        $file = $request->file('image');

        DB::transaction(function () use ($id, $data, $file, $upload, $location) {
            $folder = $id . '-' . $location;
            if ($upload) {
                $data['post_thumb'] = store_picture($file, 'pages/' . $folder, $location . '-' . time());
            }
            Post::find($id)->update($data);
        });

        return redirect()->route('admin.page.index')->withFlashSuccess('Page Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $page
     * @return Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $post = Post::withTrashed()->find($id);
        if ($post->trashed()) {
            $post->forceDelete();
            return \response([
                'status' => true,
                'icon' => 'success',
                'msg' => 'Page permanently deleted',
            ]);
        } else if ($post->delete()) {
            return \response([
                'status' => true,
                'icon' => 'success',
                'msg' => 'Page moved to trashed successfully',
            ]);
        }

        return \response([
            'status' => false,
            'icon' => 'error',
            'msg' => 'Delete failed',
        ]);
    }

    public function trashed($type = 'page')
    {
        $pages = Post::onlyTrashed()->where('post_type', $type)->orderByDesc('created_at')->paginate(10);
        return view('backend.content.page.trash', compact('pages', 'type'));
    }

    public function restore($id)
    {
        Post::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.page.index')->withFlashSuccess('Page Recovered Successfully');
    }


    public function get_slug_from_title(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:800',
        ]);
        $title = \request('title');
        $slug = Str::slug($title);

        if ($slug) {
            return $slug;
        }

        return str_replace(' ', '-', $title);
    }


    public function validatePage()
    {
        return \request()->validate([
            'post_title' => 'required|string|max:800',
            'post_slug' => 'required|string|max:800|unique:posts,post_slug', // unique page slug
            'post_content' => 'required|string',
            'post_excerpt' => 'nullable|string|max:800',
            'post_status' => 'required|string|max:191',
            'schedule_time' => 'nullable|date_format:d/m/Y',
            'thumb_status' => 'nullable|string|max:155',
            'image' => 'nullable|max:800|mimes:jpeg,jpg,png,gif,webp',
        ]);
    }

    public function editor_image_upload()
    {
        $file = request()->file('file');
        $name = 'editor-' . time();
        $data['location'] = '/' . store_picture($file, 'editor', $name);
        // dd($data['location']);
        return response()->json($data);
    }
}
