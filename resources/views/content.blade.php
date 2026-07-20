<x-layout title="Home">

    <div class="mb-6">
        @if ($currentFolder)
            <a href="{{ route('content.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-zinc-400 hover:text-white transition mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Home
            </a>
            <h2 class="text-3xl font-bold text-white">{{ $currentFolder->name }}</h2>
        @else
            <h2 class="text-3xl font-bold text-white">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h2>
        @endif
        <p class="text-sm text-zinc-400 mt-1">
            {{ $folders->count() }} {{ Str::plural('folder', $folders->count()) }} &middot;
            {{ $notes->count() }} {{ Str::plural('note', $notes->count()) }}
        </p>
    </div>

    <div class="flex items-center gap-2 mb-10">
        <button type="button" onclick="document.getElementById('create-folder').showModal()" title="New folder"
            class="relative w-11 h-11 flex items-center justify-center bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0v6a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25v-6m-19.5 0h19.5M4.5 9.75V6.75A2.25 2.25 0 016.75 4.5h3.879a1.5 1.5 0 011.06.44l1.122 1.12a1.5 1.5 0 001.06.44H17.25a2.25 2.25 0 012.25 2.25v.75" />
            </svg>
            <span class="absolute -bottom-1 -right-1 w-4 h-4 flex items-center justify-center rounded-full bg-emerald-500 text-black text-[10px] font-bold leading-none">+</span>
        </button>
        <button type="button" onclick="document.getElementById('create-note').showModal()"
            class="bg-violet-500/10 hover:bg-violet-500/20 text-violet-300 border border-violet-500/20 text-sm font-semibold px-5 py-3 rounded-xl transition">
            + Note
        </button>
    </div>

    @if ($folders->isEmpty() && $notes->isEmpty())
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center text-center py-24">
            <div class="w-16 h-16 rounded-2xl bg-violet-500/10 flex items-center justify-center mb-5">
                <svg class="w-8 h-8 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V4a2 2 0 012-2h7l5 5v12a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-1">Nothing here yet</h3>
            <p class="text-sm text-zinc-400 mb-6">Create your first folder or note to get started.</p>
            <div class="flex items-center gap-3">
                <button type="button" onclick="document.getElementById('create-folder').showModal()"
                    class="inline-flex items-center gap-2 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 text-sm font-semibold px-5 py-3 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0v6a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25v-6m-19.5 0h19.5M4.5 9.75V6.75A2.25 2.25 0 016.75 4.5h3.879a1.5 1.5 0 011.06.44l1.122 1.12a1.5 1.5 0 001.06.44H17.25a2.25 2.25 0 012.25 2.25v.75" />
                    </svg>
                    + Folder
                </button>
                <button type="button" onclick="document.getElementById('create-note').showModal()"
                    class="bg-violet-500/10 hover:bg-violet-500/20 text-violet-300 border border-violet-500/20 text-sm font-semibold px-5 py-3 rounded-xl transition">
                    + Note
                </button>
            </div>
        </div>
    @else

        @if ($folders->isNotEmpty())
            <div class="mb-10">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-4">Folders</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                    @foreach ($folders as $folder)
                        @php
                            $itemCount = $folder->children->count() + $folder->notes->count();
                        @endphp
                        <a href="{{ route('content.index', ['folder_id' => $folder->id]) }}"
                            class="group bg-emerald-500/[0.04] border border-emerald-500/20 rounded-xl p-3 flex items-center gap-3 hover:border-emerald-500/40 hover:shadow-xl hover:shadow-black/20 transition">
                            <div class="w-8 h-8 shrink-0 rounded-lg bg-emerald-500/15 flex items-center justify-center group-hover:bg-emerald-500/25 transition">
                                <svg class="w-4 h-4 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0v6a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25v-6m-19.5 0h19.5M4.5 9.75V6.75A2.25 2.25 0 016.75 4.5h3.879a1.5 1.5 0 011.06.44l1.122 1.12a1.5 1.5 0 001.06.44H17.25a2.25 2.25 0 012.25 2.25v.75" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ $folder->name }}</p>
                                <p class="text-xs text-zinc-400">
                                    {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($notes->isNotEmpty())
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-4">Notes</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($notes as $note)
                        <div class="bg-violet-500/[0.04] border border-violet-500/20 rounded-xl p-4 flex flex-col gap-1.5 hover:border-violet-500/40 hover:shadow-xl hover:shadow-black/20 transition">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="text-sm font-semibold text-white truncate">{{ $note->title }}</h4>
                                <span class="shrink-0 text-[11px] text-zinc-500">{{ $note->created_at->diffForHumans(null, null, true) }}</span>
                            </div>
                            <p class="text-xs text-zinc-400 leading-relaxed line-clamp-2">{{ $note->body }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @endif

    {{-- Create folder modal --}}
    <dialog id="create-folder" class="m-auto bg-zinc-900 text-zinc-200 rounded-2xl p-0 w-full max-w-sm backdrop:bg-black/70">
        <form action="{{ route('folders.store') }}" method="POST" class="p-8">
            @csrf
            <h3 class="text-xl font-bold text-white mb-6">New folder</h3>

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Name</label>
            <input type="text" name="name" placeholder="Folder name" value="{{ old('name') }}"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-emerald-500 transition mb-1">
            @error('name')
                <p class="text-xs text-red-400 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2 mt-5">Inside (optional)</label>
            <select name="parent_id"
                class="w-auto bg-zinc-800 text-zinc-200 text-xs rounded-lg px-3 py-1.5 outline-none ring-1 ring-transparent focus:ring-emerald-500 transition mb-7">
                <option value="">Top level</option>
                @foreach ($allFolders as $f)
                    <option value="{{ $f->id }}" @selected((int) old('parent_id', $currentFolder?->id) === $f->id)>{{ $f->name }}</option>
                @endforeach
            </select>

            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('create-folder').close()"
                    class="text-zinc-400 hover:text-white text-sm font-medium px-5 py-3 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-emerald-600/20">
                    Save folder
                </button>
            </div>
        </form>
    </dialog>

    {{-- Create note modal --}}
    <dialog id="create-note" class="m-auto bg-zinc-900 text-zinc-200 rounded-2xl p-0 w-full max-w-lg backdrop:bg-black/70">
        <form action="{{ route('notes.store') }}" method="POST" class="p-8">
            @csrf
            <h3 class="text-xl font-bold text-white mb-6">New note</h3>

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Title</label>
            <input type="text" name="title" placeholder="Give it a name" value="{{ old('title') }}"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-violet-500 transition mb-1">
            @error('title')
                <p class="text-xs text-red-400 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2 mt-5">Body</label>
            <textarea name="body" placeholder="Write something..." rows="8"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-zinc-200 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-violet-500 transition resize-none mb-1">{{ old('body') }}</textarea>
            @error('body')
                <p class="text-xs text-red-400 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2 mt-5">Folder (optional)</label>
            <select name="parent_id"
                class="w-auto bg-zinc-800 text-zinc-200 text-xs rounded-lg px-3 py-1.5 outline-none ring-1 ring-transparent focus:ring-violet-500 transition mb-7">
                <option value="">No folder</option>
                @foreach ($allFolders as $f)
                    <option value="{{ $f->id }}" @selected((int) old('parent_id', $currentFolder?->id) === $f->id)>{{ $f->name }}</option>
                @endforeach
            </select>

            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('create-note').close()"
                    class="text-zinc-400 hover:text-white text-sm font-medium px-5 py-3 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-violet-600/20">
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
