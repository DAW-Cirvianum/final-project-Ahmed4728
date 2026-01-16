<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuaris</h2>
	</x-slot>

	<div class="py-6">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white shadow-sm sm:rounded-lg p-4">
				<table class="min-w-full divide-y divide-gray-200">
					<thead>
						<tr>
							<th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
							<th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
							<th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
							<th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                            
							<th class="px-6 py-2"></th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						@foreach($users as $user)
						<tr>
							<td class="px-6 py-2">{{ $user->id }}</td>
							<td class="px-6 py-2">{{ $user->name }}</td>
							<td class="px-6 py-2">{{ $user->email }}</td>
							<td class="px-6 py-2">{{ $user->role }}</td>
							<td class="px-6 py-2 text-right">
								<a href="/admin/users/{{ $user->id }}/edit" class="text-blue-600 mr-2">Editar</a>

								<form action="/admin/users/{{ $user->id }}" method="POST" style="display:inline" onsubmit="return confirm('Eliminar usuari?')">
									@csrf
									@method('DELETE')
									<button type="submit" class="text-red-600">Eliminar</button>
								</form>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</x-app-layout>
