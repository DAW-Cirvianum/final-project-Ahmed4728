<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Usuari</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="/admin/users/{{ $user->id }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                        <input name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full" />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full" />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Rol</label>
                        <select name="role" class="mt-1 block w-full">
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Contrasenya (deixar buit per no canviar)</label>
                        <input name="password" type="password" class="mt-1 block w-full" />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Confirmar Contrasenya</label>
                        <input name="password_confirmation" type="password" class="mt-1 block w-full" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="/admin/users" class="mr-4 text-gray-600">Cancel·lar</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
