@inject('taxonomy', 'App\Models\Content\Taxonomy')
@php
  $catLoader = get_setting('category_image_loader');
  $categories = $taxonomy->whereNull('ParentId')->whereNotNull('active')->get();
@endphp


<div class="categories_wrap">
  <button type="button" data-toggle="collapse" data-target="#navCatContent" aria-expanded="false" class="categories_btn categories_menu">
    <span>All Categories </span><i class="linearicons-menu"></i>
  </button>
  <div id="navCatContent" class="navbar collapse">
    <ul>
      <li class="d-lg-none">
        <div class="clearfix">
          <button type="button" class="btn float-right categorySidebarClose"><i class="fas fa-times"></i></button>
        </div>
      </li>
      @foreach ($categories->take(10) as $category)
        <li class="dropdown dropdown-mega-menu">
          <a class="dropdown-item nav-link dropdown-toggler" href="{{url($category->slug)}}" data-toggle="dropdown">
            @if($category->picture)
              <img class="b2bLoading" data-src="{{asset($category->picture)}}" src="{{asset($catLoader)}}"
                   style="width: 35px;margin-right: 10px;">
            @else
              <i class="flaticon-tv"></i>
            @endif
            <span>{{$category->name}}</span>
          </a>
          <div class="dropdown-menu">
            <ul class="mega-menu d-lg-flex">
              <li class="mega-menu-col col-lg-4">
                <ul class="d-lg-flex">
                  <li class="mega-menu-col col-lg-6">
                    <ul>
                      <li class="dropdown-header">{{$category->name}} Item</li>
                      @foreach ($category->children as $children)
                        <li>
                          <a class="dropdown-item nav-link nav_item"
                             href="{{url($category->slug.'/'.$children->slug)}}">{{$children->name}}</a>
                        </li>
                      @endforeach
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </li>
      @endforeach
    </ul>
  </div>
</div> <!-- categories_wrap -->
