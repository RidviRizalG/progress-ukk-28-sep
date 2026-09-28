(() => {
    const modal = document.getElementById('detailModal');
    if (!modal) return;

    const el = id => document.getElementById(id);
    let lastTrigger = null;

    function isiTeks(id, nilai, kosong) {
        const node = el(id);
        if (nilai) {
            node.textContent = nilai;
            node.classList.remove('is-empty');
        } else {
            node.textContent = kosong;
            node.classList.add('is-empty');
        }
    }

    function buka(btn) {
        const d = btn.dataset;
        lastTrigger = btn;

        isiTeks('dTanggal', d.tanggal, '-');
        if (el('dSiswa')) isiTeks('dSiswa', d.siswa, '-');
        if (el('dKelas')) isiTeks('dKelas', d.kelas, '-');
        isiTeks('dKategori', d.kategori, '-');
        isiTeks('dLokasi', d.lokasi, '-');
        isiTeks('dKet', d.ket, '-');
        isiTeks('dFeedback', d.feedback, 'Belum ada tanggapan dari admin');
        isiTeks('dTglFeedback', d.tglFeedback, '-');

        const status = el('dStatus');
        status.textContent = d.status;
        status.className = 'badge ' + d.badge;

        modal.hidden = false;
        document.body.classList.add('modal-open');
        modal.querySelector('.modal-close').focus();
    }

    function tutup() {
        modal.hidden = true;
        document.body.classList.remove('modal-open');
        if (lastTrigger) lastTrigger.focus();
    }

    document.querySelectorAll('[data-detail-open]').forEach(btn => {
        btn.addEventListener('click', () => buka(btn));
    });

    modal.querySelectorAll('[data-detail-close]').forEach(btn => {
        btn.addEventListener('click', tutup);
    });

    modal.addEventListener('click', e => {
        if (e.target === modal) tutup();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.hidden) tutup();
    });
})();
