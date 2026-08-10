<div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-800 text-center">
    <p class="text-xs text-gray-500 dark:text-gray-400">
        Ingin mencoba? Gunakan 
        <button
            type="button"
            onclick="fillAndSubmitDemo()"
            class="text-[#0c3d6e] dark:text-blue-400 font-semibold hover:underline cursor-pointer"
        >
            Akun Demo
        </button>
        untuk masuk otomatis.
    </p>
    <div class="mt-1.5 text-[10px] text-gray-400 dark:text-gray-500 font-mono">
        demo@lldikti.go.id &bull; password
    </div>
</div>

<script>
    function fillAndSubmitDemo() {
        const emailInput = document.querySelector('input[type=email], input[name*=\'email\']');
        const passwordInput = document.querySelector('input[type=password], input[name*=\'password\']');
        
        if (emailInput && passwordInput) {
            // Fill values
            emailInput.value = 'demo@lldikti.go.id';
            passwordInput.value = 'password';
            
            // Dispatch input events so Livewire recognizes the state change
            emailInput.dispatchEvent(new Event('input', { bubbles: true }));
            passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
            
            // Wait a brief moment for Livewire to bind, then submit
            setTimeout(() => {
                const submitBtn = document.querySelector('button[type=submit]');
                if (submitBtn) {
                    submitBtn.click();
                } else {
                    const form = emailInput.closest('form');
                    if (form) {
                        form.submit();
                    }
                }
            }, 100);
        }
    }
</script>
