<template>
    <div>
      <!-- Ajout du formulaire pour ajouter un utilisateur -->
      <div class="mt-4">
        <h3>Ajouter un nouvel utilisateur</h3>
        <form @submit.prevent="ajouterUtilisateur">
          <div class="mb-3">
            <label for="nomUtilisateur" class="form-label">Nom d'utilisateur :</label>
            <input type="text" v-model="nouvelUtilisateur.nom" class="form-control" id="nomUtilisateur" required>
          </div>
          <div class="mb-3">
            <label for="motDePasse" class="form-label">Mot de passe :</label>
            <input type="password" v-model="nouvelUtilisateur.motDePasse" class="form-control" id="motDePasse" required>
          </div>
          <div class="mb-3">
            <label for="prenom" class="form-label">Prénom :</label>
            <input type="text" v-model="nouvelUtilisateur.prenom" class="form-control" id="prenom" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" v-model="nouvelUtilisateur.email" class="form-control" id="email" required>
          </div>
          <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    data() {
      return {
        nouvelUtilisateur: {
          nom: '',
          motDePasse: '',
          prenom: '',
          email: ''
        }
      };
    },
    methods: {
    async ajouterUtilisateur() {
      try {
        // Utilisation d'Axios pour effectuer une requête POST vers votre API
        const response = await axios.post('http://localhost/applicationjose/src/api/newusers.php', this.nouvelUtilisateur);

        // Vérification de la réponse de l'API
        if (response.data.error === false) {
          console.log('Utilisateur ajouté avec succès:', response.data.message);
          
          // Réinitialiser le formulaire après l'ajout
          this.nouvelUtilisateur.nom = '';
          this.nouvelUtilisateur.motDePasse = '';
          this.nouvelUtilisateur.prenom = '';
          this.nouvelUtilisateur.email = '';
        } else {
          console.error('Erreur lors de l\'ajout de l\'utilisateur:', response.data.message);
          // Gérer l'erreur selon vos besoins
        }
      } catch (error) {
        console.error('Erreur lors de la requête POST:', error);
        // Gérer l'erreur selon vos besoins
      }
    }
  }
};
</script>
  