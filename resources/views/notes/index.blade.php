<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ink-950 text-ink-100 antialiased font-sans text-sm">

<div
    x-data="notesApp()"
    x-init="init()"
    class="flex h-screen overflow-hidden"
>
    <!-- Icon rail -->
    <div class="w-16 shrink-0 bg-ink-950 border-r border-white/[0.06] flex flex-col items-center py-4 gap-3">
        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-xs font-bold text-white shadow-card">
            N
        </div>

        <div class="w-8 border-t border-white/[0.06]"></div>

        <button
            class="w-10 h-10 rounded-xl flex items-center justify-center bg-white/[0.08] text-white transition-all"
            title="Notes"
        >
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 3a1 1 0 011-1h6l4 4v11a1 1 0 01-1 1H5a1 1 0 01-1-1V3z"/><path d="M11 2v4h4"/></svg>
        </button>

        <button
            class="w-10 h-10 rounded-xl flex items-center justify-center text-ink-500 opacity-40 cursor-not-allowed transition-all"
            title="Todo — coming soon"
            disabled
        >
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="14" height="14" rx="3"/><path d="M7 10l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </div>

    <!-- Sidebar -->
    <aside class="w-72 shrink-0 bg-ink-900 border-r border-white/[0.06] flex flex-col">
        <div class="px-4 pt-4">
            <button
                type="button"
                @click="toggleCategory()"
                class="flip-card w-full h-12 block"
            >
                <div class="flip-card-inner" :class="activeCategory === 'personal' ? 'flip-card-flipped' : ''">
                    <div class="flip-face flip-face-front bg-gradient-to-br from-blue-500 to-indigo-600 shadow-card">
                        <span class="text-base">💼</span> Work
                    </div>
                    <div class="flip-face flip-face-back bg-gradient-to-br from-emerald-500 to-teal-600 shadow-card">
                        <span class="text-base">🏠</span> Personal
                    </div>
                </div>
            </button>
        </div>

        <div class="px-4 pt-3">
            <div class="relative">
                <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 -translate-y-1/2 text-ink-500">
                    <circle cx="9" cy="9" r="6.5"/><path d="M18 18l-4-4" stroke-linecap="round"/>
                </svg>
                <input
                    type="text"
                    x-model="search"
                    placeholder="Search folders…"
                    class="input-field pl-9"
                >
            </div>
        </div>

        <div class="mx-4 mt-3 border-t border-white/[0.06]"></div>

        <nav class="flex-1 overflow-y-auto py-3">
            <button
                @click="selectFolder(null)"
                class="sidebar-item mx-3 px-2.5 py-2"
                style="width: calc(100% - 24px)"
                :class="selectedFolderId === null ? 'sidebar-item-active' : ''"
            >
                <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor" class="text-ink-500 shrink-0"><path d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0013.414 6L10 2.586A2 2 0 008.586 2H4z"/></svg>
                All Notes
            </button>

            <div class="pt-4 pb-1.5 px-5 text-[10px] font-semibold uppercase tracking-widest text-ink-500">
                Stacks
            </div>

            <div x-html="renderTree(visibleTree())"></div>

            <p x-show="folderTree.length === 0" class="px-5 text-sm text-ink-500">
                No folders yet.
            </p>
        </nav>
    </aside>

    <!-- Main -->
    <main class="flex-1 flex flex-col overflow-hidden bg-ink-950">
        <header class="border-b border-white/[0.06] px-8 py-5 flex items-center gap-4">
            <div class="flex items-center gap-2.5 shrink-0" x-show="!selectedNote" x-cloak>
                <button
                    @click="startNewNote()"
                    title="New note"
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-white shadow-[0_1px_0_0_rgba(255,255,255,0.16)_inset,0_1px_3px_rgba(0,0,0,0.5)] transition-all duration-150 active:scale-[0.95]"
                    :class="activeCategory === 'office' ? 'bg-gradient-to-b from-indigo-500 to-indigo-600 hover:from-indigo-400 hover:to-indigo-500' : 'bg-gradient-to-b from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500'"
                >
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 3a1 1 0 00-1 1v16a1 1 0 001 1h12a1 1 0 001-1V8l-5-5H6z"/>
                        <path d="M13 3v5h5"/>
                        <path d="M9.5 14h5M12 11.5v5"/>
                    </svg>
                </button>
                <button
                    @click="openFolderModal()"
                    title="New folder"
                    class="w-11 h-11 rounded-xl flex items-center justify-center bg-white/[0.04] border border-amber-500/25 text-amber-400 hover:bg-amber-500/10 hover:border-amber-500/40 transition-all duration-150 active:scale-[0.95]"
                >
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                        <path d="M12 11v4M10 13h4"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-1.5 text-sm text-ink-500 min-w-0">
                <button @click="selectFolder(null)" class="hover:text-white transition-colors font-medium shrink-0">All Notes</button>
                <template x-for="(crumb, index) in breadcrumbs" :key="crumb.id">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="shrink-0 text-ink-600">/</span>
                        <button
                            @click="selectFolder(crumb.id)"
                            class="hover:text-white transition-colors truncate"
                            x-text="crumb.name"
                        ></button>
                    </div>
                </template>
                <template x-if="selectedNote">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="shrink-0 text-ink-600">/</span>
                        <span class="text-white truncate font-medium" x-text="selectedNote.title || 'New Note'"></span>
                    </div>
                </template>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <template x-if="!selectedNote">
                <div>
                    <p x-show="loading" class="text-sm text-ink-500">Loading…</p>

                    <div
                        x-show="!loading"
                        class="grid gap-4"
                        style="grid-template-columns: repeat(3, 1fr);"
                    >
                        <template x-for="folder in subfolders" :key="folder.id">
                            <div
                                @click="selectFolder(folder.id)"
                                class="rounded-2xl border-2 border-dashed border-white/15 hover:border-amber-500/40 hover:bg-amber-500/[0.04] p-5 flex flex-col gap-2.5 transition-all duration-150 relative group cursor-pointer"
                            >
                                <div class="absolute top-3 right-3 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-150">
                                    <button
                                        @click.stop="openRenameFolderModal(folder)"
                                        title="Rename folder"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-ink-500 hover:text-amber-400 hover:bg-amber-500/10"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                    </button>
                                    <button
                                        @click.stop="deleteFolder(folder)"
                                        title="Delete folder"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-ink-500 hover:text-red-400 hover:bg-red-500/10"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                        </svg>
                                    </button>
                                </div>
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" class="text-amber-400/80"><path d="M2 6a2 2 0 012-2h4.5l1.5 2H16a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                <h3 class="font-semibold text-sm leading-snug text-white tracking-tight pr-12" x-text="folder.name"></h3>
                            </div>
                        </template>

                        <template x-for="note in notes" :key="note.id">
                            <div
                                @click="viewNote(note)"
                                class="card p-5 flex flex-col gap-2.5 border-l-[3px] hover:-translate-y-0.5 hover:shadow-popover relative group cursor-pointer"
                                :class="activeCategory === 'office' ? 'border-l-blue-500/60' : 'border-l-emerald-500/60'"
                            >
                                <button
                                    @click.stop="deleteNote(note)"
                                    title="Delete note"
                                    class="absolute top-3 right-3 w-7 h-7 rounded-lg flex items-center justify-center text-ink-500 opacity-0 group-hover:opacity-100 hover:text-red-400 hover:bg-red-500/10 transition-all duration-150"
                                >
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                    </svg>
                                </button>
                                <h3 class="font-semibold text-sm leading-snug text-white tracking-tight pr-6" x-text="note.title"></h3>
                                <p class="text-sm text-ink-400 line-clamp-4 whitespace-pre-line leading-relaxed" x-text="note.content"></p>
                            </div>
                        </template>
                    </div>

                    <div x-show="!loading && notes.length === 0 && subfolders.length === 0" class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-white/[0.04] border border-white/[0.06] flex items-center justify-center mb-4">
                            <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="text-ink-500"><path d="M4 3a1 1 0 011-1h6l4 4v11a1 1 0 01-1 1H5a1 1 0 01-1-1V3z"/><path d="M11 2v4h4"/></svg>
                        </div>
                        <p class="text-sm text-ink-500">Nothing here yet. Create a note or a folder to get started.</p>
                    </div>
                </div>
            </template>

            <template x-if="selectedNote">
                <div class="max-w-2xl">
                    <button @click="selectedNote = null; isEditingDetail = false; isNewNote = false;" class="flex items-center gap-1.5 text-sm text-ink-500 hover:text-white transition-colors mb-6">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
                        Back
                    </button>

                    <!-- View mode -->
                    <template x-if="!isEditingDetail">
                        <div>
                            <h1 class="text-2xl font-bold text-white tracking-tight mb-5" x-text="selectedNote.title"></h1>
                            <p class="text-sm text-ink-300 whitespace-pre-line leading-relaxed" x-text="selectedNote.content"></p>

                            <div class="mt-10 flex items-center gap-2">
                                <button @click="startEditNote()" class="btn-secondary">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                    Edit
                                </button>
                                <button @click="deleteNote(selectedNote)" class="btn-ghost text-red-400 hover:bg-red-500/10 hover:text-red-300">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Edit / create mode -->
                    <template x-if="isEditingDetail">
                        <form @submit.prevent="saveDetail()" class="space-y-5">
                            <div>
                                <input
                                    type="text"
                                    x-model="detailForm.title"
                                    placeholder="Untitled"
                                    class="w-full bg-transparent text-2xl font-bold text-white tracking-tight placeholder-ink-600 outline-none border-b border-white/10 focus:border-indigo-500/60 pb-2 transition-colors"
                                >
                                <p class="text-xs text-red-400 mt-1.5" x-show="errors.title" x-text="errors.title?.[0]"></p>
                            </div>

                            <div>
                                <textarea
                                    x-model="detailForm.content"
                                    rows="10"
                                    placeholder="Write something…"
                                    class="w-full bg-transparent text-sm text-ink-200 placeholder-ink-600 outline-none leading-relaxed resize-none"
                                ></textarea>
                                <p class="text-xs text-red-400 mt-1.5" x-show="errors.content" x-text="errors.content?.[0]"></p>
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <button
                                    type="submit"
                                    class="btn-primary"
                                    :class="activeCategory === 'office' ? 'from-indigo-500 to-indigo-600 hover:from-indigo-400 hover:to-indigo-500' : 'from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500'"
                                >Save</button>
                                <button type="button" @click="cancelEditDetail()" class="btn-ghost">Cancel</button>
                            </div>
                        </form>
                    </template>
                </div>
            </template>
        </div>
    </main>

    <!-- New Folder Modal -->
    <div
        x-show="showFolderModal"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-50"
        style="display: none;"
    >
        <div
            @click.outside="showFolderModal = false"
            x-show="showFolderModal"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="card w-full max-w-md p-7 shadow-popover"
        >
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-white tracking-tight" x-text="renamingFolderId ? 'Rename Folder' : 'New Folder'"></h2>
                <span
                    class="text-[11px] font-semibold px-2.5 py-1 rounded-full"
                    :class="activeCategory === 'office' ? 'bg-blue-500/10 text-blue-400' : 'bg-emerald-500/10 text-emerald-400'"
                    x-text="activeCategory === 'office' ? '💼 Work' : '🏠 Personal'"
                ></span>
            </div>

            <form @submit.prevent="submitFolder()" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium mb-1.5 text-ink-400">Name</label>
                    <input type="text" x-model="folderForm.name" class="input-field">
                    <p class="text-xs text-red-400 mt-1.5" x-show="errors.name" x-text="errors.name?.[0]"></p>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showFolderModal = false" class="btn-ghost">Cancel</button>
                    <button type="submit" class="btn-secondary" x-text="renamingFolderId ? 'Save' : 'Create'"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function notesApp() {
        return {
            activeCategory: localStorage.getItem('activeCategory') || 'office',
            folderTree: [],
            nodesById: {},
            selectedFolderId: null,
            breadcrumbs: [],
            subfolders: [],
            notes: [],
            selectedNote: null,
            isEditingDetail: false,
            isNewNote: false,
            detailForm: { title: '', content: '' },
            loading: false,
            search: '',
            showFolderModal: false,
            renamingFolderId: null,
            folderForm: { name: '' },
            errors: {},

            init() {
                this.loadContent(null);
            },

            escapeHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            },

            toggleCategory() {
                this.activeCategory = this.activeCategory === 'office' ? 'personal' : 'office';
                localStorage.setItem('activeCategory', this.activeCategory);

                this.folderTree = [];
                this.nodesById = {};
                this.loadContent(null);
            },

            makeNode(folder, parentId) {
                const node = {
                    id: folder.id,
                    name: folder.name,
                    parentId: parentId,
                    children: null,
                    expanded: false,
                };
                this.nodesById[folder.id] = node;
                return node;
            },

            buildBreadcrumbs(id) {
                const crumbs = [];
                let current = id ? this.nodesById[id] : null;

                while (current) {
                    crumbs.unshift({ id: current.id, name: current.name });
                    current = current.parentId ? this.nodesById[current.parentId] : null;
                }

                return crumbs;
            },

            visibleTree() {
                if (!this.search) {
                    return this.folderTree;
                }

                const term = this.search.toLowerCase();

                return this.folderTree.filter((node) => node.name.toLowerCase().includes(term));
            },

            async loadContent(folderId) {
                this.loading = true;
                this.selectedFolderId = folderId;
                this.breadcrumbs = this.buildBreadcrumbs(folderId);

                const params = new URLSearchParams({ category: this.activeCategory });
                if (folderId) {
                    params.set('folder_id', folderId);
                }

                const [foldersRes, notesRes] = await Promise.all([
                    fetch(`/api/folders?${params}`),
                    fetch(`/api/notes?${params}`),
                ]);

                const foldersData = await foldersRes.json();
                const notesData = await notesRes.json();

                const childNodes = foldersData.map((f) => this.makeNode(f, folderId));

                if (folderId === null) {
                    this.folderTree = childNodes;
                } else {
                    const parentNode = this.nodesById[folderId];
                    if (parentNode) {
                        parentNode.children = childNodes;
                        parentNode.expanded = true;
                    }
                }

                this.subfolders = childNodes;
                this.notes = notesData;
                this.loading = false;
            },

            selectFolder(id) {
                this.selectedNote = null;
                this.isEditingDetail = false;
                this.isNewNote = false;
                this.loadContent(id);
            },

            async viewNote(note) {
                const res = await fetch(`/api/notes/${note.id}`);
                this.selectedNote = await res.json();
                this.isEditingDetail = false;
                this.isNewNote = false;
            },

            async deleteNote(note) {
                if (!confirm(`Delete "${note.title || 'this note'}"? This cannot be undone.`)) {
                    return;
                }

                await fetch(`/api/notes/${note.id}`, { method: 'DELETE' });

                this.notes = this.notes.filter((n) => n.id !== note.id);

                if (this.selectedNote && this.selectedNote.id === note.id) {
                    this.selectedNote = null;
                    this.isEditingDetail = false;
                }
            },

            async toggleFolder(id) {
                const node = this.nodesById[id];
                if (!node) {
                    return;
                }

                if (node.children === null) {
                    const params = new URLSearchParams({ category: this.activeCategory, folder_id: id });
                    const res = await fetch(`/api/folders?${params}`);
                    const data = await res.json();
                    node.children = data.map((f) => this.makeNode(f, id));
                }

                node.expanded = !node.expanded;
            },

            renderTree(nodes, depth = 0) {
                return nodes.map((node) => {
                    const isSelected = this.selectedFolderId === node.id;
                    const rowClass = isSelected ? 'sidebar-item-active' : '';
                    const chevronClass = node.expanded ? 'rotate-90' : '';

                    let html = `
                        <div>
                            <div class="sidebar-item group cursor-pointer ${rowClass}"
                                 style="padding-left: ${12 + depth * 14}px; padding-top: 7px; padding-bottom: 7px; padding-right: 10px; margin-left: 12px; margin-right: 12px; width: calc(100% - 24px);"
                                 @click="selectFolder('${node.id}')">
                                <button type="button" @click.stop="toggleFolder('${node.id}')"
                                        class="w-3.5 h-3.5 flex items-center justify-center text-ink-500 hover:text-ink-100 shrink-0 transition-transform duration-150 ${chevronClass}">
                                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 6l6 6-6 6"/></svg>
                                </button>
                                <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor" class="text-ink-500 shrink-0"><path d="M2 6a2 2 0 012-2h4.5l1.5 2H16a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                <span class="truncate flex-1">${this.escapeHtml(node.name)}</span>
                                <div class="hidden group-hover:flex items-center gap-0.5 shrink-0">
                                    <button type="button" @click.stop="openRenameFolderModal(nodesById['${node.id}'])"
                                            title="Rename folder"
                                            class="w-5 h-5 rounded-md flex items-center justify-center text-ink-500 hover:text-amber-400 hover:bg-amber-500/10">
                                        <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor"><path d="M13.5 2.5a1.5 1.5 0 012.121 2.121l-8.5 8.5-2.828.707.707-2.828 8.5-8.5z"/></svg>
                                    </button>
                                    <button type="button" @click.stop="deleteFolder(nodesById['${node.id}'])"
                                            title="Delete folder"
                                            class="w-5 h-5 rounded-md flex items-center justify-center text-ink-500 hover:text-red-400 hover:bg-red-500/10">
                                        <svg width="10" height="10" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h12M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2m3 0-.7 9.1a2 2 0 01-2 1.9H7.7a2 2 0 01-2-1.9L5 6h10z"/></svg>
                                    </button>
                                </div>
                            </div>
                            ${node.expanded && node.children ? `<div>${this.renderTree(node.children, depth + 1)}</div>` : ''}
                        </div>
                    `;

                    return html;
                }).join('');
            },

            startNewNote() {
                this.selectedNote = { id: null, title: '', content: '' };
                this.detailForm = { title: '', content: '' };
                this.errors = {};
                this.isNewNote = true;
                this.isEditingDetail = true;
            },

            startEditNote() {
                this.detailForm = { title: this.selectedNote.title, content: this.selectedNote.content };
                this.errors = {};
                this.isEditingDetail = true;
            },

            cancelEditDetail() {
                this.errors = {};

                if (this.isNewNote) {
                    this.selectedNote = null;
                    this.isNewNote = false;
                }

                this.isEditingDetail = false;
            },

            async saveDetail() {
                this.errors = {};

                const url = this.isNewNote ? '/api/notes' : `/api/notes/${this.selectedNote.id}`;
                const method = this.isNewNote ? 'POST' : 'PUT';
                const body = this.isNewNote
                    ? { ...this.detailForm, category: this.activeCategory, folder_id: this.selectedFolderId }
                    : { title: this.detailForm.title, content: this.detailForm.content };

                const response = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body),
                });

                if (response.status === 422) {
                    const errorBody = await response.json();
                    this.errors = errorBody.errors ?? {};
                    return;
                }

                const note = await response.json();

                if (this.isNewNote) {
                    this.notes.push(note);
                    this.isNewNote = false;
                } else {
                    const existing = this.notes.find((n) => n.id === note.id);
                    if (existing) {
                        existing.title = note.title;
                        existing.content = note.content;
                    }
                }

                this.selectedNote = note;
                this.isEditingDetail = false;
            },

            openFolderModal() {
                this.renamingFolderId = null;
                this.folderForm = { name: '' };
                this.errors = {};
                this.showFolderModal = true;
            },

            openRenameFolderModal(folder) {
                this.renamingFolderId = folder.id;
                this.folderForm = { name: folder.name };
                this.errors = {};
                this.showFolderModal = true;
            },

            async submitFolder() {
                this.errors = {};

                if (this.renamingFolderId) {
                    const response = await fetch(`/api/folders/${this.renamingFolderId}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ name: this.folderForm.name }),
                    });

                    if (response.status === 422) {
                        const errorBody = await response.json();
                        this.errors = errorBody.errors ?? {};
                        return;
                    }

                    const updated = await response.json();
                    const node = this.nodesById[updated.id];
                    if (node) {
                        node.name = updated.name;
                    }

                    this.showFolderModal = false;
                    return;
                }

                const response = await fetch('/api/folders', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        ...this.folderForm,
                        category: this.activeCategory,
                        folder_id: this.selectedFolderId,
                    }),
                });

                if (response.status === 422) {
                    const body = await response.json();
                    this.errors = body.errors ?? {};
                    return;
                }

                const folder = await response.json();
                const node = this.makeNode(folder, this.selectedFolderId);

                if (this.selectedFolderId === null) {
                    this.folderTree.push(node);
                } else {
                    const parent = this.nodesById[this.selectedFolderId];
                    if (parent) {
                        if (parent.children === null) {
                            parent.children = [];
                        }
                        parent.children.push(node);
                        parent.expanded = true;
                    }
                }

                this.subfolders.push(node);
                this.showFolderModal = false;
            },

            folderContainsCurrentView(folder) {
                if (this.selectedFolderId === folder.id) {
                    return true;
                }

                let current = this.selectedFolderId ? this.nodesById[this.selectedFolderId] : null;

                while (current) {
                    if (current.parentId === folder.id) {
                        return true;
                    }
                    current = current.parentId ? this.nodesById[current.parentId] : null;
                }

                return false;
            },

            async deleteFolder(folder) {
                if (!confirm(`Delete "${folder.name}" and everything inside it? This cannot be undone.`)) {
                    return;
                }

                await fetch(`/api/folders/${folder.id}`, { method: 'DELETE' });

                const shouldNavigateAway = this.folderContainsCurrentView(folder);

                this.folderTree = this.folderTree.filter((n) => n.id !== folder.id);
                this.subfolders = this.subfolders.filter((n) => n.id !== folder.id);

                if (folder.parentId) {
                    const parent = this.nodesById[folder.parentId];
                    if (parent && parent.children) {
                        parent.children = parent.children.filter((n) => n.id !== folder.id);
                    }
                }

                delete this.nodesById[folder.id];

                if (shouldNavigateAway) {
                    this.selectFolder(folder.parentId ?? null);
                }
            },
        };
    }
</script>

</body>
</html>
