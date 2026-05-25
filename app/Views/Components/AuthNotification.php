<?php
$session = session();
$messages = [];

// Catch error flashdata (could be string or array)
$error = $session->getFlashdata('error');
if ($error) {
    if (is_array($error)) {
        foreach ($error as $err) {
            $messages[] = ['type' => 'error', 'icon' => 'bi-exclamation-circle-fill', 'text' => $err];
        }
    } else {
        $messages[] = ['type' => 'error', 'icon' => 'bi-exclamation-circle-fill', 'text' => $error];
    }
}

if ($session->getFlashdata('success')) {
    $messages[] = ['type' => 'success', 'icon' => 'bi-check-circle-fill', 'text' => $session->getFlashdata('success')];
}
if ($session->getFlashdata('info')) {
    $messages[] = ['type' => 'info', 'icon' => 'bi-info-circle-fill', 'text' => $session->getFlashdata('info')];
}
?>

<?php if (!empty($messages)): ?>
<div class="glass-toast-container" id="authToastContainer">
    <?php foreach ($messages as $msg): ?>
        <div class="glass-toast <?= $msg['type'] ?>">
            <div class="glass-toast-icon">
                <i class="bi <?= $msg['icon'] ?>"></i>
            </div>
            <div class="glass-toast-content">
                <?= esc($msg['text']) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.glass-toast');
    
    toasts.forEach((toast, index) => {
        // Delay slighty for stagger effect and to ensure DOM is ready for CSS transition
        setTimeout(() => {
            toast.classList.add('show');
            
            // Wait 3 seconds, then slide back up
            setTimeout(() => {
                toast.classList.remove('show');
                
                // Remove from DOM after slide out animation completes (500ms)
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 3000);
        }, index * 200 + 100); // 100ms initial delay
    });
});
</script>
<?php endif; ?>
