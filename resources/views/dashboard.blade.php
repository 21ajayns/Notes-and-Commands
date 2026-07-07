<x-layout title="Notes">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-sm font-semibold text-white">Notes</h2>
        <button type="button" onclick="document.getElementById('create-note').showModal()"
            class="bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-sm font-medium px-4 py-2 rounded-lg transition">
            Create
        </button>
    </div>

    {{-- Notes grid --}}
    <div class="grid grid-cols-2 gap-4">
        @foreach($notes as $note)
            <div class="bg-zinc-800/80 rounded-xl p-5 min-h-[72px]">
                <h3 class="text-sm font-semibold text-white mb-1">{{ $note->title }}</h3>
                <p class="text-xs text-zinc-400 leading-relaxed line-clamp-2">{{ $note->body }}</p>
            </div>
        @endforeach

        @for($i = $notes->count(); $i < max(8, $notes->count()); $i++)
            <div class="bg-zinc-800/80 rounded-xl min-h-[72px]"></div>
        @endfor
    </div>

    {{-- Create note modal --}}
    <dialog id="create-note" class="bg-zinc-900 text-zinc-200 rounded-xl p-0 w-full max-w-md backdrop:bg-black/60">
        <form action="{{ route('notes.store') }}" method="POST" class="p-6">
            @csrf
            <h3 class="text-sm font-semibold text-white mb-4">New note</h3>

            <input type="text" name="title" placeholder="Title" required
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-sm rounded-lg px-3 py-2 outline-none mb-3">

            <textarea name="body" placeholder="Write something..." rows="4"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-zinc-200 text-sm rounded-lg px-3 py-2 outline-none resize-none mb-3"></textarea>

            <select name="category"
                class="w-full bg-zinc-800 text-zinc-200 text-sm rounded-lg px-3 py-2 outline-none mb-5">
                <option value="personal">Personal</option>
                <option value="work">Work</option>
                <option value="entertainment">Entertainment</option>
            </select>

            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('create-note').close()"
                    class="text-zinc-400 hover:text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                    Save note
                </button>
            </div>
        </form>
    </dialog>

</x-layout>
