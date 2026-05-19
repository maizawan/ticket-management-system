<x-app-layout>
    <x-slot name="header">
        <h2>Edit Ticket</h2>
    </x-slot>

    <div class="p-6">

        <form method="POST" action="/tickets/{{ $ticket->id }}">
            @csrf
            @method('PUT')

            <input type="text" name="title"
                   value="{{ $ticket->title }}"
                   class="w-full border p-2 mb-3">

            <textarea name="description"
                      class="w-full border p-2 mb-3">
                {{ $ticket->description }}
            </textarea>

            <select name="priority" class="w-full border p-2">
                <option value="low" {{ $ticket->priority=='low'?'selected':'' }}>Low</option>
                <option value="medium" {{ $ticket->priority=='medium'?'selected':'' }}>Medium</option>
                <option value="high" {{ $ticket->priority=='high'?'selected':'' }}>High</option>
            </select>

            <button class="bg-green-600 text-white px-4 py-2 mt-3">
                Update
            </button>

        </form>

    </div>
</x-app-layout>