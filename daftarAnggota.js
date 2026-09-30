document.addEventListener('DOMContentLoaded', () => {
    const csvURL = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQIbkFyT5ttvCbpH2_yl5ZFlkeCS9EHMYyDSbxrJf3g_DcqAx3J7vi_KksUvftJ-LcNaSn4YrwEs7Ey/pub?gid=0&single=true&output=csv';

    const tableBody = document.getElementById('anggota-table-body');
    const table = document.getElementById('anggota-table');
    const spinner = document.getElementById('loading-spinner');
    const searchInput = document.getElementById('search-input');

    let allAnggota = [];
    const adminName = "Grateo Alfando Atmojo";

    async function fetchAnggota() {
        try {
            const response = await fetch(csvURL);
            if (!response.ok) {
                throw new Error('Gagal mengambil data dari Google Sheets.');
            }
            const csvText = await response.text();
            allAnggota = parseCSV(csvText);
            displayAnggota(allAnggota);
        } catch (error) {
            console.error('Gagal mengambil data anggota:', error);
            spinner.innerHTML = `<p class="text-danger">Maaf, gagal memuat data anggota. Coba lagi nanti.</p>`;
        }
    }

    function parseCSV(text) {
        const baris = text.trim().split('\n');
        const data = [];
        for (let i = 1; i < baris.length; i++) {
            const kolom = baris[i].split(',');
            const anggota = {
                timestamp: kolom[0],
                nama: kolom[1],
                nim: kolom[2],
                fakultas: kolom[3],
                asal: kolom[4],
                whatsapp: kolom[5],
                tanggalLahir: kolom[6],
                motivasi: kolom[7]
            };
            data.push(anggota);
        }
        return data;
    }

    function displayAnggota(anggotaList) {
        tableBody.innerHTML = '';
        
        // Urutkan data terbaru di atas
        let sortedList = [...anggotaList].reverse();

        // Cari dan pindahkan admin ke atas
        const adminIndex = sortedList.findIndex(anggota => anggota.nama.trim() === adminName);
        let adminAnggota = null;
        if (adminIndex > -1) {
            adminAnggota = sortedList.splice(adminIndex, 1)[0];
        }

        // Gabungkan kembali listnya dengan admin di paling atas jika ditemukan
        const finalList = adminAnggota ? [adminAnggota, ...sortedList] : sortedList;

        if (finalList.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Belum ada anggota yang mendaftar atau tidak ada hasil yang cocok.</td></tr>';
        } else {
            finalList.forEach((anggota, index) => {
                const isAdmin = anggota.nama.trim() === adminName;
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            ${anggota.nama}
                            ${isAdmin ? '<span class="badge bg-primary ms-2">DEV</span>' : ''}
                        </td>
                        <td>${anggota.fakultas}</td>
                        <td>${anggota.asal}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        }

        spinner.style.display = 'none';
        table.style.display = 'table';
    }

    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        
        const filteredAnggota = allAnggota.filter(anggota => {
            // Jangan sertakan admin dalam pemfilteran biasa jika dia ada
            if (anggota.nama.trim() === adminName) return true; 

            return (
                anggota.nama.toLowerCase().includes(searchTerm) ||
                anggota.fakultas.toLowerCase().includes(searchTerm) ||
                anggota.asal.toLowerCase().includes(searchTerm)
            );
        });
        
        displayAnggota(filteredAnggota);
    });

    fetchAnggota();
});