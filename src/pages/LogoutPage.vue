<template>
  <div class="container">
    <h1>Déconnexion</h1>
    <p>Êtes-vous sûr de vouloir vous déconnecter ?</p>
    <button @click="logout" class="btn btn-primary">Se déconnecter</button>
    <button @click="cancel" class="btn btn-secondary ml-4">Annuler</button>
  </div>
</template>

<script>
// Importez le service d'authentification
import AuthService from '../pages/AuthService.js'; // Assurez-vous que le chemin d'accès est correct

export default {
  methods: {
    logout() {
      // Effectuez ici une requête au serveur pour effectuer la déconnexion, par exemple via une API.
      // Vous pouvez également supprimer les informations de session ou de token ici.

      // Mettez à jour l'état d'authentification (si nécessaire)
      AuthService.logout();

      // Redirigez ensuite l'utilisateur vers la page de connexion ou la page d'accueil.
      this.$router.push('/LoginPage'); // Assurez-vous que le chemin est correct.
    },
    cancel() {
      // Redirigez l'utilisateur vers la page précédente ou une autre page, par exemple la page d'accueil.
      this.$router.go(-1); // Revenir à la page précédente (peut nécessiter de personnaliser en fonction de votre routeur).
    },
  },
  // Ajoutez votre guard de navigation ici
  beforeRouteEnter(to, from, next) {
    if (!AuthService.isAuthenticated()) {
      // Si l'utilisateur n'est pas authentifié, redirigez-le vers la page de connexion
      next('/LoginPage');
    } else {
      // Si l'utilisateur est authentifié, autorisez l'accès à la page de déconnexion
      next();
    }
  },
};

</script>
