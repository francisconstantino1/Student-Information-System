/**
 * Modal Management System
 * Handles all modal operations across the application
 */

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.active').forEach(modal => {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
});

// Close modal on overlay click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
    }
});

// Initialize modal exit buttons
document.addEventListener('DOMContentLoaded', function() {
    // Close buttons
    document.querySelectorAll('.exit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });

    // Delete confirmation setup
    document.querySelectorAll('.delete-btn[data-record-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const recordId = this.getAttribute('data-record-id');
            const recordName = this.getAttribute('data-record-name') || 'this record';
            const recordType = this.getAttribute('data-record-type') || 'record';
            const deleteUrl = this.getAttribute('data-delete-url') || '#';
            
            // Update modal content
            const modal = document.getElementById('delete-confirmation-modal');
            if (modal) {
                const messageEl = document.getElementById('delete-confirmation-message');
                if (messageEl) {
                    messageEl.innerHTML = `
                        <p>Are you sure you want to delete <strong>${recordName}</strong> (ID: ${recordId})?</p>
                        <p style="color: #f44336; font-weight: 600; margin-top: 10px;">
                            ⚠️ This action cannot be undone and will permanently remove all data for this ${recordType}.
                        </p>
                    `;
                }
                
                const form = document.getElementById('delete-confirmation-form');
                if (form) {
                    form.action = deleteUrl;
                    document.getElementById('delete-record-id').value = recordId;
                }
                
                openModal('delete-confirmation-modal');
            }
        });
    });

    // Archive confirmation setup
    document.querySelectorAll('.archive-btn[data-record-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const recordId = this.getAttribute('data-record-id');
            const recordName = this.getAttribute('data-record-name') || 'this record';
            const recordType = this.getAttribute('data-record-type') || 'record';
            const archiveUrl = this.getAttribute('data-archive-url') || '#';
            
            // Update modal content
            const modal = document.getElementById('archive-confirmation-modal');
            if (modal) {
                const messageEl = document.getElementById('archive-confirmation-message');
                if (messageEl) {
                    messageEl.innerHTML = `
                        <p>Are you sure you want to archive <strong>${recordName}</strong> (ID: ${recordId})?</p>
                        <p style="color: #ff9800; font-weight: 600; margin-top: 10px;">
                            📦 This record will be moved to the archive page and can be restored later.
                        </p>
                    `;
                }
                
                const form = document.getElementById('archive-confirmation-form');
                if (form) {
                    form.action = archiveUrl;
                    document.getElementById('archive-record-id').value = recordId;
                }
                
                openModal('archive-confirmation-modal');
            }
        });
    });

    // View button setup
    document.querySelectorAll('.view-btn[data-view-url]').forEach(btn => {
        btn.addEventListener('click', function() {
            const viewUrl = this.getAttribute('data-view-url');
            const modalId = this.getAttribute('data-modal-id') || 'view-modal';
            
            // Fetch data and populate modal
            fetch(viewUrl)
                .then(response => response.json())
                .then(data => {
                    populateViewModal(data, modalId);
                    openModal(modalId);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    // Fallback to direct navigation
                    window.location.href = viewUrl.replace('/api', '');
                });
        });
    });
});

/**
 * Populate view modal with data
 */
function populateViewModal(data, modalId = 'view-modal') {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const body = modal.querySelector('#view-modal-body') || modal.querySelector('.modal-content');
    if (!body) return;

    // Clear existing content
    const existingBody = body.querySelector('#view-modal-fields');
    if (existingBody) {
        existingBody.remove();
    }

    // Create fields container
    const fieldsContainer = document.createElement('div');
    fieldsContainer.id = 'view-modal-fields';

    // Populate fields
    Object.keys(data).forEach(key => {
        if (key === 'id' || key === 'created_at' || key === 'updated_at') return;
        
        const formGroup = document.createElement('div');
        formGroup.className = 'form-group';

        const label = document.createElement('label');
        label.textContent = formatLabel(key);
        
        const viewField = document.createElement('div');
        viewField.className = 'view-field';
        viewField.textContent = data[key] || 'N/A';

        formGroup.appendChild(label);
        formGroup.appendChild(viewField);
        fieldsContainer.appendChild(formGroup);
    });

    body.insertBefore(fieldsContainer, body.querySelector('.modal-buttons'));
}

/**
 * Format field labels (convert snake_case to Title Case)
 */
function formatLabel(key) {
    return key
        .replace(/_/g, ' ')
        .replace(/\b\w/g, l => l.toUpperCase());
}

/**
 * Create a view modal dynamically
 */
function createViewModal(id, title = 'View Record') {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = `view-modal-${id}`;
    modal.innerHTML = `
        <div class="modal-content">
            <div class="form-header">
                <h3>Student Information System</h3>
                <p>View Record Details</p>
                <h4>${title}</h4>
            </div>
            <div id="view-modal-body-${id}">
                <div id="view-modal-fields-${id}"></div>
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn print-btn" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
                <button type="button" class="btn exit-btn" onclick="closeModal('view-modal-${id}')">
                    <i class="fas fa-times"></i> Exit
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    return modal;
}
