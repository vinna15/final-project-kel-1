@props(['id', 'title' => 'Konfirmasi'])

<div id="{{ $id }}"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">

        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-base font-semibold text-gray-800">{{ $title }}</h3>
            <button onclick="closeModal('{{ $id }}')"
                    class="text-gray-400 hover:text-gray-600 text-xl">×</button>
        </div>

        <div class="px-6 py-4">
            {{ $slot }}
        </div>

    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>