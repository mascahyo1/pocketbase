<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - PocketBase</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Manajemen Posts</h1>
            <p class="text-gray-600">Kelola posting dengan filter dan realtime update</p>
        </div>

        <div id="alertMessage" class="hidden mb-6 p-4 rounded-lg">
            <span id="alertText"></span>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Filter Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Waktu</label>
                    <input type="datetime-local" id="filterStartTime" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Waktu</label>
                    <input type="datetime-local" id="filterEndTime" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="publish">Publish</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="filterCategory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex gap-3">
                <button onclick="applyFilters()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                    Terapkan Filter
                </button>
                <button onclick="resetFilters()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-6 py-2 rounded-lg transition duration-200">
                    Reset
                </button>
            </div>
        </div>

        <div class="mb-6">
            <button onclick="openCreateModal()" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                + Tambah Post
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="postsTableBody" class="bg-white divide-y divide-gray-200">
                        <tr id="loadingRow">
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Loading...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="pagination" class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Menampilkan <span id="showingText">0</span> dari <span id="totalItems">0</span> posts
            </div>
            <div class="flex gap-2" id="paginationButtons">
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Tambah Post</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="createForm" onsubmit="handleCreate(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Title</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Kategori</label>
                        <select name="category_id" id="create_category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Status</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="draft">Draft</option>
                            <option value="publish">Publish</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Waktu Mulai</label>
                        <input type="datetime-local" name="start_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Waktu Selesai</label>
                        <input type="datetime-local" name="end_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi</label>
                        <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Batal</button>
                    <button type="submit" id="createBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Edit Post</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="editForm" onsubmit="handleUpdate(event)">
                <input type="hidden" name="id" id="edit_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Title</label>
                        <input type="text" name="title" id="edit_title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Kategori</label>
                        <select name="category_id" id="edit_category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Status</label>
                        <select name="status" id="edit_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="draft">Draft</option>
                            <option value="publish">Publish</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Waktu Mulai</label>
                        <input type="datetime-local" name="start_time" id="edit_start_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Waktu Selesai</label>
                        <input type="datetime-local" name="end_time" id="edit_end_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi</label>
                        <textarea name="description" id="edit_description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Batal</button>
                    <button type="submit" id="updateBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/pocketbase@0.21.5/dist/pocketbase.umd.js"></script>
    <script>
        const pb = new PocketBase('http://127.0.0.1:8090');
        pb.autoCancellation(false);

        let currentPage = 1;
        const perPage = 10;
        let categories = [];
        let currentFilters = {
            startTime: '',
            endTime: '',
            status: '',
            category: ''
        };

        document.addEventListener('DOMContentLoaded', function() {
            setDefaultDateFilter();
            loadCategories();
            loadPosts();
            subscribeToPosts();
        });

        function setDefaultDateFilter() {
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
            
            const formatDateTime = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day}T${hours}:${minutes}`;
            };
            
            const formatForFilter = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            };
            
            document.getElementById('filterStartTime').value = formatDateTime(firstDay);
            document.getElementById('filterEndTime').value = formatDateTime(lastDay);
            
            currentFilters.startTime = formatForFilter(firstDay);
            currentFilters.endTime = formatForFilter(lastDay);
        }

        async function loadCategories() {
            try {
                const records = await pb.collection('post_categories').getList(1, 100, {
                    sort: 'name',
                });
                
                categories = records.items;
                
                // Populate filter dropdown
                const filterSelect = document.getElementById('filterCategory');
                const createSelect = document.getElementById('create_category_id');
                const editSelect = document.getElementById('edit_category_id');
                
                categories.forEach(cat => {
                    const option = `<option value="${cat.id}">${escapeHtml(cat.name)}</option>`;
                    filterSelect.innerHTML += option;
                    createSelect.innerHTML += option;
                    editSelect.innerHTML += option;
                });
                
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        function subscribeToPosts() {
            pb.collection('posts').subscribe('*', function (e) {
                console.log('Realtime update:', e.action);
                loadPosts(currentPage);
            });
        }

        function buildFilterString() {
            let filters = [];
            
            if (currentFilters.startTime) {
                filters.push(`start_time >= "${currentFilters.startTime}"`);
            }
            
            if (currentFilters.endTime) {
                filters.push(`end_time <= "${currentFilters.endTime}"`);
            }
            
            if (currentFilters.status) {
                filters.push(`status = "${currentFilters.status}"`);
            }
            
            if (currentFilters.category) {
                filters.push(`category_id = "${currentFilters.category}"`);
            }
            
            const filterString = filters.length > 0 ? filters.join(' && ') : '';
            console.log('Filter Query:', filterString);
            return filterString;
        }

        async function loadPosts(page = 1) {
            try {
                currentPage = page;
                
                const filterString = buildFilterString();
                console.log('Current Filters:', currentFilters);
                console.log('Loading posts with filter:', filterString);
                
                const records = await pb.collection('posts').getList(page, perPage, {
                    sort: '-created',
                    filter: filterString,
                });
                
                console.log('Posts loaded:', records);
                console.log('Total items:', records.totalItems);
                
                const tbody = document.getElementById('postsTableBody');
                const loadingRow = document.getElementById('loadingRow');
                if (loadingRow) loadingRow.remove();
                
                if (records.items && records.items.length > 0) {
                    const startNum = (page - 1) * perPage;
                    tbody.innerHTML = records.items.map((post, index) => {
                        const category = categories.find(c => c.id === post.category_id);
                        const categoryName = category ? category.name : 'N/A';
                        
                        const statusBadge = post.status === 'publish' 
                            ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Publish</span>'
                            : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>';
                        
                        return `
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${startNum + index + 1}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">${escapeHtml(post.title || '')}</div>
                                    <div class="text-xs text-gray-500 mt-1">${escapeHtml(post.description ? post.description.substring(0, 60) + '...' : '')}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${escapeHtml(categoryName)}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-600">
                                        ${formatDateTime(post.start_time)}<br/>
                                        s/d ${formatDateTime(post.end_time)}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${statusBadge}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button onclick='openEditModal(${JSON.stringify(post).replace(/'/g, "&#39;")})' class="text-blue-600 hover:text-blue-900 mr-3 font-medium">Edit</button>
                                    <button onclick="confirmDelete('${post.id}', '${escapeHtml(post.title || '').replace(/'/g, "&#39;")}')" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada post. Silakan tambahkan post baru.
                            </td>
                        </tr>
                    `;
                }
                
                updatePagination(records);
            } catch (error) {
                console.error('Error loading posts:', error);
                const tbody = document.getElementById('postsTableBody');
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-red-500">
                            Error: ${error.message}
                        </td>
                    </tr>
                `;
                showAlert('Gagal memuat data posts: ' + error.message, 'error');
            }
        }

        function updatePagination(records) {
            const showingStart = (records.page - 1) * records.perPage + 1;
            const showingEnd = Math.min(records.page * records.perPage, records.totalItems);
            
            document.getElementById('showingText').textContent = records.totalItems > 0 ? `${showingStart}-${showingEnd}` : '0';
            document.getElementById('totalItems').textContent = records.totalItems;
            
            const paginationButtons = document.getElementById('paginationButtons');
            let buttonsHTML = '';
            
            if (records.totalPages > 0) {
                buttonsHTML += `
                    <button onclick="loadPosts(${records.page - 1})" ${records.page === 1 ? 'disabled' : ''} 
                        class="px-3 py-1 border rounded ${records.page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-50'}">
                        Sebelumnya
                    </button>
                `;
                
                for (let i = 1; i <= records.totalPages; i++) {
                    if (i === 1 || i === records.totalPages || (i >= records.page - 1 && i <= records.page + 1)) {
                        buttonsHTML += `
                            <button onclick="loadPosts(${i})" 
                                class="px-3 py-1 border rounded ${i === records.page ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-50'}">
                                ${i}
                            </button>
                        `;
                    } else if (i === records.page - 2 || i === records.page + 2) {
                        buttonsHTML += `<span class="px-2">...</span>`;
                    }
                }
                
                buttonsHTML += `
                    <button onclick="loadPosts(${records.page + 1})" ${records.page === records.totalPages ? 'disabled' : ''} 
                        class="px-3 py-1 border rounded ${records.page === records.totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-50'}">
                        Selanjutnya
                    </button>
                `;
            }
            
            paginationButtons.innerHTML = buttonsHTML;
        }

        function applyFilters() {
            const startTimeValue = document.getElementById('filterStartTime').value;
            const endTimeValue = document.getElementById('filterEndTime').value;
            
            if (startTimeValue) {
                currentFilters.startTime = startTimeValue.replace('T', ' ') + ':00';
            } else {
                currentFilters.startTime = '';
            }
            
            if (endTimeValue) {
                currentFilters.endTime = endTimeValue.replace('T', ' ') + ':00';
            } else {
                currentFilters.endTime = '';
            }
            
            currentFilters.status = document.getElementById('filterStatus').value;
            currentFilters.category = document.getElementById('filterCategory').value;
            
            loadPosts(1);
        }

        function resetFilters() {
            document.getElementById('filterStartTime').value = '';
            document.getElementById('filterEndTime').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterCategory').value = '';
            
            currentFilters = {
                startTime: '',
                endTime: '',
                status: '',
                category: ''
            };
            
            loadPosts(1);
        }

        async function handleCreate(event) {
            event.preventDefault();
            
            const btn = document.getElementById('createBtn');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';
            
            const formData = new FormData(event.target);
            
            // Convert datetime-local to YYYY-MM-DD HH:mm:ss format
            if (formData.get('start_time')) {
                const startValue = formData.get('start_time');
                formData.set('start_time', startValue.replace('T', ' ') + ':00');
            }
            if (formData.get('end_time')) {
                const endValue = formData.get('end_time');
                formData.set('end_time', endValue.replace('T', ' ') + ':00');
            }
            
            try {
                const response = await fetch('api/post/create.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('Post berhasil ditambahkan!', 'success');
                    closeCreateModal();
                    event.target.reset();
                    loadPosts();
                } else {
                    showAlert('Gagal menambahkan post: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        }

        async function handleUpdate(event) {
            event.preventDefault();
            
            const btn = document.getElementById('updateBtn');
            btn.disabled = true;
            btn.textContent = 'Mengupdate...';
            
            const formData = new FormData(event.target);
            
            // Convert datetime-local to YYYY-MM-DD HH:mm:ss format
            if (formData.get('start_time')) {
                const startValue = formData.get('start_time');
                formData.set('start_time', startValue.replace('T', ' ') + ':00');
            }
            if (formData.get('end_time')) {
                const endValue = formData.get('end_time');
                formData.set('end_time', endValue.replace('T', ' ') + ':00');
            }
            
            try {
                const response = await fetch('api/post/update.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('Post berhasil diupdate!', 'success');
                    closeEditModal();
                    loadPosts(currentPage);
                } else {
                    showAlert('Gagal mengupdate post: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Update';
            }
        }

        async function deletePost(id) {
            try {
                const formData = new FormData();
                formData.append('id', id);
                
                const response = await fetch('api/post/delete.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('Post berhasil dihapus!', 'success');
                    loadPosts(currentPage);
                } else {
                    showAlert('Gagal menghapus post: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            }
        }

        function showAlert(message, type) {
            const alert = document.getElementById('alertMessage');
            const alertText = document.getElementById('alertText');
            
            alertText.textContent = message;
            alert.className = `mb-6 p-4 rounded-lg ${type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'}`;
            alert.classList.remove('hidden');
            
            setTimeout(() => {
                alert.classList.add('hidden');
            }, 5000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        }

        function formatDateTime(dateTimeString) {
            if (!dateTimeString) return '-';
            const date = new Date(dateTimeString);
            return date.toLocaleString('id-ID', { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('createForm').reset();
        }

        function openEditModal(post) {
            document.getElementById('edit_id').value = post.id;
            document.getElementById('edit_title').value = post.title;
            document.getElementById('edit_category_id').value = post.category_id;
            document.getElementById('edit_status').value = post.status;
            
            // Convert YYYY-MM-DD HH:mm:ss to datetime-local format (YYYY-MM-DDTHH:mm)
            if (post.start_time) {
                const startStr = post.start_time.replace(' ', 'T').slice(0, 16);
                document.getElementById('edit_start_time').value = startStr;
            }
            
            if (post.end_time) {
                const endStr = post.end_time.replace(' ', 'T').slice(0, 16);
                document.getElementById('edit_end_time').value = endStr;
            }
            
            document.getElementById('edit_description').value = post.description || '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(id, title) {
            if (confirm('Apakah Anda yakin ingin menghapus post "' + title + '"?')) {
                deletePost(id);
            }
        }

        window.onclick = function(event) {
            const createModal = document.getElementById('createModal');
            const editModal = document.getElementById('editModal');
            if (event.target === createModal) {
                closeCreateModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>
