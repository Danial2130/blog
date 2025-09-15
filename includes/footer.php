<!-- Footer -->
</main>
<footer class="footer-simple">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mb-4">
        <div class="footer-brand">
          <h4 class="brand-name">BlogD ✨</h4>
          <p class="brand-description">
            Platform blog yang menginspirasi untuk berbagi ide, bertanya-tanya, dan mengekspresikan kreativitas.
          </p>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="footer-section">
          <h5 class="footer-title">Navigasi</h5>
          <ul class="footer-links">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="index.php?category=Ide">Ide</a></li>
            <li><a href="index.php?category=Bertanya-tanya">Bertanya-tanya</a></li>
            <li><a href="index.php?category=Random">Random</a></li>
            <li><a href="/blog/about/index.php">Tentang</a></li>
            <li><a href="contact.php">Kontak</a></li>
          </ul>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="footer-section">
          <h5 class="footer-title">Hubungi Kami</h5>
          <div class="contact-info">
            <p><i class="fas fa-envelope me-2"></i>hello@blogd.com</p>
            <p><i class="fas fa-phone me-2"></i>+62 123 4567 890</p>
          </div>
          <div class="social-links">
            <a href="#" class="social-link" title="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-link" title="Twitter">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-link" title="Instagram">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="social-link" title="LinkedIn">
              <i class="fab fa-linkedin-in"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <hr class="footer-divider">
    
    <div class="footer-bottom text-center">
      <p class="copyright">
        &copy; <?php echo date('Y'); ?> BlogD. Dibuat dengan <span class="heart">❤️</span> untuk komunitas yang menginspirasi.
      </p>
    </div>
  </div>
  
  <!-- Scroll to Top Button -->
  <div class="scroll-to-top" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </div>
</footer>

<style>
.footer-simple {
  background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
  color: white;
  padding: 3rem 0 1.5rem;
  margin-top: 4rem;
  position: relative;
}

.footer-simple::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.1);
  pointer-events: none;
}

.footer-brand {
  position: relative;
  z-index: 2;
}

.brand-name {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: white;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.brand-description {
  color: rgba(255, 255, 255, 0.9);
  line-height: 1.6;
  font-size: 1rem;
  margin: 0;
}

.footer-section {
  position: relative;
  z-index: 2;
}

.footer-title {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 1.2rem;
  color: white;
  position: relative;
  padding-bottom: 0.5rem;
}

.footer-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 30px;
  height: 2px;
  background: var(--pastel-yellow);
  border-radius: 2px;
}

.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-links li {
  margin-bottom: 0.6rem;
}

.footer-links a {
  color: rgba(255, 255, 255, 0.85);
  text-decoration: none;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  font-weight: 400;
}

.footer-links a:hover {
  color: var(--pastel-yellow);
  text-decoration: none;
  padding-left: 5px;
}

.contact-info {
  margin-bottom: 1.5rem;
}

.contact-info p {
  color: rgba(255, 255, 255, 0.85);
  margin-bottom: 0.6rem;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
}

.contact-info i {
  color: var(--pastel-yellow);
  width: 18px;
}

.social-links {
  display: flex;
  gap: 0.8rem;
}

.social-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  color: white;
  text-decoration: none;
  font-size: 1.1rem;
  transition: all 0.3s ease;
}

.social-link:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: var(--pastel-yellow);
  color: var(--pastel-yellow);
  transform: translateY(-2px);
}

.footer-divider {
  border: none;
  height: 1px;
  background: rgba(255, 255, 255, 0.2);
  margin: 2rem 0 1.5rem;
}

.footer-bottom {
  position: relative;
  z-index: 2;
}

.copyright {
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.9rem;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.heart {
  color: #ff6b6b;
  animation: heartbeat 2s ease-in-out infinite;
}

@keyframes heartbeat {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

.scroll-to-top {
  position: fixed;
  bottom: 25px;
  right: 25px;
  width: 45px;
  height: 45px;
  background: linear-gradient(135deg, var(--accent-color), #764BA2);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
  opacity: 0;
  visibility: hidden;
  z-index: 1000;
}

.scroll-to-top.show {
  opacity: 1;
  visibility: visible;
}

.scroll-to-top:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .footer-simple {
    padding: 2rem 0 1rem;
    text-align: center;
  }
  
  .footer-brand {
    text-align: center;
    margin-bottom: 2rem;
  }
  
  .brand-name {
    font-size: 1.8rem;
  }
  
  .social-links {
    justify-content: center;
  }
  
  .footer-title::after {
    left: 50%;
    transform: translateX(-50%);
  }
  
  .scroll-to-top {
    bottom: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
  }
}
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Simple JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Scroll to top functionality
  const scrollToTop = document.getElementById('scrollToTop');
  
  window.addEventListener('scroll', function() {
    if (window.pageYOffset > 300) {
      scrollToTop.classList.add('show');
    } else {
      scrollToTop.classList.remove('show');
    }
  });
  
  scrollToTop.addEventListener('click', function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
  
  // Simple hover effects for social links
  const socialLinks = document.querySelectorAll('.social-link');
  socialLinks.forEach(link => {
    link.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-2px) scale(1.05)';
    });
    
    link.addEventListener('mouseleave', function() {
      this.style.transform = '';
    });
  });
});
</script>
<!-- Portal animation element -->
<div id="portal-animation"></div>

<!-- Script portal -->
<script>
  document.querySelectorAll(".portal-link").forEach(link => {
    link.addEventListener("click", function(e) {
      e.preventDefault();
      const portal = document.getElementById("portal-animation");
      portal.classList.add("active");

      // Optional: fade out body
      document.body.classList.add("portal-fade");

      setTimeout(() => {
        window.location.href = this.href;
      }, 800); // delay harus lebih kecil dari CSS transition
    });
  });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
