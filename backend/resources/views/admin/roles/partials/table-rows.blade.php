@forelse($roles as $role)
<tr class="hover:bg-gray-50 transition">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div>
                <div class="text-sm font-medium text-gray-900">{{ $role->display_name }}</div>
                <div class="text-sm text-gray-500">{{ $role->name }}</div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($role->is_system)
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">System</span>
        @else
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Custom</span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-900">{{ $role->users_count }} user(s)</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-900">{{ count($role->permissions ?? []) }} permission(s)</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($role->is_active)
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
        @else
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <a href="{{ route('admin.roles.show', $role) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
        @if($role->name !== 'super_admin' && auth()->user()->hasPermission('roles.edit'))
            <a href="{{ route('admin.roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
        @endif
        @if($role->name !== 'super_admin' && auth()->user()->hasPermission('roles.delete'))
            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
            </form>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <p class="mt-2">No roles found</p>
    </td>
</tr>
@endforelse
