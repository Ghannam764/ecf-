<header class="header">

   <section class="flex">

      <!-- Logo dell'Admin Panel -->
      <a href="dashboard.php" class="logo">AdminPanel.</a>

      <!-- Menu di navigazione -->
      <nav class="navbar">
         <a href="dashboard.php">Accueil</a>
         <a href="bookings.php">Réservations</a>
         <a href="admins.php">Admins</a>
         <a href="messages.php">Messages</a>
         <a href="register.php">S'inscrire</a>
         <!-- Il link al login può essere rimosso se l'utente è già loggato -->
         <a href="login.php">Se connecter</a>
         <!-- Logout -->
         <a href="../components/admin_logout.php" onclick="return confirm('Se déconnecter de ce site ?');">Déconnexion</a>
      </nav>

      <!-- Bottone per il menu mobile -->
      <div id="menu-btn" class="fas fa-bars"></div>

   </section>

</header>
