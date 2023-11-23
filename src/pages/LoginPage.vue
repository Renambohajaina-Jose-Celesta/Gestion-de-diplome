<template>
  <div>

    <div class="container justify-content-center" style="margin-top: 160px;">
      <div class="row">
        <div class="col-md-4 ml-auto mr-auto">
          <div class="card my-4">
            <div class="card-heading bg-info">
              <img src="../assets/univ.png" alt="" class="bg-info mt-3 card-title ml-20" style="border-radius: 50px;">
              <h4 class="h card-title bg-info p-3 text-center">AUTHENTIFICATION</h4>
            </div>
            <div class="card-body">
              <!-- Ajout d'un message d'erreur -->
              <div v-if="loginError" class="alert alert-danger mt-3">
                {{ loginError }}
              </div>
              <form @submit.prevent="onLogin">
                <div class="form-row">
                  <div class="form-group col-md-12 input-group">
                    <span class="input-group-text"><i class="fa fa-user fa-2x mr-2"></i></span>
                    <input type="text" placeholder="Nom d'utilisateur" id="User" class="form-control" required="jose"
                      style=" border: 2px solid rgba(0, 0, 255, 0.477);" v-model="users.username">
                  </div>
                  <div class="form-group col-md-12 mt-6 input-group">
                    <span class="input-group-text "><i class="fa fa-lock fa-2x mr-2"></i></span>
                    <input type="password" placeholder="Mot de passe" id="Mdp" class="form-control" required=""
                      style=" border: 2px solid rgba(0, 0, 255, 0.477); just" v-model="users.password">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12 mt-4">
                    <!-- <button class="btn btn-success btn-lg w-40" type="submit" > -->
                      <button type="submit" class="btn btn-success w-40" style="border-radius: 10px;">Connexion</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import AuthService from '../pages/AuthService.js'; // Assurez-vous que le chemin d'accès est correct

export default {
  data() {
    return {
      users: {
        username: '',
        password: ''
      },
      loginError: ''
    };
  },
  methods: {
    async onLogin() {
      if (!this.users.username || !this.users.password) {
        this.loginError = "Veuillez remplir tous les champs.";
        return;
      }
      if (!this.users.username) {
        this.loginError = "Veuillez remplir le nom d'utilisateur.";
        return;
      }
      if (!this.users.password) {
        this.loginError = "Veuillez remplir le mot de passe.";
        return;
      }

      try {
        const requestData = {
          username: this.users.username,
          password: this.users.password
        };

        const res = await axios.post("http://localhost/applicationjose/src/api/login.php", requestData);

        if (res.data.error === false) {
          // Mettez à jour l'état d'authentification ici
          AuthService.login();
          this.$router.push({ name: 'DashboardDiplome' });
        } else {
          this.loginError = "Nom d'utilisateur ou mot de passe incorrect.";
        }
      } catch (err) {
        console.error("Erreur", err);
      }
    }
  }
};
</script>


<style></style>
