<?php include '../header.php'; ?>

<div class="container mt-4">
    <!-- Tombol Kembali ke Blog Utama -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="../index.php" class="btn-back-to-blog" id="backToBlogBtn">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Blog Utama</span>
                <div class="btn-shine"></div>
            </a>
        </div>
    </div>

    <!-- Konten About Page -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="about-card">
                <div class="about-header">
                    <h1 class="about-title">
                        <i class="fas fa-info-circle me-3"></i>Tentang BlogD
                    </h1>
                    <p class="about-subtitle">Selamat datang di halaman tentang blog kami</p>
                </div>
                
                <div class="about-content">
                    <div class="content-section">
                        <h3><i class="fas fa-bookmark me-2"></i>Apa itu BlogD?</h3>
                        <p>BlogD adalah platform blog modern yang dibuat dengan teknologi terbaru. Kami menyediakan ruang untuk berbagi ide, pemikiran, dan pengalaman dengan cara yang menarik dan interaktif.</p>
                    </div>

                    <div class="content-section">
                        <h3><i class="fas fa-target me-2"></i>Tujuan Kami</h3>
                        <ul class="custom-list">
                            <li><i class="fas fa-check"></i>Menyediakan platform untuk berbagi ide kreatif</li>
                            <li><i class="fas fa-check"></i>Membangun komunitas yang aktif dan supportive</li>
                            <li><i class="fas fa-check"></i>Memberikan pengalaman membaca yang menyenangkan</li>
                            <li><i class="fas fa-check"></i>Mengembangkan kreativitas dalam penulisan</li>
                        </ul>
                    </div>

                    <div class="content-section">
                        <h3><i class="fas fa-users me-2"></i>Tim Kami</h3>
                        <p>BlogD dikelola oleh tim yang berdedikasi untuk memberikan pengalaman terbaik bagi para pembaca dan penulis. Kami selalu berusaha untuk terus berkembang dan memberikan fitur-fitur terbaru.</p>
                    </div>

                    <div class="content-section">
                        <h3><i class="fas fa-envelope me-2"></i>Hubungi Kami</h3>
                        <p>Punya pertanyaan, saran, atau ingin berkolaborasi? Jangan ragu untuk menghubungi kami melalui halaman kontak atau langsung ke email kami.</p>
                        <a href="../contact.php" class="btn btn-contact">
                            <i class="fas fa-paper-plane me-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Tombol Kembali ke Blog Utama */
.btn-back-to-blog {
    display: inline-flex;
    align-items: center;
    padding: 15px 30px;
    background: linear-gradient(135deg, #FF6B6B 0%, #FF8E8E 50%, #FF6B6B 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position: relative;
    overflow: hidden;
    margin-bottom: 20px;
    border: none;
}

.btn-back-to-blog:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 15px 40px rgba(255, 107, 107, 0.6);
    color: white;
    text-decoration: none;
}

.btn-back-to-blog i {
    font-size: 1.3rem;
    margin-right: 12px;
    transition: transform 0.3s ease;
}

.btn-back-to-blog:hover i {
    transform: translateX(-5px);
}

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.6s ease;
}

.btn-back-to-blog:hover .btn-shine {
    left: 100%;
}

/* About Card Styling */
.about-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 25px;
    padding: 40px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 30px;
}

.about-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 2px solid var(--pastel-blue);
}

.about-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.about-subtitle {
    font-size: 1.2rem;
    color: var(--text-secondary);
    font-weight: 400;
    margin: 0;
}

.content-section {
    margin-bottom: 35px;
    padding: 25px;
    background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-blue));
    border-radius: 20px;
    transition: transform 0.3s ease;
}

.content-section:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.content-section h3 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    font-size: 1.4rem;
}

.content-section h3 i {
    color: var(--accent-color);
}

.content-section p {
    color: var(--text-secondary);
    line-height: 1.8;
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.custom-list {
    list-style: none;
    padding: 0;
}

.custom-list li {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.custom-list li i {
    color: var(--success-color);
    margin-right: 12px;
    font-size: 1rem;
}

.btn-contact {
    background: linear-gradient(135deg, var(--accent-color), #764BA2);
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    color: white;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 768px) {
    .about-title {
        font-size: 2rem;
        flex-direction: column;
    }
    
    .about-title i {
        margin-bottom: 10px;
        margin-right: 0 !important;
    }
    
    .about-card {
        padding: 25px;
        margin: 0 15px;
    }
    
    .btn-back-to-blog {
        font-size: 1rem;
        padding: 12px 25px;
    }
    
    .content-section {
        padding: 20px;
    }
}

/* Animasi masuk untuk konten */
.about-card {
    opacity: 0;
    animation: slideInUp 0.8s ease forwards;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.content-section {
    opacity: 0;
    animation: fadeInSection 0.6s ease forwards;
}

.content-section:nth-child(1) { animation-delay: 0.2s; }
.content-section:nth-child(2) { animation-delay: 0.4s; }
.content-section:nth-child(3) { animation-delay: 0.6s; }
.content-section:nth-child(4) { animation-delay: 0.8s; }

@keyframes fadeInSection {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const backBtn = document.getElementById('backToBlogBtn');
    
    if (backBtn) {
        backBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add click effect
            this.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                // Create slide-out transition
                document.body.style.transform = 'translateX(-100%)';
                document.body.style.transition = 'transform 0.6s ease-in-out';
                document.body.style.opacity = '0.8';
                
                // Navigate after animation
                setTimeout(() => {
                    window.location.href = this.href;
                }, 600);
            }, 150);
        });
    }
});
</script>

<?php include '../footer.php'; ?>