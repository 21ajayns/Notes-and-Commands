<x-layout title="Home">

    <div class="flex items-start justify-between mb-10">
        <div>
            <h2 class="text-3xl font-bold text-zinc-900">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h2>
            <p class="text-sm text-zinc-500 mt-1">
                {{ $folders->count() }} {{ Str::plural('folder', $folders->count()) }} &middot;
                {{ $notes->count() }} {{ Str::plural('note', $notes->count()) }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="document.getElementById('create-folder').showModal()"
                class="bg-white hover:bg-green-50 text-green-700 border border-green-200 text-sm font-semibold px-5 py-3 rounded-xl transition">
                + Folder
            </button>
            <button type="button" onclick="document.getElementById('create-note').showModal()"
                class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-5 py-3 rounded-xl transition shadow-lg shadow-green-600/20">
                + Note
            </button>
        </div>
    </div>

    @if ($folders->isEmpty() && $notes->isEmpty())
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center text-center py-24">
            <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center mb-5">
                <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V4a2 2 0 012-2h7l5 5v12a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-zinc-800 mb-1">Nothing here yet</h3>
            <p class="text-sm text-zinc-500 mb-6">Create your first folder or note to get started.</p>
            <div class="flex items-center gap-3">
                <button type="button" onclick="document.getElementById('create-folder').showModal()"
                    class="bg-white hover:bg-green-50 text-green-700 border border-green-200 text-sm font-semibold px-5 py-3 rounded-xl transition">
                    + Folder
                </button>
                <button type="button" onclick="document.getElementById('create-note').showModal()"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-5 py-3 rounded-xl transition shadow-lg shadow-green-600/20">
                    + Note
                </button>
            </div>
        </div>
    @else

        @if ($folders->isNotEmpty())
            <div class="mb-10">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-4">Folders</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach ($folders as $folder)
                        @php
                            $itemCount = $folder->children->count() + $folder->notes->count();
                        @endphp
                        <div class="group bg-white/80 border border-green-200 rounded-2xl p-5 flex flex-col gap-3 hover:border-green-300 hover:shadow-xl hover:shadow-green-900/5 transition">
                            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition">
                                <svg class="w-5 h-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0v6a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25v-6m-19.5 0h19.5M4.5 9.75V6.75A2.25 2.25 0 016.75 4.5h3.879a1.5 1.5 0 011.06.44l1.122 1.12a1.5 1.5 0 001.06.44H17.25a2.25 2.25 0 012.25 2.25v.75" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-900 truncate">{{ $folder->name }}</p>
                                <p class="text-xs text-zinc-400 mt-0.5">
                                    {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($notes->isNotEmpty())
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-4">Notes</h3>
                <div class="grid grid-cols-2 gap-6">
                    @foreach ($notes as $note)
                        <div class="bg-white/80 border border-green-200 rounded-2xl p-6 min-h-[140px] flex flex-col gap-3 hover:border-green-300 hover:shadow-xl hover:shadow-green-900/5 transition">
                            <div class="flex items-start justify-between gap-3">
                                <h4 class="text-lg font-semibold text-zinc-900">{{ $note->title }}</h4>
                                <span class="shrink-0 text-xs text-zinc-400">{{ $note->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-zinc-500 leading-relaxed line-clamp-3">{{ $note->body }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @endif

    {{-- Create folder modal --}}
    <dialog id="create-folder" class="m-auto bg-white text-zinc-800 rounded-2xl p-0 w-full max-w-sm backdrop:bg-black/40">
        <form action="{{ route('folders.store') }}" method="POST" class="p-8">
            @csrf
            <h3 class="text-xl font-bold text-zinc-900 mb-6">New folder</h3>

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Name</label>
            <input type="text" name="name" placeholder="Folder name" value="{{ old('name') }}"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition mb-1">
            @error('name')
                <p class="text-xs text-red-600 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2 mt-5">Inside (optional)</label>
            <select name="parent_id"
                class="w-auto bg-zinc-100 text-zinc-800 text-xs rounded-lg px-3 py-1.5 outline-none ring-1 ring-transparent focus:ring-green-500 transition mb-7">
                <option value="">Top level</option>
                @foreach ($allFolders as $f)
                    <option value="{{ $f->id }}" @selected((int) old('parent_id') === $f->id)>{{ $f->name }}</option>
                @endforeach
            </select>

            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('create-folder').close()"
                    class="text-zinc-500 hover:text-zinc-900 text-sm font-medium px-5 py-3 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-green-600/20">
                    Save folder
                </button>
            </div>
        </form>
    </dialog>

    {{-- Create note modal --}}
    <dialog id="create-note" class="m-auto bg-white text-zinc-800 rounded-2xl p-0 w-full max-w-lg backdrop:bg-black/40">
        <form action="{{ route('notes.store') }}" method="POST" class="p-8">
            @csrf
            <h3 class="text-xl font-bold text-zinc-900 mb-6">New note</h3>

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Title</label>
            <input type="text" name="title" placeholder="Give it a name" value="{{ old('title') }}"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition mb-1">
            @error('title')
                <p class="text-xs text-red-600 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2 mt-5">Body</label>
            <textarea name="body" placeholder="Write something..." rows="8"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition resize-none mb-1">{{ old('body') }}</textarea>
            @error('body')
                <p class="text-xs text-red-600 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2 mt-5">Folder (optional)</label>
            <select name="parent_id"
                class="w-auto bg-zinc-100 text-zinc-800 text-xs rounded-lg px-3 py-1.5 outline-none ring-1 ring-transparent focus:ring-green-500 transition mb-7">
                <option value="">No folder</option>
                @foreach ($allFolders as $f)
                    <option value="{{ $f->id }}" @selected((int) old('parent_id') === $f->id)>{{ $f->name }}</option>
                @endforeach
            </select>

            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('create-note').close()"
                    class="text-zinc-500 hover:text-zinc-900 text-sm font-medium px-5 py-3 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-green-600/20">
                    Save note
                </button>
            </div>
        </form>
    </dialog>

    @if ($errors->any())
        <script>
            document.getElementById('{{ $errors->has('name') ? 'create-folder' : 'create-note' }}').showModal();
        </script>
    @endif

</x-layout>
