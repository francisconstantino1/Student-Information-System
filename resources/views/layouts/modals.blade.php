{{-- Delete Confirmation Modal (Shared across all pages) --}}
<div class="modal" id="delete-confirmation-modal">
    <div class="modal-content" style="padding: 0; background: transparent; border: none; box-shadow: none;">
        <div style="background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); padding: 24px; position: relative;">
            <button type="button" aria-label="Close" onclick="closeModal('delete-confirmation-modal')" style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; background: transparent; color: #6B7280; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
            <div style="text-align: center; padding: 12px 8px 4px;">
                <div style="width: 56px; height: 56px; margin: 0 auto 16px; display: grid; place-items: center; border-radius: 999px; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 style="margin: 0 0 12px; color: #111827; font-size: 1.05rem; font-weight: 600;">Are you sure you want to delete this record?</h3>
                <p id="delete-confirmation-message" style="margin: 0 0 20px; color: #6B7280; font-size: 0.95rem;">This action cannot be undone.</p>
                <form id="delete-confirmation-form" method="POST" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete-record-id" name="id">
                    <button type="submit" style="background: #DC2626; color: #FFFFFF; border: 1px solid transparent; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer; box-shadow: 0 6px 14px rgba(220, 38, 38, 0.18);">
                        Yes, delete
                    </button>
                    <button type="button" onclick="closeModal('delete-confirmation-modal')" style="background: #F3F4F6; color: #111827; border: 1px solid #E5E7EB; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer;">
                        No, cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Archive Confirmation Modal (Shared across all pages) --}}
<div class="modal" id="archive-confirmation-modal">
    <div class="modal-content" style="padding: 0; background: transparent; border: none; box-shadow: none;">
        <div style="background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); padding: 24px; position: relative;">
            <button type="button" aria-label="Close" onclick="closeModal('archive-confirmation-modal')" style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; background: transparent; color: #6B7280; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
            <div style="text-align: center; padding: 12px 8px 4px;">
                <div style="width: 56px; height: 56px; margin: 0 auto 16px; display: grid; place-items: center; border-radius: 999px; background: #FFF7ED; color: #EA580C; border: 1px solid #FED7AA;">
                    <i class="fas fa-archive"></i>
                </div>
                <h3 style="margin: 0 0 12px; color: #111827; font-size: 1.05rem; font-weight: 600;">Are you sure you want to archive this record?</h3>
                <p id="archive-confirmation-message" style="margin: 0 0 20px; color: #6B7280; font-size: 0.95rem;">It will be moved to the archive and can be restored later.</p>
                <form id="archive-confirmation-form" method="POST" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    @csrf
                    <input type="hidden" id="archive-record-id" name="id">
                    <button type="submit" style="background: #F59E0B; color: #FFFFFF; border: 1px solid transparent; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer; box-shadow: 0 6px 14px rgba(245, 158, 11, 0.18);">
                        Yes, archive
                    </button>
                    <button type="button" onclick="closeModal('archive-confirmation-modal')" style="background: #F3F4F6; color: #111827; border: 1px solid #E5E7EB; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer;">
                        No, cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/modals.js') }}"></script>

