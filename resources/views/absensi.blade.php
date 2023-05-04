@extends('layouts.app')

@push('heads')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
        integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
        integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
        crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Turf.js/6.5.0/turf.min.js"></script>
@endpush

@section('content')
    <div class="card p-3 p-md-4 mb-3">
        <h5 class="text-muted">Selamat Datang,</h5>
        <h2>{{ auth()->user()->nama }}</h2>
        <p class="text-muted m-0">{{ now()->format('d F Y') }}</p>
        @if ($todayAbsen?->logMasuk)
            <small class="text-muted mt-2">Anda telah melakukan absen masuk pada pukul
                {{ $todayAbsen->logMasuk->created_at->format('H:i') }} WIB
                @if ($todayAbsen->menit_terlambat > 0)
                    ( Terlambat {{ $todayAbsen->menit_terlambat }} Menit )
                @endif
                @if ($todayAbsen?->logKeluar)
                    dan telah melakukan absen keluar pada pukul
                    {{ $todayAbsen->logKeluar->created_at->format('H:i') }} WIB
                @endif
            </small>
        @endif
        <hr>
        @empty($jadwalAbsen)
            <small class="text-muted">Tidak ada jadwal absen pada hari ini.</small>
        @else
            <div class="d-flex gap-2 w-100">
                <button id="absen-button" @class([
                    'btn w-100',
                    'btn-success' => $tipeAbsen == 'MASUK',
                    'btn-danger text-white' => $tipeAbsen == 'KELUAR',
                    'd-none' => $todayAbsen?->logMasuk && $todayAbsen?->logKeluar,
                ]) data-bs-toggle="modal" data-bs-target="#modalAbsensi">
                    @if ($tipeAbsen == 'MASUK')
                        <i class="bi bi-box-arrow-in-right"></i> Absen Masuk
                    @else
                        <i class="bi bi-box-arrow-in-left"></i> Absen Keluar
                    @endif
                </button>
            </div>
        @endempty
    </div>

    {{-- Alert Berhasil --}}
    @if (session('berhasil'))
        <div class="alert alert-success alert-dismissible fade show border" role="alert">
            <strong>Berhasil! </strong> {!! session('berhasil') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border" role="alert">
            <strong>Ops! </strong> {{ collect($errors->all())->join(',') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert Gagal --}}
    @if (session('gagal'))
        <div class="alert alert-danger alert-dismissible fade show border" role="alert">
            <strong>Maaf! </strong> {!! session('gagal') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-3" style="height: 380px">
        <div id="map" style="height: 100%; width: 100%"></div>
    </div>

    <div class="card p-3">
        <div class="d-flex gap-3">
            <small>Latitude: <strong><span id="lat-info">-</span></strong></small>
            <small>Longitude: <strong><span id="lng-info">-</span></strong></small>
        </div>
    </div>

    <!-- Modal Absen Masuk -->
    <div class="modal fade" id="modalAbsensi" tabindex="-1" aria-labelledby="modalAbsensiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAbsensiLabel">Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="input-support" class="w-100" id="mobile-support">
                        <img src="{{ asset('newlabel.png') }}" width="100%" alt="frame" id="frame">
                        <input type="file" accept="image/*" id="input-support" class="d-none" capture="camera"
                            name="photo" onchange="preview()">
                        {{-- <small class="text-muted">* klik gambar untuk mengambil gambar anda.</small> --}}
                    </label>
                    <input type="hidden" name="latitude" id="lat-input">
                    <input type="hidden" name="longitude" id="lng-input">
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Batal
                    </button>
                    <button type="submit" @class([
                        'btn',
                        'btn-success' => $tipeAbsen == 'MASUK',
                        'btn-danger text-white' => $tipeAbsen == 'KELUAR',
                    ])>
                        @if ($tipeAbsen == 'MASUK')
                            <i class="bi bi-box-arrow-in-right"></i> Absen Masuk
                        @else
                            <i class="bi bi-box-arrow-in-left"></i> Absen Keluar
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // fungsi untuk update informasi coordinate di interface
        function updateCoordinateInterface({
            latitude,
            longitude
        }) {
            document.getElementById('lat-info').textContent = latitude
            document.getElementById('lng-info').textContent = longitude
            document.getElementById('lat-input').value = latitude
            document.getElementById('lng-input').value = longitude
        }

        // fungsi untuk preview photo
        function preview() {
            frame.src = URL.createObjectURL(event.target.files[0]);
        }

        // inisialisasi koordinat untuk membuat map (posisi koordinat di provinsi aceh)
        const initialCoordinates = {
            lat: 4.695135, // latitude
            lng: 96.7493993 // longitude
        }

        // inisiasi map menggunakan leaflet dengan koordinat yang telah di ditentukan diawal.
        let map = L.map('map', {
            zoomControl: false
        }).setView([initialCoordinates.lat, initialCoordinates.lng], 17)

        // disable zoom ketika di scroll
        map.scrollWheelZoom.disable()

        // menambahkan layer gambar map (jalan dan sebagainya)
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // data geojson (untuk menandakan lokasi sekolah, dapat dibuat di geojson.io)
        const data = {
            "type": "FeatureCollection",
            "features": [{
                "type": "Feature",
                "properties": {},
                "geometry": {
                    "coordinates": [
                        [
                            [
                                95.34002729867825,
                                5.542150232058404
                            ],
                            [
                                95.34013676373928,
                                5.54053799192458
                            ],
                            [
                                95.34148689983351,
                                5.53910777277855
                            ],
                            [
                                95.34329029590486,
                                5.539482125775578
                            ],
                            [
                                95.34225840617427,
                                5.540998732925601
                            ],
                            [
                                95.34264415934388,
                                5.542841693334793
                            ],
                            [
                                95.34002729867825,
                                5.542150232058404
                            ]
                        ]
                    ],
                    "type": "Polygon"
                }
            }]
        }

        // masukan data geojson diatas kedalam map leaflet.
        L.geoJSON(data, {
            style: function(feature) {
                return {
                    color: '#296b8e',
                    fillColor: '#76c3ed',
                    fillOpacity: 0.5
                };
            }
        }).addTo(map);

        // menambahkan marker dengan koordinat yang telah di inisialisasi diawal.
        let marker = L.marker([initialCoordinates.lat, initialCoordinates.lng]).addTo(map)
            .bindPopup('Mengambil informasi lokasi anda')
            .openPopup();

        // cek apakah marker (posisi gps) berada dalam polygon
        function verifyLocation(markerCoordinates) {
            // Buat point dari koordinat yang diterima
            let markerPoint = turf.point(markerCoordinates);
            // Mambuat data poligon dari geometri di data geojson diatas
            let polygon = turf.polygon(data.features[0].geometry.coordinates);
            // cek jika point ada didalam data poligon
            let isInside = turf.booleanPointInPolygon(markerPoint, polygon);
            // Kembalikan nilai dalam boolean
            return isInside;
        }

        // Fungsi untuk mengambil data lokasi user
        function getUserLocation() {
            // nonaktfikan tombol absen sebelum pengecekan
            document.getElementById('absen-button').disabled = true

            // cek jika API geolocation (untuk ambil data lokasi user) tersedia di browser yang dipakai.
            if ("geolocation" in navigator) {
                // Mengambil data lokasi user dengan metode watch (selalu di update).
                navigator.geolocation.watchPosition(
                    function(position) {
                        // Jika sukses, kita mendapatkan koordinat latitude dan longitude
                        // inisialisasi data koordinat yang telah berhasil didapatkan dari browser user
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;

                        // set tampilan map dengan koordinat yang telah diterima (sesuai posisi user)
                        map.setView([latitude, longitude], 17);
                        // set marker dengan koordinat yang telah diterima juga
                        marker.setLatLng([latitude, longitude]);
                        // update interface
                        updateCoordinateInterface({
                            latitude,
                            longitude
                        })

                        // cek jika lokasi user berada di dalam posisi geojson diatas
                        const isInside = verifyLocation([longitude, latitude])

                        // nonaktifkan tombol jika tidak berada di dalam posisi geojson
                        document.getElementById('absen-button').disabled = !isInside

                        // update juga tulisan pada popup marker nya
                        // jika berada di dalam geojson / lokasi maka tampilkan 'Anda berada di zonasi'
                        // jika tidak, tampilkan 'Anda tidak berada di zonasi'
                        marker.bindPopup(isInside ? 'Anda berada di zonasi' : 'Anda tidak berada di zonasi');
                    },
                    function(error) {
                        // Jika error jalankan perintah dibawah.
                        // tampilkan pesan error berdasarkan kode error yang diterima
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                alert("User tidak memberika izin lokasi");
                                showPermissionAlert();
                                break;
                            case error.POSITION_UNAVAILABLE:
                                alert("Informasi Lokasi tidak tersedia.");
                                break;
                            case error.TIMEOUT:
                                alert("Gagal mengambil informasi lokasi.");
                                break;
                            case error.UNKNOWN_ERROR:
                                alert("Terdapat kesalahan.");
                                break;
                        }
                    }
                );
            } else {
                // API geolocation tidak ada, tampilkan pesan dibawah ini.
                alert("Fitur lokasi tidak tersedia pada browser ini.");
            }
        }

        // Fungsi untuk menampilkan pesan izin penggunaan geolocation di browser user
        function showPermissionAlert() {
            // Tampilkan pesan ke user
            const alertInterval = setInterval(function() {
                alert(
                    "Mohon izinkan sistem untuk mengambil lokasi anda. silahkan aktifkan pada pengaturan dan reload halaman ini."
                );
            }, 1000); // Tampilkan pesan setiap 1 detik

            // Cek izin penggunaan geolocation di browser user.
            const permissionInterval = setInterval(function() {
                navigator.permissions
                    .query({
                        name: "geolocation"
                    })
                    .then(function(permissionStatus) {
                        if (permissionStatus.state === "granted") {
                            // Jika sudah diizinkan maka hentikan tampilan pesan.
                            clearInterval(alertInterval);
                            clearInterval(permissionInterval);

                            console.log("Location permission granted.");
                            getUserLocation();
                        }
                    });
            }, 1000);
        }

        // Panggil fungsi pengambilan lokasi user agar dijalankan.
        getUserLocation();
    </script>
@endpush
