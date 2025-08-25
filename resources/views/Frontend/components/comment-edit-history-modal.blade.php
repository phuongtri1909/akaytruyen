<!-- Edit History Modal -->
<div class="modal fade" id="editHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-history"></i> Lịch sử chỉnh sửa bình luận
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="editHistoryContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
    .edit-history-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }

    .edit-history-header {
        margin-bottom: 0.75rem;
    }

    .edit-history-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .edit-history-editor {
        font-weight: 600;
        color: #495057;
    }

    .edit-history-reason {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 6px;
        padding: 0.5rem;
        margin-bottom: 0.75rem;
        font-size: 0.85rem;
        color: #856404;
    }

    .edit-history-content {
        background: white;
        border-radius: 6px;
        padding: 0.75rem;
    }

    .content-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
    }

    .content-text {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 0.5rem;
        font-size: 0.85rem;
        line-height: 1.4;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .content-old {
        border-left: 3px solid #dc3545;
    }

    .content-new {
        border-left: 3px solid #28a745;
    }

    .edit-history-empty {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .edit-history-empty i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    .edit-history-empty p {
        margin: 0;
        font-size: 0.9rem;
    }
</style>
