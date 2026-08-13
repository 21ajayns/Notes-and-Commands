<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

<div
    x-data="notesApp()"
    x-init="init()"
    class="flex h-screen overflow-hidden"
>
    <!-- Sidebar -->
    <aside class="w-64 shrink-0 border-r border-gray-200 bg-white flex flex-col">
        <div class="px-5 py-4 border-b border-gray-200">
            <h1 class="text-lg font-semibold">Notes</h1>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-1">
            <button
                @click="goRoot()"
                class="w-full flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-100"
                :class="currentFolderId === null ? 'bg-gray-100 text-gray-900' : 'text-gray-600'"
            >
                <span>🏠</span>
                <span>All Notes</span>
            </button>

            <div class="pt-3 pb-1 px-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
                Folders
            </div>

            <template x-for="folder in folders" :key="folder.id">
                <button
                    @click="openFolder(folder)"
                    class="w-full flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-100 text-gray-600 group"
                >
                    <span>📁</span>
                    <span class="truncate flex-1 text-left" x-text="folder.name"></span>
                    <span
                        class="text-[10px] px-1.5 py-0.5 rounded-full font-semibold"
                        :class="folder.category === 'office' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'"
                        x-text="folder.category"
                    ></span>
                </button>
            </template>

            <p x-show="!loading && folders.length === 0" class="px-3 text-sm text-gray-400">
                No subfolders here.
            </p>
        </nav>

        <div class="p-3 border-t border-gray-200">
            <button
                @click="openFolderModal()"
                class="w-full rounded-lg bg-gray-900 text-white text-sm font-medium py-2 hover:bg-gray-700 transition"
            >
                + New Folder
            </button>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="border-b border-gray-200 bg-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-1 text-sm text-gray-500 min-w-0">
                <button @click="goRoot()" class="hover:text-gray-900 font-medium shrink-0">All Notes</button>
                <template x-for="(crumb, index) in breadcrumbs" :key="crumb.id">
                    <div class="flex items-center gap-1 min-w-0">
                        <span class="shrink-0">/</span>
                        <button
                            @click="goToBreadcrumb(index)"
                            class="hover:text-gray-900 truncate"
                            x-text="crumb.name"
                        ></button>
                    </div>
                </template>
            </div>

            <button
                @click="openNoteModal()"
                class="shrink-0 rounded-lg bg-blue-600 text-white text-sm font-medium px-4 py-2 hover:bg-blue-700 transition"
            >
                + New Note
            </button>
        </header>

        <div class="flex-1 overflow-y-auto p-6">
            <p x-show="loading" class="text-sm text-gray-400">Loading…</p>

            <div
                x-show="!loading"
                class="grid gap-4"
                style="grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));"
            >
                <template x-for="note in notes" :key="note.id">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition flex flex-col gap-2">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-semibold text-sm leading-snug" x-text="note.title"></h3>
                            <span
                                class="shrink-0 text-[10px] px-1.5 py-0.5 rounded-full font-semibold"
                                :class="note.category === 'office' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'"
                                x-text="note.category"
                            ></span>
                        </div>
                        <p class="text-sm text-gray-500 line-clamp-4 whitespace-pre-line" x-text="note.content"></p>
                    </div>
                </template>
            </div>

            <p x-show="!loading && notes.length === 0 && folders.length === 0" class="text-sm text-gray-400">
                Nothing here yet. Create a note or a folder to get started.
            </p>
        </div>
    </main>

    <!-- New Note Modal -->
    <div
        x-show="showNoteModal"
        x-cloak
        class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50"
        style="display: none;"
    >
        <div @click.outside="showNoteModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-lg font-semibold mb-4">New Note</h2>

            <form @submit.prevent="submitNote()" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Title</label>
                    <input
                        type="text"
                        x-model="noteForm.title"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <p class="text-xs text-red-600 mt-1" x-show="errors.title" x-text="errors.title?.[0]"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Content</label>
                    <textarea
                        x-model="noteForm.content"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                    <p class="text-xs text-red-600 mt-1" x-show="errors.content" x-text="errors.content?.[0]"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Category</label>
                    <select
                        x-model="noteForm.category"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="office">Office</option>
                        <option value="personal">Personal</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showNoteModal = false" class="text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="text-sm font-medium px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- New Folder Modal -->
    <div
        x-show="showFolderModal"
        x-cloak
        class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50"
        style="display: none;"
    >
        <div @click.outside="showFolderModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h2 class="text-lg font-semibold mb-4">New Folder</h2>

            <form @submit.prevent="submitFolder()" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input
                        type="text"
                        x-model="folderForm.name"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <p class="text-xs text-red-600 mt-1" x-show="errors.name" x-text="errors.name?.[0]"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Category</label>
                    <select
                        x-model="folderForm.category"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="office">Office</option>
                        <option value="personal">Personal</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showFolderModal = false" class="text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="text-sm font-medium px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-700">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function notesApp() {
        return {
            currentFolderId: null,
            breadcrumbs: [],
            folders: [],
            notes: [],
            loading: false,
            showNoteModal: false,
            showFolderModal: false,
            noteForm: { title: '', content: '', category: 'office' },
            folderForm: { name: '', category: 'office' },
            errors: {},

            init() {
                this.loadContents();
            },

            async loadContents() {
                this.loading = true;

                const query = this.currentFolderId ? `?folder_id=${this.currentFolderId}` : '';

                const [foldersRes, notesRes] = await Promise.all([
                    fetch(`/api/folders${query}`),
                    fetch(`/api/notes${query}`),
                ]);

                this.folders = await foldersRes.json();
                this.notes = await notesRes.json();
                this.loading = false;
            },

            openFolder(folder) {
                this.breadcrumbs.push({ id: folder.id, name: folder.name });
                this.currentFolderId = folder.id;
                this.loadContents();
            },

            goToBreadcrumb(index) {
                this.currentFolderId = this.breadcrumbs[index].id;
                this.breadcrumbs = this.breadcrumbs.slice(0, index + 1);
                this.loadContents();
            },

            goRoot() {
                this.currentFolderId = null;
                this.breadcrumbs = [];
                this.loadContents();
            },

            openNoteModal() {
                this.noteForm = { title: '', content: '', category: 'office' };
                this.errors = {};
                this.showNoteModal = true;
            },

            openFolderModal() {
                this.folderForm = { name: '', category: 'office' };
                this.errors = {};
                this.showFolderModal = true;
            },

            async submitNote() {
                this.errors = {};

                const response = await fetch('/api/notes', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ...this.noteForm, folder_id: this.currentFolderId }),
                });

                if (response.status === 422) {
                    const body = await response.json();
                    this.errors = body.errors ?? {};
                    return;
                }

                const note = await response.json();
                this.notes.push(note);
                this.showNoteModal = false;
            },

            async submitFolder() {
                this.errors = {};

                const response = await fetch('/api/folders', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ...this.folderForm, folder_id: this.currentFolderId }),
                });

                if (response.status === 422) {
                    const body = await response.json();
                    this.errors = body.errors ?? {};
                    return;
                }

                const folder = await response.json();
                this.folders.push(folder);
                this.showFolderModal = false;
            },
        };
    }
</script>

</body>
</html>