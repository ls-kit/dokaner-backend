@extends('backend.layouts.app')

@section('title', ' General Settings ')

@php
    $required = html()
        ->span('*')
        ->class('text-danger');
    $demoImg = 'img/backend/front-logo.png';
@endphp

@inject('storage', 'Illuminate\Support\Facades\Storage')

@section('content')


    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title mt-2">Browsing Caching Control </h3>
                    <form action="{{ route('admin.setting.cache.control.all.store') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger float-right">Clear All</button>
                    </form>
                </div>
                <div class="mt-2 ml-4">
                    {{ html()->form('POST', route('admin.front-setting.manage.cache.setting'))->open() }}
                    {{html()->button('Update')->class('btn btn-sm btn-success float-right mr-4 mt-2')}}
                    <h6>Auto Cache Cleaning (Daily)</h6>
                    <div class="form-check form-check-inline">
                        {{ html()->radio('cache_auto_clean_active', get_setting('cache_auto_clean_active') === 'enable', 'enable')->id('cache_auto_clean_enable')->class('form-check-input') }}
                        {{ html()->label('Enable')->class('form-check-label')->for('cache_auto_clean_enable') }}
                    </div>
                    <div class="form-check form-check-inline">
                        {{ html()->radio('cache_auto_clean_active', get_setting('cache_auto_clean_active') === 'disable', 'disable')->id('cache_auto_clean_diable')->class('form-check-input') }}
                        {{ html()->label('Disable')->class('form-check-label')->for('cache_auto_clean_diable') }}
                    </div>
                    {{ html()->form()->close() }}
                </div>
                <div class="card-body table-responsive-sm">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th class="text-center">Date</th>
                                <th>Keyword</th>
                                <th class="text-center">Size</th>
                                <th class="text-center">Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($files as $file)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ date('d-m-Y', $file->getATime()) }}</td>
                                    <td>{{ $file->getFilename() }}</td>
                                    <td class="text-center">{{ round($file->getSize() / 1024) }} kb</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.setting.cache.control.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="pathname" value="{{ $file->getPathName() }}">
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center align-middle text-danger">Browsing file not found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> <!--  .card-body -->
            </div> <!--  .card -->
        </div> <!-- .col-md-9 -->
    </div> <!-- .row -->

@endsection
