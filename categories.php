<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Categories - PocketBase</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Manajemen Post Categories</h1>
            <p class="text-gray-600">Kelola kategori posting dengan PocketBase</p>
        </div>

        <div id="alertMessage" class="hidden mb-6 p-4 rounded-lg">
            <span id="alertText"></span>
        </div>

        <div class="mb-6">
            <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                + Tambah Kategori
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody" class="bg-white divide-y divide-gray-200">
                    <tr id="loadingRow">
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="pagination" class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Menampilkan <span id="showingText">0</span> dari <span id="totalItems">0</span> kategori
            </div>
            <div class="flex gap-2" id="paginationButtons">
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Tambah Kategori</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="createForm" onsubmit="handleCreate(event)">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Nama</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
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
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Edit Kategori</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="editForm" onsubmit="handleUpdate(event)">
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Nama</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi</label>
                    <textarea name="description" id="edit_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
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

        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            subscribeToCategories();
        });

        function subscribeToCategories() {
            pb.collection('post_categories').subscribe('*', function (e) {
                console.log('Realtime update:', e.action);
                loadCategories();
            });
        }

        async function loadCategories(page = 1) {
            try {
                currentPage = page;
                const records = await pb.collection('post_categories').getList(page, perPage, {
                    sort: '-created',
                });
                
                console.log('Categories loaded:', records);
                
                const tbody = document.getElementById('categoriesTableBody');
                const loadingRow = document.getElementById('loadingRow');
                if (loadingRow) loadingRow.remove();
                
                if (records.items && records.items.length > 0) {
                    const startNum = (page - 1) * perPage;
                    tbody.innerHTML = records.items.map((cat, index) => `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${startNum + index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${escapeHtml(cat.name || '')}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">${escapeHtml(cat.description || '-')}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${new Date(cat.created).toLocaleDateString('id-ID')}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick='openEditModal(${JSON.stringify(cat).replace(/'/g, "&#39;")})' class="text-blue-600 hover:text-blue-900 mr-3 font-medium">Edit</button>
                                <button onclick="confirmDelete('${cat.id}', '${escapeHtml(cat.name || '').replace(/'/g, "&#39;")}')" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada kategori. Silakan tambahkan kategori baru.
                            </td>
                        </tr>
                    `;
                }
                
                updatePagination(records);
            } catch (error) {
                console.error('Error loading categories:', error);
                const tbody = document.getElementById('categoriesTableBody');
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-red-500">
                            Error: ${error.message}
                        </td>
                    </tr>
                `;
                showAlert('Gagal memuat data kategori: ' + error.message, 'error');
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
                    <button onclick="loadCategories(${records.page - 1})" ${records.page === 1 ? 'disabled' : ''} 
                        class="px-3 py-1 border rounded ${records.page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-50'}">
                        Sebelumnya
                    </button>
                `;
                
                for (let i = 1; i <= records.totalPages; i++) {
                    if (i === 1 || i === records.totalPages || (i >= records.page - 1 && i <= records.page + 1)) {
                        buttonsHTML += `
                            <button onclick="loadCategories(${i})" 
                                class="px-3 py-1 border rounded ${i === records.page ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-50'}">
                                ${i}
                            </button>
                        `;
                    } else if (i === records.page - 2 || i === records.page + 2) {
                        buttonsHTML += `<span class="px-2">...</span>`;
                    }
                }
                
                buttonsHTML += `
                    <button onclick="loadCategories(${records.page + 1})" ${records.page === records.totalPages ? 'disabled' : ''} 
                        class="px-3 py-1 border rounded ${records.page === records.totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-50'}">
                        Selanjutnya
                    </button>
                `;
            }
            
            paginationButtons.innerHTML = buttonsHTML;
        }

        async function handleCreate(event) {
            event.preventDefault();
            
            const btn = document.getElementById('createBtn');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';
            
            const formData = new FormData(event.target);
            
            try {
                const response = await fetch('api/category/create.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('Kategori berhasil ditambahkan!', 'success');
                    closeCreateModal();
                    event.target.reset();
                    loadCategories();
                } else {
                    showAlert('Gagal menambahkan kategori: ' + result.message, 'error');
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
            
            try {
                const response = await fetch('api/category/update.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('Kategori berhasil diupdate!', 'success');
                    closeEditModal();
                    loadCategories(currentPage);
                } else {
                    showAlert('Gagal mengupdate kategori: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Update';
            }
        }

        async function deleteCategory(id) {
            try {
                const formData = new FormData();
                formData.append('id', id);
                
                const response = await fetch('api/category/delete.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('Kategori berhasil dihapus!', 'success');
                    loadCategories(currentPage);
                } else {
                    showAlert('Gagal menghapus kategori: ' + result.message, 'error');
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

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('createForm').reset();
        }

        function openEditModal(category) {
            document.getElementById('edit_id').value = category.id;
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_description').value = category.description || '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(id, name) {
            if (confirm('Apakah Anda yakin ingin menghapus kategori "' + name + '"?')) {
                deleteCategory(id);
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
