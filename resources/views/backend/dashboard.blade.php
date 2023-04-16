@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</strong>
                </div> <!-- card-header-->
                <div class="card-body">
                    {{-- FOR MAIN DOMAINS --}}
                    <h5>SubAPI Order Tracking</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Domain</th>
                                    <th class="text-center">Total Invoices</th>
                                    <th class="text-center">Total Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subApis as $subApi)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ $subApi->domain }}">{{ $subApi->domain }}</a>
                                        </td>
                                        <td class="text-center align-middle">{{ $subApi->total_invoices }}</td>
                                        <td class="text-center align-middle">{{ $subApi->total_orders }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center align-middle" colspan="4">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- FOR MAIN DOMAINS --}}
                </div> <!-- card-body-->
            </div> <!-- card-->
        </div> <!-- col-->
    </div> <!-- row-->
@endsection
