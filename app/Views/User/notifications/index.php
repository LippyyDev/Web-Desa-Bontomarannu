<?= $this->extend('User/layout') ?>

<?= $this->section('content') ?>
<div class="mb-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <div class="text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 2px; color: #64748b;">
            <span style="display: inline-block; width: 24px; height: 2px; background-color: #cbd5e1; margin-bottom: 4px; margin-right: 8px;"></span>
            INFORMASI
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Notifikasi <span style="color: #15803d;">Pesan</span>
        </h2>
        <p class="text-muted fs-6 mb-0" style="max-width: 600px;">Status terbaru surat dan balasan staf.</p>
    </div>
    <div class="d-flex gap-2">
        <button id="btn-mark-all-read" class="btn btn-outline-success">Tandai Semua Dibaca</button>
        <button id="btn-delete-all" class="btn btn-danger">Hapus Semua</button>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="list-group list-group-flush" id="notification-container">
            <!-- Items akan di-load via AJAX -->
        </div>
        
        <div id="empty-state" class="list-group-item text-muted small text-center py-4 d-none">
            Belum ada notifikasi.
        </div>
        
        <div class="text-center p-3 d-none" id="load-more-container">
            <button class="btn btn-sm btn-outline-primary" id="load-more-btn">Muat Lebih Banyak</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let isLoading = false;
    const container = document.getElementById('notification-container');
    const loadMoreBtn = document.getElementById('load-more-btn');
    const loadMoreContainer = document.getElementById('load-more-container');
    const emptyState = document.getElementById('empty-state');
    
    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';

    function fetchNotifications(page) {
        if (isLoading) return;
        isLoading = true;
        
        if (page === 1) {
            container.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div></div>';
        } else {
            loadMoreBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memuat...';
            loadMoreBtn.disabled = true;
        }
        
        const formData = new URLSearchParams();
        formData.append('page', page);
        formData.append('per_page', 15);
        formData.append(csrfHeaderName, getCsrfHash());
        
        fetch('<?= base_url('/user/notifikasi/data') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (page === 1) container.innerHTML = '';
            
            if (data.success) {
                if (data.notifications.length === 0 && page === 1) {
                    emptyState.classList.remove('d-none');
                } else {
                    emptyState.classList.add('d-none');
                    renderNotifications(data.notifications);
                }
                
                if (data.has_more) {
                    loadMoreContainer.classList.remove('d-none');
                } else {
                    loadMoreContainer.classList.add('d-none');
                }
            } else {
                if (page === 1) container.innerHTML = '<div class="text-center p-4 text-danger">Gagal memuat data.</div>';
            }
        })
        .catch(err => {
            console.error('Error fetching notifications:', err);
            if (page === 1) container.innerHTML = '<div class="text-center p-4 text-danger">Terjadi kesalahan.</div>';
        })
        .finally(() => {
            isLoading = false;
            loadMoreBtn.innerHTML = 'Muat Lebih Banyak';
            loadMoreBtn.disabled = false;
        });
    }

    function renderNotifications(items) {
        items.forEach(notif => {
            const isRead = parseInt(notif.is_read) === 1;
            
            let markReadHtml = '';
            if (!isRead) {
                markReadHtml = `<button class="btn btn-link btn-sm p-0 text-decoration-none text-success mark-read-btn fw-medium" data-id="${notif.id}">Tandai dibaca</button>`;
            }
            
            let actionHtml = '';
            if (notif.action_url) {
                actionHtml = `<a href="${notif.action_url}" class="d-block small mt-1 text-decoration-none text-success fw-medium">${notif.action_label} <i class="bi bi-arrow-right"></i></a>`;
            }
            
            const itemHtml = `
                <div class="list-group-item d-flex justify-content-between align-items-start py-3" id="notif-item-${notif.id}">
                    <div style="width: calc(100% - 130px);">
                        <div class="fw-semibold mb-1 ${!isRead ? 'text-dark' : 'text-muted'}">${escapeHtml(notif.title)}</div>
                        <div class="small ${!isRead ? 'text-secondary' : 'text-muted'}" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${escapeHtml(notif.message)}</div>
                        ${actionHtml}
                    </div>
                    <div class="text-end ms-3" style="min-width: 110px;">
                        <div class="small text-muted mb-1">${notif.created_at_formatted}</div>
                        <div id="notif-action-${notif.id}">
                            ${markReadHtml}
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', itemHtml);
        });
        
        attachMarkReadEvents();
    }
    
    function attachMarkReadEvents() {
        document.querySelectorAll('.mark-read-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                markAsRead(id, this);
            });
        });
    }
    
    function markAsRead(id, btnElement) {
        const formData = new URLSearchParams();
        formData.append(csrfHeaderName, getCsrfHash());
        
        const originalText = btnElement.innerText;
        btnElement.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btnElement.disabled = true;
        
        fetch(`<?= base_url('/user/notifikasi') ?>/${id}/read`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const actionContainer = document.getElementById(`notif-action-${id}`);
                if (actionContainer) actionContainer.innerHTML = '';
                
                const item = document.getElementById(`notif-item-${id}`);
                if (item) {
                    item.querySelector('.fw-semibold').classList.replace('text-dark', 'text-muted');
                    item.querySelector('.small.text-secondary')?.classList.replace('text-secondary', 'text-muted');
                }
                
                // Update badge if exists
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    let count = parseInt(badge.innerText.replace('+', '')) - 1;
                    if (count > 0) {
                        badge.innerText = count > 99 ? '99+' : count;
                    } else {
                        badge.remove();
                    }
                }
            } else {
                btnElement.innerHTML = originalText;
                btnElement.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            btnElement.innerHTML = originalText;
            btnElement.disabled = false;
        });
    }

    function escapeHtml(unsafe) {
        return (unsafe || '').toString()
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }

    loadMoreBtn.addEventListener('click', function() {
        currentPage++;
        fetchNotifications(currentPage);
    });

    document.getElementById('btn-mark-all-read').addEventListener('click', function() {
        showConfirm('Apakah Anda yakin ingin menandai semua notifikasi telah dibaca?', 'Tandai Semua').then((confirmed) => {
            if (!confirmed) return;
            
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            this.disabled = true;
            
            const formData = new URLSearchParams();
            formData.append(csrfHeaderName, getCsrfHash());
            
            fetch('<?= base_url('/user/notifikasi/read-all') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove all unread visual indicators
                    document.querySelectorAll('.fw-semibold.text-dark').forEach(el => el.classList.replace('text-dark', 'text-muted'));
                    document.querySelectorAll('.small.text-secondary').forEach(el => el.classList.replace('text-secondary', 'text-muted'));
                    document.querySelectorAll('.mark-read-btn').forEach(el => el.remove());
                    
                    // Clear badge
                    const badge = document.querySelector('.notification-badge');
                    if (badge) badge.remove();
                    
                    showSuccess('Semua notifikasi telah ditandai dibaca.');
                } else {
                    showError('Gagal menandai notifikasi.');
                }
            })
            .catch(() => showError('Terjadi kesalahan sistem.'))
            .finally(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });

    document.getElementById('btn-delete-all').addEventListener('click', function() {
        showConfirm('Apakah Anda yakin ingin MENGHAPUS SEMUA notifikasi secara permanen?', 'Hapus Semua', 'Ya, Hapus').then((confirmed) => {
            if (!confirmed) return;
            
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...';
            this.disabled = true;
            
            const formData = new URLSearchParams();
            formData.append(csrfHeaderName, getCsrfHash());
            
            fetch('<?= base_url('/user/notifikasi/delete-all') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    container.innerHTML = '';
                    emptyState.classList.remove('d-none');
                    loadMoreContainer.classList.add('d-none');
                    
                    // Clear badge
                    const badge = document.querySelector('.notification-badge');
                    if (badge) badge.remove();
                    
                    showSuccess('Semua notifikasi berhasil dihapus.');
                } else {
                    showError('Gagal menghapus notifikasi.');
                }
            })
            .catch(() => showError('Terjadi kesalahan sistem.'))
            .finally(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });

    // Initial load
    fetchNotifications(currentPage);
});
</script>
<?= $this->endSection() ?>


