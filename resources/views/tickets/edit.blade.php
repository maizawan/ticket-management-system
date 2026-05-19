<x-app-layout>

    <x-slot name="header">
        <h2>Edit Ticket</h2>
    </x-slot>

    <div class="p-6">

       
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
            @csrf
            @method('PUT')

         
            <input type="text"
                   name="title"
                   value="{{ old('title', $ticket->title) }}"
                   class="w-full border p-2 mb-3 rounded"
                   placeholder="Enter title">

            
            <textarea name="description"
                      class="w-full border p-2 mb-3 rounded"
                      placeholder="Enter description">{{ old('description', $ticket->description) }}</textarea>

           
            <select name="priority" class="w-full border p-2 mb-3 rounded">

                <option value="low"
                    {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>
                    Low
                </option>

                <option value="medium"
                    {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>
                    Medium
                </option>

                <option value="high"
                    {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>
                    High
                </option>

            </select>

           
            <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Update Ticket
            </button>

        </form>

    </div>

</x-app-layout>