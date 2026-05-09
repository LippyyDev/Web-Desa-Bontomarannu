<?php
/**
 * FlashMessage Component
 * Listens for CI4 session flashdata and triggers SweetAlert2.
 * Requires sweetalert.js to be loaded beforehand.
 */
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->getFlashdata('success')): ?>
        showSuccess("<?= addslashes(session()->getFlashdata('success')) ?>");
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        showError("<?= addslashes(session()->getFlashdata('error')) ?>");
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')): ?>
        showInfo("<?= addslashes(session()->getFlashdata('info')) ?>");
    <?php endif; ?>
});
</script>
