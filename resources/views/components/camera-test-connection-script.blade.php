<script>
    document.getElementById('btn-test-connection')?.addEventListener('click', async function () {
        const btn = this;
        const result = document.getElementById('test-result');
        const url = document.getElementById('rtsp_url').value.trim();
        const username = document.getElementById('username')?.value.trim() || '';
        const password = document.getElementById('password')?.value || '';
        const token = document.querySelector('input[name="_token"]').value;

        if (!url) {
            result.classList.remove('hidden');
            result.className = 'mt-2 text-sm rounded-lg p-3 bg-danger/10 border border-danger/20 text-danger';
            result.textContent = 'Isi RTSP URL terlebih dahulu.';
            return;
        }

        result.classList.remove('hidden');
        result.className = 'mt-2 text-sm rounded-lg p-3 bg-dark-elevated border border-border-subtle text-text-secondary';
        result.textContent = 'Testing connection...';
        btn.disabled = true;
        btn.style.opacity = '0.5';

        try {
            const response = await fetch('{{ route('dashboard.camera.test-connection') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ rtsp_url: url, username: username, password: password })
            });

            const data = await response.json();

            if (data.success) {
                result.className = 'mt-2 text-sm rounded-lg p-3 bg-success/10 border border-success/20 text-success';
            } else {
                result.className = 'mt-2 text-sm rounded-lg p-3 bg-danger/10 border border-danger/20 text-danger';
            }
            result.textContent = data.message || (data.success ? 'Connected' : 'Connection failed');
        } catch (e) {
            result.className = 'mt-2 text-sm rounded-lg p-3 bg-danger/10 border border-danger/20 text-danger';
            result.textContent = 'Request gagal: ' + e.message;
        } finally {
            btn.disabled = false;
            btn.style.opacity = '1';
        }
    });
</script>