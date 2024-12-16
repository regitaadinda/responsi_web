<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Leaflet dengan Bootstrap</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="plugin/leaflet-search-master/leaflet-search-master/dist/leaflet-search.min.css">
    <script src="plugin/leaflet-search-master/leaflet-search-master/dist/leaflet-search.min.js"></script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

          <style>
        #map {
            height: 500px;
            margin: 0 auto;
            margin-bottom: 30px;
        }

        .bg-green {
            background-color: rgba(73, 159, 50, 0.695) !important;
        }

        body {
            background-color: #e8f5e9;
        }

        .navbar, .footer {
            background-color: #388e3c;
        }

        .navbar-nav .nav-link {
            color: #ffffff;
        }

        .navbar-nav .nav-link:hover {
            color: #c8e6c9;
        }

        .footer {
            background-color: #388e3c;
            color: white;
        }

        .table-dark {
            background-color: #388e3c;
        }

        /* Styling for table */
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #c8e6c9;
        }

        .btn-action {
            transition: transform 0.3s ease;
        }

        .btn-action:hover {
            transform: scale(1.1);
        }

        .table-hover tbody tr:hover {
            background-color: #b2dfdb;
        }

        /* Add custom button styling */
        .btn-outline-success, .btn-outline-danger {
            border-radius: 20px;
            padding: 8px 16px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2d6a4f;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">HealthInfo Surabaya</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#map">Peta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Kontak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#infoModal"><i
                            class="fa-solid fa-circle-info"></i> Info</a>
                </li>
                <!-- Tombol Edit Data -->
                <li class="nav-item">
                    <a class="nav-link btn btn-warning text-dark ms-2" href="#table-container">Edit Data</a>
                </li>
            </ul>
        </div>
    </div>
</nav>



    </header>
    <!-- Hero Section -->
    <section class="bg-green text-white py-5">
        <div class="container text-center">
            <h1 class="display-5">SATU PETA SURABAYA</h1>
            <p class="lead">Jelajahi data fasilitas kesehatan dengan visualisasi yang menarik dan responsif</p>
        </div>
    </section>

    <!-- Main Content -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-lg">
            <div class="card-header" style="background-color: #2d6a4f; color: white; text-align: center; font-weight: bold;">
                    <i class="fas fa-map-signs"></i> Keterangan Peta
                </div>
                <div class="card-body">
                    <p class="text-muted">Gunakan kontrol di peta untuk mengatur layer.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-area-chart"></i> <strong>Poligon:</strong> Menampilkan batas kecamatan</li>
                        <li class="list-group-item"><i class="fas fa-road"></i> <strong>Garis:</strong> Menampilkan jaringan jalan</li>
                        <li class="list-group-item"><i class="fas fa-hospital"></i> <strong>Titik:</strong> Menampilkan lokasi fasilitas kesehatan</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Peta -->
        <div class="col-md-9">
            <div id="map" class="mx-auto" style="height: 500px; border: 2px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <!-- Peta akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>

<!-- Optional: Add Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>


    <!-- Tabel Data -->
<div id="container" class="container-fluid mt-4">
    <div id="table-container">
        <table class="table table-striped table-bordered table-hover shadow-sm rounded">
            <thead class="table-dark">
                <tr>
                <th class="text-center">Nama Kecamatan</th>
                <th class="text-center">Longitude</th>
                <th class="text-center">Latitude</th>
                <th class="text-center">Nama Faskes</th>
                <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli("localhost", "root", "", "responsi");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT * FROM lokasi";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["kecamatan"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["longitude"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["latitude"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nama_faskes"]) . "</td>";
                        echo "<td>
                            <div class='d-flex justify-content-center'>
                                <a href='edit.php?id=" . $row["id"] . "' class='btn btn-outline-success btn-action me-2'>Edit</a>
                                <a href='delete.php?id=" . $row["id"] . "' class='btn btn-outline-danger btn-action' onclick=\"return confirm('Hapus data ini?')\">Delete</a>
                            </div>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

    <!-- About Section -->
<section id="about" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Tentang Fasilitas Kesehatan</h2>
        
        <!-- Carousel -->
        <div id="aboutCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <img src="img/RS.jpg" class="d-block w-50 img-fluid mx-auto" alt="Gambar Rumah Sakit">
            <div class="text-center mt-3">
                <h5>Rumah Sakit</h5>
                <p>Rumah sakit adalah fasilitas kesehatan yang menyediakan pelayanan medis secara menyeluruh, baik dalam bentuk rawat inap, rawat jalan, maupun perawatan darurat. Di Surabaya, terdapat lebih dari 40 rumah sakit yang tersebar di berbagai wilayah kota. Rumah sakit-rumah sakit ini termasuk rumah sakit umum, rumah sakit khusus, dan rumah sakit dengan spesialisasi tertentu seperti rumah sakit mata, rumah sakit jiwa, serta rumah sakit onkologi.</p>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="img/puskesmas.jpg" class="d-block w-50 img-fluid mx-auto" alt="Gambar Puskesmas">
            <div class="text-center mt-3">
                <h5>Puskesmas</h5>
                <p>Puskesmas ini menyediakan berbagai layanan kesehatan dasar, termasuk pemeriksaan rutin, imunisasi, pengobatan, dan layanan darurat. Di Surabaya, terdapat 63 puskesmas yang tersebar di seluruh wilayah kota, memberikan pelayanan kesehatan yang dapat diakses oleh masyarakat.</p>
            </div>
        </div>
        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="img/apotek.jpg" class="d-block w-50 img-fluid mx-auto" alt="Gambar Apotek">
            <div class="text-center mt-3">
                <h5>Apotek</h5>
                <p>Di Surabaya, terdapat sekitar 871 apotek yang tersebar di berbagai kawasan kota. Apotek-apotek ini berfungsi sebagai tempat untuk menyediakan berbagai jenis obat, mulai dari obat resep hingga obat bebas, serta produk kesehatan lainnya. Beberapa apotek juga menawarkan layanan konsultasi kesehatan dan pemeriksaan, selain penjualan produk-produk medis.</p>
            </div>
        </div>
   
            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section> 



    <!-- Contact Section -->
    <section id="contact" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Kontak</h2>
            <p class="text-center">Jika Anda memiliki pertanyaan atau masukan, silakan hubungi melalui email: <a href="mailto:regita.adinda73@gmail.com">regita.adinda73@gmail.com</a>.</p>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="infoModalLabel">Info Pembuat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tr>
                            <th>Nama</th>
                            <td>Regita Adinda Sefty</td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td>23/515190/SV/22498</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>A</td>
                        </tr>
                        <tr>
                            <th>Github</th>
                            <td><a href="http://github.com/regita" target="_blank"
                                    rel="noopener noreferrer">http://github.com/regita</a></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Modal -->
    <div class="modal fade" id="featureModal" tabindex="-1" aria-labelledby="featureModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="featureModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="featureModalBody">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-green text-white text-center py-4">
        <p class="mb-0">&copy; 2024 HealthInfo Surabaya by Regita</p>
    </footer>

    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="plugin/leaflet-search-master/leaflet-search-master/dist/leaflet-search.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
    // === Initialize Map ===
    var map = L.map("map").setView([-7.2575, 112.7521], 12); // Coordinates for Surabaya

    // === Tile Layer ===
    var basemap = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // === Area Data (GeoJSON) ===
    var area = L.geoJSON(null, {
        style: function (feature) {
            return {
                color: "blue",
                weight: 1.5,
                fillColor: "lightblue",
                fillOpacity: 0.5
            };
        },
        onEachFeature: function (feature, layer) {
            var popup_content = "Nama Kecamatan: " + feature.properties.WADMKC;
            layer.bindPopup(popup_content);
        }
    });

    $.getJSON("data/Area.geojson", function (data) {
        area.addData(data);
        map.addLayer(area);
    });

    // === Road Data (GeoJSON) ===
    var jalan = L.geoJSON(null, {
        style: function (feature) {
            return {
                color: "red",
                weight: 2,
                opacity: 0.8
            };
        },
        onEachFeature: function (feature, layer) {
            var popup_content = "Nama Jalan: " + feature.properties.REMARK;
            layer.bindPopup(popup_content);
        }
    });

    $.getJSON("data/Jalan.geojson", function (data) {
        jalan.addData(data);
        map.addLayer(jalan);
    });

    // === Health Facility Points (GeoJSON) ===
    var titik_rs = L.geoJSON(null, {
        pointToLayer: function (feature, latlng) {
            return L.marker(latlng, {
                icon: L.icon({
                    iconUrl: "icon/RS_marker.png", // Use your own marker icon
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                })
            });
        },
        onEachFeature: function (feature, layer) {
            var popup_content = "Nama Fasilitas Kesehatan: " + feature.properties.REMARK + "<br>" +
                                "Koordinat: " + feature.geometry.coordinates[1] + ", " + feature.geometry.coordinates[0];
            layer.bindPopup(popup_content);
        }
    });

    $.getJSON("data/Titik_RS.geojson", function (data) {
        titik_rs.addData(data);
        map.addLayer(titik_rs);
    });

    // === Layer Control ===
    var baseMaps = {
        "OpenStreetMap": basemap
    };

    var overlayMaps = {
        "Poligon": area,
        "Garis": jalan,
        "Titik": titik_rs
    };

    L.control.layers(baseMaps, overlayMaps).addTo(map);

     //Search Control
     var searchControl = new L.Control.Search({
            layer: area,
            propertyName: 'WADMKC',
            marker: false,
            moveToLocation: function (latlng, title, map) {
                //map.fitBounds( latlng.layer.getBounds() );
                var zoom = map.getBoundsZoom(latlng.layer.getBounds());
                map.setView(latlng, zoom); // access the zoom
            }
        });

        searchControl.on('search:locationfound', function (e) {

            //console.log('search:locationfound', );

            //map.removeLayer(this._markerSearch)

            e.layer.setStyle({ fillColor: '#2596be', color: '#0f0' });
            if (e.layer._popup)
                e.layer.openPopup();

        }).on('search:collapsed', function (e) {

            area.eachLayer(function (layer) {	//restore feature color
                area.resetStyle(layer);
            });
        });

        map.addControl(searchControl);  //inizialize search control

        //Default Extent Control
        L.control.defaultExtent()
            .addTo(map);

    </script>

</body>
</html>
