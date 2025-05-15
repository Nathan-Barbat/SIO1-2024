<!-- views/layout/footer.php -->
    </main>
    
    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-5">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © <?php echo date('Y'); ?> QuizApp - BTS SIO SLAM
        </div>
    </footer>
    
    <!-- Bootstrap JS, Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (nécessaire pour certaines fonctionnalités) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    
    <!-- Script spécifique à la page si nécessaire -->
    <?php if (isset($pageScript)): ?>
        <script src="assets/js/<?php echo $pageScript; ?>.js"></script>
    <?php endif; ?>
</body>
</html>