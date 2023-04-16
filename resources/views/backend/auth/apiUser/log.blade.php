@extends('backend.layouts.app')

@section('title', app_name() . ' |  User Api Log')


@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ __('User API Log') }}
                </h4>
            </div><!--col-->

        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>API-Type</th>
                            <th>API-Function</th>
                            <th>API-Call Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $key => $log)
                            <tr>
                                <td>{{ count($logs) - $key }}</td>
                                <td>{{ $log->type }}</td>
                                <td>{{ $log->function_name }}</td>
                                <td>{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    {!! $logs->total() !!} {{ trans_choice('Total Logs', $logs->total()) }}
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $logs->render() !!}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
