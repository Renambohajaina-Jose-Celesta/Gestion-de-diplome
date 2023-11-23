// AuthService.js

let isAuthenticated = false;

export default {
  login() {
    // Logique pour effectuer la connexion
    isAuthenticated = true;
  },
  logout() {
    // Logique pour effectuer la déconnexion
    isAuthenticated = false;
  },
  isAuthenticated() {
    // Vérifie si l'utilisateur est authentifié
    return isAuthenticated;
  }
};
