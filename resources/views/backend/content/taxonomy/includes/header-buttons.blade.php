
    <a href="{{ route('admin.taxonomy.create') }}" class="btn btn-tool text-primary" data-toggle="tooltip"
       title="@lang('labels.general.create_new')">
        <i class="fa fa-plus-circle"></i> Create New
    </a>
    @hasrole('administrator')
    <a href="{{ route('admin.taxonomy.trashed') }}" class="btn btn-tool text-danger" data-toggle="tooltip"
       title="View Trashed"><i class="fa fa-trash-o"></i> View Trashed</a>
    @endhasrole
