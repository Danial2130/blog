<?php 
session_start(); 
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}
include 'includes/header.php'; 
?>

<div class="container my-5" style="max-width:900px;">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Hubungi Saya</h2>
        <p class="text-muted">Jangan ragu untuk menghubungi saya. Saya akan senang mendengar dari Anda!</p>
    </div>

    <div class="row g-4">
        <!-- Informasi Kontak -->
        <div class="col-md-5">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Informasi Kontak</h5>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                <i class="bi bi-envelope-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Email</h6>
                                <a href="mailto:dnialarshd@gmail.com" class="text-decoration-none">
                                    dnialarshd@gmail.com
                                </a>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                <i class="bi bi-geo-alt-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Lokasi</h6>
                                <p class="mb-0 text-muted">Jambi, Indonesia</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                <i class="bi bi-clock-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Waktu Respon</h6>
                                <p class="mb-0 text-muted">1-2 hari kerja</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="mb-3">Media Sosial</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-github"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Kontak -->
        <div class="col-md-7">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Kirim Pesan</h5>
                    
                    <form action="process_contact.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send-fill me-2"></i>Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section (Optional) -->
    <div class="mt-5">
        <h4 class="fw-bold mb-4">Pertanyaan Umum</h4>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Berapa lama waktu respon email?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Biasanya saya akan merespon dalam 1-2 hari kerja. Untuk pertanyaan mendesak, silakan mention di subjek email.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Apakah tersedia untuk proyek freelance?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Ya, saya terbuka untuk diskusi proyek freelance. Silakan kirim detail proyek via email.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>