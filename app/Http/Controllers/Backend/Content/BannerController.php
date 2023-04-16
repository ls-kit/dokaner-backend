<?php

namespace App\Http\Controllers\Backend\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Post;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BannerController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Factory|View
   */
  public function index()
  {
    $banners = Post::where('post_type', 'banner')->latest()->paginate(10);
    return view('backend.content.banner.index', compact('banners'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Factory|View
   */
  public function create()
  {
    return view('backend.content.banner.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return \Illuminate\Http\Response
   * @throws \Throwable
   */
  public function store(Request $request)
  {
    $data = $this->bannerValidator();
    unset($data['image']);
    unset($data['schedule_time']);

    if (\request('post_status') === 'schedule') {
      $data['schedule_time'] = Carbon::parse(\request('schedule_time'))->toDateTimeString();
    }
    $data['post_type'] = 'banner';
    $data['revision_by'] = \auth()->id();
    $data['update_by'] = \auth()->id();
    $data['user_id'] = \auth()->id();

    $upload = \request('thumb_status') == 1 && \request()->hasFile('image') ? true : false;

    $location = str_replace(".", "", Str::limit($data['post_slug'], 30));
    $file = \request()->file('image');

    DB::transaction(function () use ($data, $file, $upload, $location) {
      $data = Post::create($data);
      $folder = $data->id . '-' . $location;
      if ($upload) {
        $post_thumb = store_picture($file, 'banner/' . $folder, $location . '-' . time());
        $data->update(['post_thumb' => $post_thumb]);
      }
    });

    return redirect()->route('admin.banner.index')->withFlashSuccess('Banner Created Successfully');
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
   * @param int $id
   * @return Factory|View
   */
  public function edit(Post $banner)
  {
    return view('backend.content.banner.edit', compact('banner'));
  }


  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   * @throws \Throwable
   */
  public function update(Request $request, $id)
  {
    $data = $this->bannerValidator($id);

    unset($data['image']);
    unset($data['schedule_time']);

    if (\request('post_status') === 'schedule') {
      $data['schedule_time'] = Carbon::parse(\request('schedule_time'))->toDateTimeString();
    }
    $data['post_type'] = 'banner';
    $data['revision_by'] = \auth()->id();
    $data['update_by'] = \auth()->id();

    $upload = \request('thumb_status') == 1 && \request()->hasFile('image') ? true : false;

    $location = str_replace(".", "", Str::limit($data['post_slug'], 30));
    $file = \request()->file('image');

    DB::transaction(function () use ($id, $data, $file, $upload, $location) {
      $folder = $id . '-' . $location;
      if ($upload) {
        $data['post_thumb'] = store_picture($file, 'banner/' . $folder, $location . '-' . time());
      }
      Post::find($id)->update($data);
    });

    return redirect()->route('admin.banner.index')->withFlashSuccess('Banner Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   * @throws \Exception
   */
  public function destroy($id)
  {
    $testimonial = Post::withTrashed()->findOrFail($id);
    if ($testimonial->trashed()) {
      $testimonial->forceDelete();
      return redirect()->route('admin.banner.index')->withFlashSuccess('Permanent Deleted Successfully');
    } else if ($testimonial->delete()) {
      return redirect()->route('admin.banner.index')->withFlashSuccess('Trashed Successfully');
    }
    return redirect()->route('admin.banner.index')->withFlashSuccess('Delete failed');
  }


  public function bannerValidator(int $update_id = null)
  {
    return request()->validate([
      'post_title' => 'required|string|max:800',
      'post_slug' => 'required|string|max:800|' . $update_id ? 'unique:posts,post_slug,' . $update_id : 'unique:posts,post_slug', // unique page slug
      'post_content' => 'required|string',
      'post_excerpt' => 'nullable|string|max:800',
      'post_status' => 'required|string|max:191',
      'schedule_time' => 'nullable|date_format:d-m-Y',
      'thumb_status' => 'nullable|string|max:155',
      'image' => 'nullable|max:800|mimes:jpeg,jpg,png,gif,webp',
    ]);
  }


  public function trashed()
  {
    $banners = Post::onlyTrashed()->where('post_type', 'banner')->latest()->paginate(10);
    return view('backend.content.banner.trash', compact('banners'));
  }

  public function restore($id)
  {
    Post::onlyTrashed()->findOrFail($id)->restore();
    return redirect()->route('admin.banner.index')->withFlashSuccess('Banner Recovered Successfully');
  }
}
