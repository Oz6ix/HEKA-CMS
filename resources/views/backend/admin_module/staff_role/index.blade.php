@extends('backend.layouts.modern')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Staff Roles</h1>
            <p class="mt-1 text-sm text-slate-500">Manage Staff Roles records.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-3">
            <a href="{{ url($url_prefix . '/staff_role/create') }}"
               class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Role
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="py-3 pl-6 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 w-12">#</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Role</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @if(isset($items) && sizeof($items) > 0)
                        @foreach($items as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 pl-6 pr-3 text-sm text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-3 py-3 text-sm font-medium text-slate-900">{{ $item->role }}</td>
                            <td class="px-3 py-3">
                                @if($item->status == 1)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Active</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700">Inactive</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 pr-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($item->status == 1)
                                        <a href="javascript:;" onclick="change_status('{{ url($url_prefix . '/staff_role/deactivate/'.$item->id) }}')" class="text-slate-400 hover:text-amber-500 transition-colors" title="Deactivate">
                                            <i class="fas fa-toggle-on text-emerald-500"></i>
                                        </a>
                                    @else
                                        <a href="javascript:;" onclick="change_status('{{ url($url_prefix . '/staff_role/activate/'.$item->id) }}')" class="text-slate-400 hover:text-emerald-500 transition-colors" title="Activate">
                                            <i class="fas fa-toggle-off"></i>
                                        </a>
                                    @endif
                                    <a href="{{ url($url_prefix . '/staff_role/edit/'.$item->id) }}" class="text-slate-400 hover:text-primary-600 transition-colors" title="Edit">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <a href="javascript:;" onclick="delete_record('{{ url($url_prefix . '/staff_role/delete/'.$item->id) }}')" class="text-slate-400 hover:text-red-500 transition-colors" title="Delete">
                                        <i class="fas fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="text-slate-400">
                                    <i class="fas fa-user-shield text-3xl mb-3"></i>
                                    <p class="text-sm font-medium">No records found</p>
                                    <p class="text-xs mt-1">Add your first Role to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
