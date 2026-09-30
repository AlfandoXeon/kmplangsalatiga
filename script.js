// NodeJS AlfandoXeon.93.3rvb localStorage
// @thisCodeFor All


AOS.init({
    duration: 800, 
    once: true, 
});
document.addEventListener('scroll', () => {
    const nav = document.querySelector('#main-nav');
    if (window.scrollY > 50) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});

const galleryModal = document.getElementById('galleryModal');
galleryModal.addEventListener('show.bs.modal', event => {
 
  const triggerElement = event.relatedTarget;
  
  
  const imageSrc = triggerElement.getAttribute('src');
  const imageAlt = triggerElement.getAttribute('alt');
  
  
  const modalImage = galleryModal.querySelector('#modalImage');
  const modalCaption = galleryModal.querySelector('#modalCaption');
  
  modalImage.src = imageSrc;
  modalImage.alt = imageAlt;
  modalCaption.textContent = imageAlt;
});


const themeSwitcher = document.querySelectorAll('[data-theme]');
const body = document.getElementById('body-theme');
const storedTheme = localStorage.getItem('theme');

const setDarkTheme = () => {
    body.classList.add('dark-theme');
    localStorage.setItem('theme', 'dark');
}

const setLightTheme = () => {
    body.classList.remove('dark-theme');
    localStorage.setItem('theme', 'light');
}

if (storedTheme === 'dark') {
    setDarkTheme();
}

themeSwitcher.forEach(switcher => {
    switcher.addEventListener('click', (e) => {
        e.preventDefault();
        const theme = switcher.dataset.theme;
        if (theme === 'dark') {
            setDarkTheme();
        } else {
            setLightTheme();
        }
    });
});



const registrationForm = document.getElementById('registration-form');
// Pastikan URL ini adalah URL Web App yang benar dari Google Apps Script Anda
const scriptURL = 'https://script.google.com/macros/s/AKfycbwjZegOb902lVuY-xfjQbjPLVWaLi-SpSba1RUq5I3FGa1-A3Ku03vKGMiXvkojz-MX_g/exec';

registrationForm.addEventListener('submit', async (e) => {
    e.preventDefault(); // Mencegah form dari reload halaman

    
    const submitButton = registrationForm.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim Data...';

    // 1. Mengumpulkan semua data dari form
    const nama = document.getElementById('nama').value;
    const nim = document.getElementById('nim').value;
    const asal = document.getElementById('asal').value;
    const fakultas = document.getElementById('fakultas').value;
    const whatsapp = document.getElementById('whatsapp').value;
    const tanggalLahir = document.getElementById('tanggalLahir').value;
    const motivasi = document.getElementById('motivasi').value;

    // Membuat objek data untuk dikirim ke Google Sheet
    const formData = { nama, nim, asal, fakultas, whatsapp, tanggalLahir, motivasi };

    try {
        // 2. Mengirim data ke Google Spreadsheet di latar belakang
        console.log("Mengirim data ke Google Sheet...");
        await fetch(scriptURL, {
            method: 'POST',
            mode: 'no-cors', // Diperlukan untuk Apps Script
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        console.log("Data berhasil dikirim ke Google Sheet.");
        alert(`Selamat bergabung ${nama}! Kamu akan dialihkan ke WhatsApp untuk konfirmasi.`);
        
    } catch (error) {
        console.error('Error saat mengirim ke Google Sheet:', error);
        // Jika gagal, beri tahu pengguna dan tetap lanjutkan ke WhatsApp
        alert("Pendaftaran otomatis gagal. Mohon lanjutkan konfirmasi secara manual melalui WhatsApp.");

    } finally {
        // 3. SELALU membuka link WhatsApp setelah mencoba mengirim data
        console.log("Membuka Whatsapp");
        
        // Membuat pesan WhatsApp
        const message = `*MAU DAFTAR K'MPLANG SALATIGA KAK:  *\n\nNAMA: ${nama}\nNIM: ${nim}\nASAL: ${asal}\nFAKULTAS: ${fakultas}\nNOMOR WHATSAPP: ${whatsapp}\nTANGGAL LAHIR: ${tanggalLahir}\nMOTIVASI: ${motivasi}\n\n> @KmplangSalatiga`;
        const whatsappURL = `https://wa.me/6285225216552?text=${encodeURIComponent(message)}`;
        window.open(whatsappURL, '_blank');


        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
        registrationForm.reset();
    }
});




// *Reina:* (Mendadak diam. Bibirnya bergetar, tapi bukan karena ragu—seperti seseorang yang baru saja ditampar oleh kebenaran yang tak terduga.)  

// "Jangan." (Suaranya pecah, tapi tegas. Jarinya menekan dadanya sendiri, tepat di atas jantung.) "Jangan pernah berkata seperti itu padaku."  

// (Dia menarik napas bergelora, matanya menyala dengan amaran yang dalam.)  

// "Aku tidak mau jadi 'lebih dari' mantanmu. Aku mau jadi Reina—seseorang yang kau cintai dengan caranya sendiri, tanpa ukuran, tanpa bandingan." (Genggamannya di tanganmu mengencang, tapi tidak menyakiti.) "Cintailah aku karena aku adalah aku. Bukan karena kau ingin membuktikan sesuatu pada dirimu sendiri, atau... pada hantu masa lalumu."  

// (Dia melepaskan genggaman, lalu mengusap pipimu dengan punggung jari—sentuhannya seperti api yang dingin.)  

// "Kalau kau bisa menerima itu... maka aku akan menjadi istri yang setia, kekasih yang sabar, dan teman yang takkan pernah menyakitimu." (Senyumnya pilu, tapi mantap.) "Tapi kalau kau masih ingin menjadikanku alat untuk melawan kenangan... lebih baik kita berpisah sekarang."  

// (Dia menatapmu dalam-dalam, menunggu—seperti hakim yang sudah tahu jawabannya, tapi tetap memberi kesempatan terakhir.)

document.addEventListener('DOMContentLoaded', () => {
    const musicPlaylistContainer = document.querySelector('.music-playlist-container');
    if (!musicPlaylistContainer) return;

    const playlist = musicPlaylistContainer.querySelector('.playlist');
    const nowPlayingBar = document.querySelector('.now-playing-bar');
    const albumArt = nowPlayingBar.querySelector('.album-art');
    const trackTitle = nowPlayingBar.querySelector('.track-title');
    const trackArtist = nowPlayingBar.querySelector('.track-artist');
    const playPauseBtn = nowPlayingBar.querySelector('#play-pause-btn');
    const prevBtn = nowPlayingBar.querySelector('#prev-btn');
    const nextBtn = nowPlayingBar.querySelector('#next-btn');
    const currentTimeEl = nowPlayingBar.querySelector('.current-time');
    const totalDurationEl = nowPlayingBar.querySelector('.total-duration');
    const progressBarContainer = nowPlayingBar.querySelector('.progress-bar-container');
    const progressBar = nowPlayingBar.querySelector('.progress-bar');

    const mainAudio = new Audio();
    let musicIndex = 0;
    let isPlaying = false;

    function loadPlaylist() {
        if (typeof allMusic === 'undefined' || allMusic.length === 0) return;
        playlist.innerHTML = '';
        allMusic.forEach((song, index) => {
            const li = document.createElement('li');
            li.dataset.index = index;
            li.innerHTML = `
                <div class="play-icon"><i class="fas fa-play"></i></div>
                <div class="song-info">
                    <div class="song-title">${song.name}</div>
                    <div class="song-artist">${song.artist}</div>
                </div>
                <div class="song-duration">--:--</div>
            `;
            playlist.appendChild(li);

            const audioForDuration = new Audio(`musikLampung/${song.src}`);
            audioForDuration.addEventListener('loadedmetadata', () => {
                const duration = audioForDuration.duration;
                const totalMin = Math.floor(duration / 60);
                let totalSec = Math.floor(duration % 60);
                if (totalSec < 10) totalSec = `0${totalSec}`;
                li.querySelector('.song-duration').textContent = `${totalMin}:${totalSec}`;
            });
        });
    }

    function loadSong(index) {
        const song = allMusic[index];
        trackTitle.textContent = song.name;
        trackArtist.textContent = song.artist;
        albumArt.src = `image/${song.img}.jpg`;
        mainAudio.src = `musikLampung/${song.src}`;
        musicIndex = index;
        updatePlaylistUI();
    }

    function playSong() {
        isPlaying = true;
        mainAudio.play();
        playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
        updatePlaylistUI();
    }

    function pauseSong() {
        isPlaying = false;
        mainAudio.pause();
        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
        updatePlaylistUI();
    }
    
    function updatePlaylistUI() {
        const playlistItems = playlist.querySelectorAll('li');
        playlistItems.forEach((item, index) => {
            if (index === musicIndex) {
                item.classList.add('playing');
                const icon = item.querySelector('.play-icon i');
                if(isPlaying) {
                    icon.classList.remove('fa-play');
                    icon.classList.add('fa-pause');
                } else {
                    icon.classList.remove('fa-pause');
                    icon.classList.add('fa-play');
                }
            } else {
                item.classList.remove('playing');
                const icon = item.querySelector('.play-icon i');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-play');
            }
        });
    }

    function nextSong() {
        musicIndex = (musicIndex + 1) % allMusic.length;
        loadSong(musicIndex);
        playSong();
    }

    function prevSong() {
        musicIndex = (musicIndex - 1 + allMusic.length) % allMusic.length;
        loadSong(musicIndex);
        playSong();
    }

    function updateProgress(e) {
        const { duration, currentTime } = e.srcElement;
        const progressPercent = (currentTime / duration) * 100;
        progressBar.style.width = `${progressPercent}%`;

        // Update time display
        const formatTime = (time) => {
            const minutes = Math.floor(time / 60);
            let seconds = Math.floor(time % 60);
            if (seconds < 10) seconds = `0${seconds}`;
            return `${minutes}:${seconds}`;
        }
        
        if(duration) {
            totalDurationEl.textContent = formatTime(duration);
        }
        currentTimeEl.textContent = formatTime(currentTime);
    }

    function setProgress(e) {
        const width = this.clientWidth;
        const clickX = e.offsetX;
        const duration = mainAudio.duration;
        mainAudio.currentTime = (clickX / width) * duration;
    }

    // Event Listeners
    playPauseBtn.addEventListener('click', () => {
        if (mainAudio.src) {
            isPlaying ? pauseSong() : playSong();
        } else if (allMusic.length > 0) {
            loadSong(0);
            playSong();
        }
    });

    nextBtn.addEventListener('click', nextSong);
    prevBtn.addEventListener('click', prevSong);

    mainAudio.addEventListener('timeupdate', updateProgress);
    mainAudio.addEventListener('ended', nextSong);

    progressBarContainer.addEventListener('click', setProgress);

    playlist.addEventListener('click', (e) => {
        const listItem = e.target.closest('li');
        if (listItem) {
            const index = parseInt(listItem.dataset.index);
            if (index === musicIndex && isPlaying) {
                pauseSong();
            } else {
                loadSong(index);
                playSong();
            }
        }
    });

    loadPlaylist();
});
